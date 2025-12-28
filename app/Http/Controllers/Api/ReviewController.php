<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    use ApiResponse;

    /**
     * Get reviews for a product
     *
     * @param int $productId
     * @return JsonResponse
     */
    public function index(int $productId): JsonResponse
    {
        $reviews = Review::where('reviewable_type', 'App\\Models\\Product')
            ->where('reviewable_id', $productId)
            ->where('is_approved', true)
            ->with('user:id,name')
            ->latest()
            ->paginate(10);

        return $this->successResponse($reviews);
    }

    /**
     * Submit a review
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000'
        ]);

        $user = $request->user();
        $productId = $request->product_id;

        // SECURITY: Verify user has purchased and received this product
        $hasPurchased = Order::where('user_id', $user->id)
            ->whereHas('items', function ($query) use ($productId) {
                $query->where('product_id', $productId);
            })
            ->where('status', 'delivered') // Only delivered orders
            ->exists();

        if (!$hasPurchased) {
            return $this->forbiddenResponse(
                'You can only review products you have purchased and received.',
                'PURCHASE_REQUIRED'
            );
        }

        // Check if user has already reviewed this product
        $exists = Review::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->exists();

        if ($exists) {
            return $this->badRequestResponse(
                'You have already reviewed this product.',
                'DUPLICATE_REVIEW'
            );
        }

        // Create review (auto-approve for now, or set to false if moderation needed)
        $review = Review::create([
            'user_id' => $user->id,
            'product_id' => $productId,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_approved' => true // Auto-approve for demo
        ]);

        return $this->successResponse($review, 'Review submitted successfully.');
    }
}
