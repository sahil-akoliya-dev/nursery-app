<?php

namespace App\Http\Controllers\Api;

use App\Traits\ApiResponse;
use App\Http\Controllers\Controller;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    use ApiResponse;

    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * Get user's cart items
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        try {
            $cart = $this->cartService->getCart($user->id);

            return $this->successResponse([
                'items' => $cart['items'],
                'subtotal' => $cart['subtotal'],
                'tax' => $cart['tax'],
                'shipping' => $cart['shipping'],
                'total' => $cart['total'],
                'item_count' => $cart['item_count'],
                'total_quantity' => $cart['total_quantity'],
            ]);
        } catch (\Exception $e) {
            return $this->serverErrorResponse($e->getMessage());
        }
    }

    /**
     * Add item to cart
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function add(Request $request): JsonResponse
    {
        $request->validate([
            'item_id' => 'required|integer',
            'item_type' => 'required|string|in:product,plant',
            'quantity' => 'required|integer|min:1|max:100',
        ]);

        $user = $request->user();

        try {
            $cart = $this->cartService->addItem(
                $user->id,
                $request->item_id,
                $request->item_type,
                $request->quantity
            );

            $cartCount = $this->cartService->getCartCount($user->id);

            return $this->successResponse([
                'cart' => $cart,
                'cart_count' => $cartCount['total_quantity'],
            ], 'Item added to cart successfully.', 201);
        } catch (\InvalidArgumentException $e) {
            return $this->badRequestResponse($e->getMessage());
        } catch (\Exception $e) {
            return $this->badRequestResponse($e->getMessage());
        }
    }

    /**
     * Update cart item quantity
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:100',
        ]);

        $user = $request->user();

        try {
            $cart = $this->cartService->updateQuantity($user->id, $id, $request->quantity);

            return $this->successResponse($cart, 'Cart item updated successfully.');
        } catch (\Exception $e) {
            return $this->badRequestResponse($e->getMessage());
        }
    }

    /**
     * Remove item from cart
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function remove(Request $request, int $id): JsonResponse
    {
        $user = $request->user();

        try {
            $cart = $this->cartService->removeItem($user->id, $id);

            return $this->successResponse($cart, 'Item removed from cart successfully.');
        } catch (\Exception $e) {
            return $this->notFoundResponse('Cart Item', $e->getMessage());
        }
    }

    /**
     * Clear entire cart
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function clear(Request $request): JsonResponse
    {
        $user = $request->user();

        $this->cartService->clearCart($user->id);

        return $this->successResponse(null, 'Cart cleared successfully.');
    }

    /**
     * Get cart count (quick summary)
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function count(Request $request): JsonResponse
    {
        $user = $request->user();

        $cartCount = $this->cartService->getCartCount($user->id);

        return $this->successResponse($cartCount);
    }
}

