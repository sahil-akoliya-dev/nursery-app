<?php

namespace App\Http\Controllers\Api;

use App\Traits\ApiResponse;
use App\Http\Controllers\Controller;
use App\Services\OrderService;
use App\Http\Requests\CreateOrderRequest;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use ApiResponse;

    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }
    /**
     * Get user's orders
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $query = Order::where('user_id', $user->id);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by payment status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Sort
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $perPage = min($request->get('per_page', 15), 50);
        $orders = $query->paginate($perPage);

        return $this->successResponse(collect($orders->items())->map(function ($order) {
            return $this->formatOrder($order);
        }), null, 200);
    }

    /**
     * Get all orders (Admin only)
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function adminIndex(Request $request): JsonResponse
    {
        $query = Order::with(['user']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Sort
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $perPage = min($request->get('per_page', 15), 50);
        $orders = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => collect($orders->items())->map(function ($order) {
                $formatted = $this->formatOrder($order);
                $formatted['user'] = $order->user ? [
                    'id' => $order->user->id,
                    'name' => $order->user->name,
                    'email' => $order->user->email,
                ] : null;
                return $formatted;
            }),
            'meta' => [
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'per_page' => $orders->perPage(),
                'total' => $orders->total(),
            ]
        ], 200);
    }

    /**
     * Get single order details
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $user = $request->user();

        $order = Order::with(['orderItems.item.category'])
            ->where('user_id', $user->id)
            ->find($id);

        if (!$order) {
            return $this->notFoundResponse('Order', 'The order you are looking for was not found.');
        }

        return response()->json([
            'success' => true,
            'data' => $this->formatOrderDetails($order),
        ]);
    }

    /**
     * Create order from cart
     *
     * @param CreateOrderRequest $request
     * @return JsonResponse
     */
    public function store(CreateOrderRequest $request): JsonResponse
    {
        $user = $request->user();

        try {
            $order = $this->orderService->createOrder($user->id, $request->all());

            // Award Loyalty Points (1 point per $1)
            $pointsEarned = (int) $order->total_amount;
            $user->addPoints($pointsEarned, 'purchase', "Earned from Order #{$order->order_number}", $order->id);

            return $this->successResponse([
                'message' => 'Order created successfully.',
                'order' => $this->formatOrderDetails($order),
                'points_earned' => $pointsEarned
            ], 'Order created successfully.', 201);
        } catch (\Exception $e) {
            return $this->badRequestResponse($e->getMessage());
        }
    }

    /**
     * Cancel order
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function cancel(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        $user = $request->user();

        try {
            $this->orderService->cancelOrder($id, $user->id, $request->reason);

            $order = Order::with(['orderItems.item'])->find($id);

            return response()->json([
                'success' => true,
                'message' => 'Order cancelled successfully.',
                'data' => $this->formatOrderDetails($order),
            ]);
        } catch (\Exception $e) {
            return $this->badRequestResponse($e->getMessage());
        }
    }

    /**
     * Update order status (Admin only)
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function updateStatus(Request $request, int $id): JsonResponse
    {
        $user = $request->user();

        if (!$user->can('orders.update')) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'FORBIDDEN',
                    'message' => 'Insufficient permissions to update orders.',
                ]
            ], 403);
        }

        $request->validate([
            'status' => 'required|string|in:pending,processing,shipped,delivered,cancelled',
            'payment_status' => 'nullable|string|in:pending,paid,failed,refunded',
        ]);

        $order = Order::find($id);

        if (!$order) {
            return $this->notFoundResponse('Order', 'The order you are looking for was not found.');
        }

        $updateData = ['status' => $request->status];

        if ($request->filled('payment_status')) {
            $updateData['payment_status'] = $request->payment_status;
        }

        $order->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Order status updated successfully.',
            'data' => $this->formatOrderDetails($order->fresh()),
        ]);
    }

    /**
     * Format order for list view
     *
     * @param Order $order
     * @return array
     */
    private function formatOrder(Order $order): array
    {
        return [
            'id' => $order->id,
            'order_number' => $order->order_number,
            'status' => $order->status,
            'payment_status' => $order->payment_status,
            'payment_method' => $order->payment_method,
            'total_amount' => (float) $order->total_amount,
            'item_count' => $order->orderItems()->sum('quantity'),
            'created_at' => $order->created_at->toIso8601String(),
            'updated_at' => $order->updated_at->toIso8601String(),
        ];
    }

    /**
     * Format order details
     *
     * @param Order $order
     * @return array
     */
    private function formatOrderDetails(Order $order): array
    {
        $order->load('orderItems.item.category');

        return [
            'id' => $order->id,
            'order_number' => $order->order_number,
            'status' => $order->status,
            'payment_status' => $order->payment_status,
            'payment_method' => $order->payment_method,
            'payment_transaction_id' => $order->payment_transaction_id,
            'summary' => [
                'subtotal' => (float) $order->subtotal,
                'tax' => (float) $order->tax_amount,
                'shipping' => (float) $order->shipping_amount,
                'total' => (float) $order->total_amount,
            ],
            'items' => $order->orderItems->map(function ($orderItem) {
                $item = $orderItem->item;
                return [
                    'id' => $orderItem->id,
                    'item_id' => $orderItem->item_id,
                    'item_type' => $orderItem->item_type,
                    'quantity' => $orderItem->quantity,
                    'price' => (float) $orderItem->price,
                    'subtotal' => (float) ($orderItem->quantity * $orderItem->price),
                    'item' => $item ? [
                        'id' => $item->id,
                        'name' => $item->name,
                        'slug' => $item->slug,
                        'image' => $item->images[0] ?? null,
                        'category' => $item->category ? [
                            'id' => $item->category->id,
                            'name' => $item->category->name,
                            'slug' => $item->category->slug,
                        ] : null,
                    ] : null,
                ];
            }),
            'shipping_address' => $order->shipping_address,
            'billing_address' => $order->billing_address,
            'notes' => $order->notes,
            'cancelled_at' => $order->cancelled_at?->toIso8601String(),
            'cancellation_reason' => $order->cancellation_reason,
            'created_at' => $order->created_at->toIso8601String(),
            'updated_at' => $order->updated_at->toIso8601String(),
            'can_cancel' => $this->canCancel($order),
        ];
    }

    /**
     * Check if order can be cancelled
     *
     * @param Order $order
     * @return bool
     */
    private function canCancel(Order $order): bool
    {
        if (!in_array($order->status, ['pending', 'processing'])) {
            return false;
        }

        $hoursSinceOrder = now()->diffInHours($order->created_at);
        return $hoursSinceOrder <= 24;
    }
}

