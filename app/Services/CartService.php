<?php

namespace App\Services;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\Plant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;

class CartService
{
    private const MAX_CART_ITEMS = 50;
    private const MAX_QUANTITY_PER_ITEM = 100;
    private const CART_EXPIRY_DAYS = 30;
    private const TAX_RATE = 0.08;
    private const SHIPPING_COST = 9.99;
    private const FREE_SHIPPING_THRESHOLD = 50;

    /**
     * Add item to cart with validation
     *
     * @param int $userId
     * @param int $itemId
     * @param string $itemType ('product' or 'plant')
     * @param int $quantity
     * @return array
     * @throws \Exception
     */
    public function addItem(int $userId, int $itemId, string $itemType, int $quantity = 1): array
    {
        // Validate quantity
        if ($quantity < 1 || $quantity > self::MAX_QUANTITY_PER_ITEM) {
            throw new \InvalidArgumentException(
                "Quantity must be between 1 and " . self::MAX_QUANTITY_PER_ITEM
            );
        }

        // Validate item type
        $normalizedItemType = $this->normalizeItemType($itemType);

        // Get item
        $item = $this->getItem($itemId, $normalizedItemType);
        
        // Validate item exists
        if (!$item) {
            throw new \Exception('Item not found');
        }
        
        // Validate item is active
        if (!$item->is_active) {
            throw new \Exception('Item is no longer available');
        }

        if (!$item->in_stock || $item->stock_quantity < $quantity) {
            throw new \Exception('Item out of stock or insufficient quantity available');
        }
        
        // Check cart size limit
        $currentCartSize = CartItem::where('user_id', $userId)->count();
        if ($currentCartSize >= self::MAX_CART_ITEMS) {
            throw new \Exception('Cart limit reached. Maximum ' . self::MAX_CART_ITEMS . ' items allowed.');
        }

        DB::beginTransaction();
        
        try {
            // Check if item already in cart
            $cartItem = CartItem::where('user_id', $userId)
                ->where('item_id', $itemId)
                ->where('item_type', $normalizedItemType)
                ->lockForUpdate()
                ->first();
            
            if ($cartItem) {
                $newQuantity = $cartItem->quantity + $quantity;
                
                if ($newQuantity > self::MAX_QUANTITY_PER_ITEM) {
                    throw new \Exception('Maximum quantity per item is ' . self::MAX_QUANTITY_PER_ITEM);
                }

                if ($newQuantity > $item->stock_quantity) {
                    throw new \Exception("Only {$item->stock_quantity} item(s) available in stock");
                }
                
                // Update price to current price (price may have changed)
                $currentPrice = $item->sale_price ?? $item->price;
                $cartItem->update([
                    'quantity' => $newQuantity,
                    'price' => $currentPrice,
                    'updated_at' => now()
                ]);
            } else {
                $currentPrice = $item->sale_price ?? $item->price;
                $cartItem = CartItem::create([
                    'user_id' => $userId,
                    'item_id' => $itemId,
                    'item_type' => $normalizedItemType,
                    'quantity' => $quantity,
                    'price' => $currentPrice
                ]);
            }

            // Clean expired cart items
            $this->cleanExpiredCartItems($userId);
            
            DB::commit();
            
            // Clear cache
            Cache::forget("cart_user_{$userId}");
        
            return $this->getCart($userId);
            
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Get cart with items and totals
     *
     * @param int $userId
     * @return array
     */
    public function getCart(int $userId): array
    {
        return Cache::remember("cart_user_{$userId}", 300, function() use ($userId) {
            $items = CartItem::where('user_id', $userId)
                ->where('created_at', '>=', now()->subDays(self::CART_EXPIRY_DAYS))
                ->with(['item.category'])
                ->get();
            
            $subtotal = 0;
            $validItems = [];

            foreach ($items as $cartItem) {
                $item = $cartItem->item;
                
                if (!$item) {
                    // Item was deleted, remove from cart
                    $cartItem->delete();
                    continue;
                }

                // Check if item is still active and in stock
                if (!$item->is_active || !$item->in_stock) {
                    continue;
                }

                // Get current price (use sale price if available)
                $currentPrice = $item->sale_price ?? $item->price;
                
                // Update cart item price if it changed (always update to ensure latest price)
                if (abs($cartItem->price - $currentPrice) > 0.01) {
                    $cartItem->update(['price' => $currentPrice]);
                    // Refresh cart item to get updated price
                    $cartItem->refresh();
                }

                // Check stock availability
                $maxQuantity = min($cartItem->quantity, $item->stock_quantity);
                if ($maxQuantity < $cartItem->quantity && $maxQuantity > 0) {
                    $cartItem->update(['quantity' => $maxQuantity]);
                }

                $itemSubtotal = $cartItem->quantity * $cartItem->price;
                $subtotal += $itemSubtotal;

                $validItems[] = [
                    'id' => $cartItem->id,
                    'item_id' => $cartItem->item_id,
                    'item_type' => $cartItem->item_type,
                    'quantity' => $cartItem->quantity,
                    'price' => (float) $cartItem->price,
                    'subtotal' => (float) $itemSubtotal,
                    'item' => [
                        'id' => $item->id,
                        'name' => $item->name,
                        'slug' => $item->slug,
                        'price' => (float) $item->price,
                        'sale_price' => $item->sale_price ? (float) $item->sale_price : null,
                        'final_price' => (float) $currentPrice,
                        'stock_quantity' => $item->stock_quantity,
                        'in_stock' => $item->in_stock,
                        'image' => $item->images[0] ?? null,
                        'category' => $item->category ? [
                            'id' => $item->category->id,
                            'name' => $item->category->name,
                            'slug' => $item->category->slug,
                        ] : null,
                    ],
                    'created_at' => $cartItem->created_at->toIso8601String(),
                ];
            }
            
            $tax = round($subtotal * self::TAX_RATE, 2);
            $shipping = $subtotal >= self::FREE_SHIPPING_THRESHOLD ? 0 : self::SHIPPING_COST;
            $total = round($subtotal + $tax + $shipping, 2);
            
            return [
                'items' => $validItems,
                'subtotal' => round($subtotal, 2),
                'tax' => round($tax, 2),
                'shipping' => $shipping,
                'total' => round($total, 2),
                'item_count' => count($validItems),
                'total_quantity' => array_sum(array_column($validItems, 'quantity')),
            ];
        });
    }

    /**
     * Update cart item quantity
     *
     * @param int $userId
     * @param int $cartItemId
     * @param int $quantity
     * @return array
     * @throws \Exception
     */
    public function updateQuantity(int $userId, int $cartItemId, int $quantity): array
    {
        if ($quantity < 1 || $quantity > self::MAX_QUANTITY_PER_ITEM) {
            throw new \InvalidArgumentException(
                "Quantity must be between 1 and " . self::MAX_QUANTITY_PER_ITEM
            );
        }

        $cartItem = CartItem::where('user_id', $userId)
            ->with('item')
            ->find($cartItemId);

        if (!$cartItem) {
            throw new \Exception('Cart item not found');
        }

        $item = $cartItem->item;
        if (!$item || !$item->is_active) {
            throw new \Exception('Item is no longer available');
        }

        if ($quantity > $item->stock_quantity) {
            throw new \Exception("Only {$item->stock_quantity} item(s) available in stock");
        }

        // Update price if it changed
        $currentPrice = $item->sale_price ?? $item->price;
        
        $cartItem->update([
            'quantity' => $quantity,
            'price' => $currentPrice,
        ]);

        // Clear cache
        Cache::forget("cart_user_{$userId}");

        return $this->getCart($userId);
    }

    /**
     * Remove item from cart
     *
     * @param int $userId
     * @param int $cartItemId
     * @return array
     * @throws \Exception
     */
    public function removeItem(int $userId, int $cartItemId): array
    {
        $cartItem = CartItem::where('user_id', $userId)->find($cartItemId);

        if (!$cartItem) {
            throw new \Exception('Cart item not found');
        }

        $cartItem->delete();

        // Clear cache
        Cache::forget("cart_user_{$userId}");

        return $this->getCart($userId);
    }

    /**
     * Clear entire cart
     *
     * @param int $userId
     * @return void
     */
    public function clearCart(int $userId): void
    {
        CartItem::where('user_id', $userId)->delete();
        Cache::forget("cart_user_{$userId}");
    }

    /**
     * Get cart count
     *
     * @param int $userId
     * @return array
     */
    public function getCartCount(int $userId): array
    {
        $totalQuantity = CartItem::where('user_id', $userId)->sum('quantity');
        $itemCount = CartItem::where('user_id', $userId)->count();

        return [
            'total_quantity' => $totalQuantity,
            'unique_items' => $itemCount,
        ];
    }

    /**
     * Clean expired cart items
     *
     * @param int $userId
     * @return void
     */
    private function cleanExpiredCartItems(int $userId): void
    {
        CartItem::where('user_id', $userId)
            ->where('created_at', '<', now()->subDays(self::CART_EXPIRY_DAYS))
            ->delete();
    }

    /**
     * Get or create current cart (for test compatibility)
     * 
     * @return \App\Models\Cart
     */
    public function getOrCreateCurrentCart()
    {
        $userId = \Illuminate\Support\Facades\Auth::check() ? \Illuminate\Support\Facades\Auth::id() : null;
        $sessionId = session()->getId();

        if ($userId) {
            $cart = \App\Models\Cart::firstOrCreate(
                ['user_id' => $userId],
                ['session_id' => $sessionId, 'subtotal' => 0, 'total' => 0]
            );
        } else {
            $cart = \App\Models\Cart::firstOrCreate(
                ['session_id' => $sessionId],
                ['subtotal' => 0, 'total' => 0]
            );
        }

        return $cart;
    }


    /**
     * Get item by type and ID
     *
     * @param int $itemId
     * @param string $itemType
     * @return Product|Plant
     * @throws \Exception
     */
    private function getItem(int $itemId, string $itemType)
    {
        try {
            return match($itemType) {
                Product::class => Product::find($itemId),
                Plant::class => Plant::find($itemId),
                default => throw new \InvalidArgumentException('Invalid item type')
            };
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Normalize item type string to class name
     *
     * @param string $itemType
     * @return string
     */
    private function normalizeItemType(string $itemType): string
    {
        return match($itemType) {
            'product' => Product::class,
            'plant' => Plant::class,
            Product::class => Product::class,
            Plant::class => Plant::class,
            default => throw new \InvalidArgumentException('Invalid item type: ' . $itemType)
        };
    }
}

