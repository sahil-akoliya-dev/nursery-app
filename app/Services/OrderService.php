<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CartItem;
use App\Models\LoyaltyPoint;
use App\Mail\OrderConfirmation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class OrderService
{
    private const CANCEL_DEADLINE_HOURS = 24;
    private const TAX_RATE = 0.08;
    private const SHIPPING_COST = 9.99;
    private const FREE_SHIPPING_THRESHOLD = 50;
    private const LOYALTY_POINTS_RATE = 1; // 1 point per $1

    /**
     * Create order from cart with inventory reservation and payment processing
     *
     * @param int $userId
     * @param array $orderData
     * @return Order
     * @throws \Exception
     */
    public function createOrder(int $userId, array $orderData): Order
    {
        DB::beginTransaction();

        try {
            // Lock cart items for update to prevent race conditions
            $cartItems = CartItem::where('user_id', $userId)
                ->lockForUpdate()
                ->with(['item'])
                ->get();

            if ($cartItems->isEmpty()) {
                throw new \Exception('Cart is empty');
            }

            // Validate stock availability for all items
            $invalidItems = [];
            foreach ($cartItems as $cartItem) {
                $item = $cartItem->item;

                if (!$item || !$item->is_active) {
                    $invalidItems[] = 'Item is no longer available';
                    continue;
                }

                if (!$item->in_stock) {
                    $invalidItems[] = "{$item->name} is out of stock";
                    continue;
                }

                if ($item->stock_quantity < $cartItem->quantity) {
                    $invalidItems[] = "Only {$item->stock_quantity} of {$item->name} available (requested: {$cartItem->quantity})";
                }
            }

            if (!empty($invalidItems)) {
                throw new \Exception('Some items in your cart are no longer available or have insufficient stock');
            }

            // Calculate totals
            $subtotal = $cartItems->sum(function ($item) {
                return $item->quantity * $item->price;
            });
            $tax = round($subtotal * self::TAX_RATE, 2);
            $shipping = $subtotal >= self::FREE_SHIPPING_THRESHOLD ? 0 : self::SHIPPING_COST;
            $total = round($subtotal + $tax + $shipping, 2);

            // Process payment (if payment gateway integrated)
            $paymentResult = $this->processPayment(
                $orderData['payment_method'] ?? 'cod',
                $total,
                $orderData
            );

            // Generate unique order number
            $orderNumber = $this->generateOrderNumber();

            // Create order
            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => $userId,
                'subtotal' => $subtotal,
                'tax_amount' => $tax,
                'shipping_amount' => $shipping,
                'total_amount' => $total,
                'status' => 'pending',
                'payment_status' => $paymentResult['status'],
                'payment_method' => $orderData['payment_method'] ?? 'cod',
                'payment_transaction_id' => $paymentResult['transaction_id'] ?? null,
                'shipping_address' => $orderData['shipping_address'],
                'billing_address' => $orderData['billing_address'],
                'notes' => $orderData['notes'] ?? null
            ]);

            // Create order items and update inventory
            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'item_id' => $cartItem->item_id,
                    'item_type' => $cartItem->item_type,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->price
                ]);

                // Update stock with lock to prevent overselling
                $item = $cartItem->item;
                $item->lockForUpdate();
                $newQuantity = $item->stock_quantity - $cartItem->quantity;

                if ($newQuantity < 0) {
                    throw new \Exception("Insufficient stock for {$item->name}");
                }

                $item->update([
                    'stock_quantity' => $newQuantity,
                    'in_stock' => $newQuantity > 0
                ]);
            }

            // Clear cart
            CartItem::where('user_id', $userId)->delete();

            // Award loyalty points
            $this->awardLoyaltyPoints($userId, $order->id, $total);

            // Send order confirmation email
            try {
                $order->load('user', 'orderItems.item');
                Mail::to($order->user->email)->send(new OrderConfirmation($order));
            } catch (\Exception $e) {
                // Log error but don't fail the order
                \Log::error('Failed to send order confirmation email: ' . $e->getMessage());
            }

            // Process Vendor Commissions
            $this->processVendorCommissions($order, $cartItems);

            DB::commit();

            return $order->fresh(['orderItems.item']);

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Cancel order with refund policy
     *
     * @param int $orderId
     * @param int $userId
     * @param string|null $reason
     * @return bool
     * @throws \Exception
     */
    public function cancelOrder(int $orderId, int $userId, ?string $reason = null): bool
    {
        $order = Order::where('id', $orderId)
            ->where('user_id', $userId)
            ->lockForUpdate()
            ->first();

        if (!$order) {
            throw new \Exception('Order not found');
        }

        // Check cancellation deadline (24 hours)
        $hoursSinceOrder = now()->diffInHours($order->created_at);
        if ($hoursSinceOrder > self::CANCEL_DEADLINE_HOURS) {
            throw new \Exception('Order cannot be cancelled after 24 hours');
        }

        // Only pending or processing orders can be cancelled
        if (!in_array($order->status, ['pending', 'processing'])) {
            throw new \Exception('Order cannot be cancelled in current status');
        }

        DB::beginTransaction();

        try {
            // Restore inventory
            foreach ($order->orderItems as $orderItem) {
                $item = $orderItem->item;
                if ($item) {
                    $item->lockForUpdate();
                    $item->increment('stock_quantity', $orderItem->quantity);
                    $item->update(['in_stock' => true]);
                }
            }

            // Process refund if payment was made
            if ($order->payment_status === 'paid') {
                $this->processRefund($order);
            }

            // Reverse loyalty points
            $this->reverseLoyaltyPoints($userId, $order->id);

            // Update order status
            $order->update([
                'status' => 'cancelled',
                'payment_status' => $order->payment_status === 'paid' ? 'refunded' : $order->payment_status,
                'cancelled_at' => now(),
                'cancellation_reason' => $reason ?? 'Cancelled by customer'
            ]);

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Process payment via gateway
     *
     * @param string $paymentMethod
     * @param float $amount
     * @param array $orderData
     * @return array
     */
    private function processPayment(string $paymentMethod, float $amount, array $orderData): array
    {
        // For college project, simulate payment
        // In production, integrate with Stripe/PayPal

        switch ($paymentMethod) {
            case 'stripe':
                return $this->processStripePayment($amount, $orderData);
            case 'paypal':
                return $this->processPayPalPayment($amount, $orderData);
            case 'cod': // Cash on Delivery
                return [
                    'status' => 'pending',
                    'transaction_id' => null
                ];
            default:
                return [
                    'status' => 'pending',
                    'transaction_id' => null,
                ];
        }
    }

    /**
     * Process Stripe payment (placeholder for integration)
     */
    private function processStripePayment(float $amount, array $orderData): array
    {
        // Integration with Stripe SDK
        // \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
        // $charge = \Stripe\Charge::create([...]);

        // For now, return success
        return [
            'status' => 'paid',
            'transaction_id' => 'txn_' . Str::random(24)
        ];
    }

    /**
     * Process PayPal payment (placeholder for integration)
     */
    private function processPayPalPayment(float $amount, array $orderData): array
    {
        // Integration with PayPal SDK
        // For now, return success
        return [
            'status' => 'paid',
            'transaction_id' => 'paypal_' . Str::random(24)
        ];
    }

    /**
     * Process refund
     */
    private function processRefund(Order $order): void
    {
        // Integrate with payment gateway refund API
        // For now, just log
        \Log::info("Refund processed for order {$order->order_number}");
    }

    /**
     * Award loyalty points
     */
    private function awardLoyaltyPoints(int $userId, int $orderId, float $orderTotal): void
    {
        $points = (int) floor($orderTotal * self::LOYALTY_POINTS_RATE);

        if ($points <= 0) {
            return;
        }

        $currentBalance = LoyaltyPoint::where('user_id', $userId)
            ->where('type', 'earned')
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->sum('points');

        LoyaltyPoint::create([
            'user_id' => $userId,
            'points' => $points,
            'type' => 'earned',
            'source' => 'purchase',
            'order_id' => $orderId,
            'points_balance' => $currentBalance + $points,
            'description' => "Points from order #{$orderId}",
            'expires_at' => now()->addMonths(12) // Expires in 12 months
        ]);
    }

    /**
     * Reverse loyalty points on cancellation
     */
    private function reverseLoyaltyPoints(int $userId, int $orderId): void
    {
        $pointsRecord = LoyaltyPoint::where('user_id', $userId)
            ->where('order_id', $orderId)
            ->where('type', 'earned')
            ->first();

        if ($pointsRecord) {
            LoyaltyPoint::create([
                'user_id' => $userId,
                'points' => -$pointsRecord->points,
                'type' => 'redeemed',
                'source' => 'order_cancellation',
                'order_id' => $orderId,
                'description' => "Points reversed due to order cancellation"
            ]);
        }
    }

    /**
     * Generate unique order number
     *
     * @return string
     */
    private function generateOrderNumber(): string
    {
        do {
            $orderNumber = 'ORD-' . strtoupper(Str::random(8));
        } while (Order::where('order_number', $orderNumber)->exists());

        return $orderNumber;
    }

    /**
     * Calculate and record vendor commissions
     *
     * @param Order $order
     * @param \Illuminate\Support\Collection $cartItems
     */
    private function processVendorCommissions(Order $order, $cartItems): void
    {
        // Group items by vendor
        $itemsByVendor = $cartItems->groupBy(function ($cartItem) {
            return $cartItem->item->vendor_id;
        });

        foreach ($itemsByVendor as $vendorId => $items) {
            if (!$vendorId)
                continue; // Skip items with no vendor (admin products)

            $vendor = \App\Models\Vendor::find($vendorId);
            if (!$vendor)
                continue;

            $vendorTotal = $items->sum(function ($item) {
                return $item->price * $item->quantity;
            });

            // Calculate commission
            $commissionAmount = $vendorTotal * ($vendor->commission_rate / 100);
            $netEarnings = $vendorTotal - $commissionAmount;

            // Create transaction record
            \App\Models\VendorTransaction::create([
                'vendor_id' => $vendorId,
                'order_id' => $order->id,
                'amount' => $netEarnings,
                'type' => 'sale',
                'description' => "Earnings from Order #{$order->order_number} (Total: \${$vendorTotal}, Commission: " . $vendor->commission_rate . "%)",
                'status' => 'pending', // Pending until order is completed/delivered
            ]);
        }
    }
}
