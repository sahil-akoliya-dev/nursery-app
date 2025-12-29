<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\WishlistItem;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    use ApiResponse;

    /**
     * Get user's wishlist
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        // Polymorphic relation load
        $wishlist = $request->user()->wishlistItems()
            ->with('item')
            ->latest()
            ->paginate(20);

        // Transform for API: map 'item' to 'product' if it matches
        $wishlist->getCollection()->transform(function ($wishlistItem) {
            if ($wishlistItem->item_type === Product::class && $wishlistItem->item) {
                // Determine formatted images on the product model
                $wishlistItem->item->append('formatted_images');
                // Set 'product' relation for frontend compatibility
                $wishlistItem->setRelation('product', $wishlistItem->item);
            }
            return $wishlistItem;
        });

        return $this->successResponse($wishlist);
    }

    /**
     * Toggle item in wishlist (Add/Remove)
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function toggle(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $user = $request->user();
        $productId = $request->product_id;

        // Check for existing item using polymorphic columns
        $existingItem = WishlistItem::where('user_id', $user->id)
            ->where('item_type', Product::class)
            ->where('item_id', $productId)
            ->first();

        if ($existingItem) {
            $existingItem->delete();
            return $this->successResponse(['in_wishlist' => false], 'Removed from wishlist.');
        } else {
            WishlistItem::create([
                'user_id' => $user->id,
                'item_type' => Product::class,
                'item_id' => $productId
            ]);
            return $this->successResponse(['in_wishlist' => true], 'Added to wishlist.');
        }
    }

    /**
     * Check if product is in wishlist
     *
     * @param Request $request
     * @param int $productId
     * @return JsonResponse
     */
    public function check(Request $request, int $productId): JsonResponse
    {
        $exists = WishlistItem::where('user_id', $request->user()->id)
            ->where('item_type', Product::class)
            ->where('item_id', $productId)
            ->exists();

        return $this->successResponse(['in_wishlist' => $exists]);
    }

    /**
     * Remove item from wishlist
     *
     * @param Request $request
     * @param int $productId
     * @return JsonResponse
     */
    public function remove(Request $request, int $productId): JsonResponse
    {
        $deleted = $request->user()->wishlistItems()
            ->where('item_type', Product::class)
            ->where('item_id', $productId)
            ->delete();

        return $this->successResponse(null, 'Removed from wishlist.');
    }

    /**
     * Clear all items from wishlist
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function clear(Request $request): JsonResponse
    {
        $request->user()->wishlistItems()->delete();
        return $this->successResponse(null, 'Wishlist cleared.');
    }
}
