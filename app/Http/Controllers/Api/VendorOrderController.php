<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VendorOrderController extends Controller
{
    use ApiResponse;

    /**
     * List vendor's orders (containing their products)
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!$user->vendor) {
            return $this->forbiddenResponse('You are not a registered vendor.');
        }

        $vendorId = $user->vendor->id;

        // Find orders that have items belonging to this vendor
        $orders = Order::whereHas('orderItems.item', function ($query) use ($vendorId) {
            $query->where('vendor_id', $vendorId);
        })->with([
                    'orderItems' => function ($query) use ($vendorId) {
                        // Only load items belonging to this vendor
                        $query->whereHasMorph('item', [\App\Models\Product::class], function ($q) use ($vendorId) {
                            $q->where('vendor_id', $vendorId);
                        })->with('item');
                    }
                ])->latest()->paginate(20);

        return $this->successResponse($orders);
    }

    /**
     * Show order details (filtered for vendor)
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $user = $request->user();

        if (!$user->vendor) {
            return $this->forbiddenResponse('You are not a registered vendor.');
        }

        $vendorId = $user->vendor->id;

        $order = Order::where('id', $id)
            ->whereHas('orderItems.item', function ($query) use ($vendorId) {
                $query->where('vendor_id', $vendorId);
            })
            ->with([
                'orderItems' => function ($query) use ($vendorId) {
                    $query->whereHasMorph('item', [\App\Models\Product::class], function ($q) use ($vendorId) {
                        $q->where('vendor_id', $vendorId);
                    })->with('item');
                },
                'shipping_address'
            ])
            ->first();

        if (!$order) {
            return $this->notFoundResponse('Order', 'Order not found or access denied.');
        }

        return $this->successResponse($order);
    }

    /**
     * Update order item status
     *
     * @param Request $request
     * @param int $orderId
     * @param int $itemId
     * @return JsonResponse
     */
    public function updateItemStatus(Request $request, int $orderId, int $itemId): JsonResponse
    {
        $user = $request->user();

        if (!$user->vendor) {
            return $this->forbiddenResponse('You are not a registered vendor.');
        }

        $request->validate([
            'status' => 'required|in:pending,shipped,delivered,cancelled',
        ]);

        $vendorId = $user->vendor->id;

        // Find the order item, ensuring it belongs to the order and the vendor
        $orderItem = OrderItem::where('id', $itemId)
            ->where('order_id', $orderId)
            ->whereHasMorph('item', [\App\Models\Product::class], function ($q) use ($vendorId) {
                $q->where('vendor_id', $vendorId);
            })
            ->first();

        if (!$orderItem) {
            return $this->notFoundResponse('Order Item', 'Item not found or access denied.');
        }

        $orderItem->status = $request->status;
        $orderItem->save();

        return $this->successResponse($orderItem, 'Item status updated successfully.');
    }
}
