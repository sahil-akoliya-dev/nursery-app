<?php

namespace App\Http\Controllers\Api\Admin;

use App\Traits\ApiResponse;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    use ApiResponse;
    /**
     * List all reviews (pending approval)
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $query = Review::with(['user', 'reviewable']);

        // Filter by approval status
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'pending':
                    $query->where('is_approved', false);
                    break;
                case 'approved':
                    $query->where('is_approved', true);
                    break;
                case 'featured':
                    $query->where('is_featured', true);
                    break;
            }
        }

        // Filter by rating
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $perPage = min($request->get('per_page', 20), 50);
        $reviews = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $reviews->items()->map(function ($review) {
                return $this->formatReview($review);
            }),
            'meta' => [
                'current_page' => $reviews->currentPage(),
                'last_page' => $reviews->lastPage(),
                'per_page' => $reviews->perPage(),
                'total' => $reviews->total(),
            ],
        ]);
    }

    /**
     * Approve review
     *
     * @param int $id
     * @return JsonResponse
     */
    public function approve(int $id): JsonResponse
    {
        $review = Review::find($id);

        if (!$review) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'REVIEW_NOT_FOUND',
                    'message' => 'Review not found.',
                ]
            ], 404);
        }

        $review->update(['is_approved' => true]);

        // Award loyalty points if not already awarded
        if (!$review->loyaltyPoints()->exists()) {
            \App\Http\Controllers\LoyaltyController::awardReviewPoints($review->user_id, $review->id);
        }

        return response()->json([
            'success' => true,
            'message' => 'Review approved successfully.',
            'data' => $this->formatReview($review->fresh(['user', 'reviewable'])),
        ]);
    }

    /**
     * Reject/Delete review
     *
     * @param int $id
     * @return JsonResponse
     */
    public function reject(int $id): JsonResponse
    {
        $review = Review::find($id);

        if (!$review) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'REVIEW_NOT_FOUND',
                    'message' => 'Review not found.',
                ]
            ], 404);
        }

        // Delete associated images
        if ($review->images) {
            foreach ($review->images as $image) {
                $path = str_replace('/storage/', '', parse_url($image, PHP_URL_PATH));
                \Illuminate\Support\Facades\Storage::disk('public')->delete($path);
            }
        }

        $review->delete();

        return response()->json([
            'success' => true,
            'message' => 'Review rejected and deleted.',
        ]);
    }

    /**
     * Feature review
     *
     * @param int $id
     * @return JsonResponse
     */
    public function feature(int $id): JsonResponse
    {
        $review = Review::where('is_approved', true)->find($id);

        if (!$review) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'REVIEW_NOT_FOUND',
                    'message' => 'Review not found or not approved.',
                ]
            ], 404);
        }

        $review->update(['is_featured' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Review featured successfully.',
            'data' => $this->formatReview($review->fresh(['user', 'reviewable'])),
        ]);
    }

    /**
     * Unfeature review
     *
     * @param int $id
     * @return JsonResponse
     */
    public function unfeature(int $id): JsonResponse
    {
        $review = Review::find($id);

        if (!$review) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'REVIEW_NOT_FOUND',
                    'message' => 'Review not found.',
                ]
            ], 404);
        }

        $review->update(['is_featured' => false]);

        return response()->json([
            'success' => true,
            'message' => 'Review unfeatured successfully.',
            'data' => $this->formatReview($review->fresh(['user', 'reviewable'])),
        ]);
    }

    /**
     * Format review for API response
     *
     * @param Review $review
     * @return array
     */
    private function formatReview(Review $review): array
    {
        return [
            'id' => $review->id,
            'rating' => $review->rating,
            'title' => $review->title,
            'content' => $review->content,
            'images' => $review->images ?? [],
            'is_verified_purchase' => $review->is_verified_purchase,
            'is_approved' => $review->is_approved,
            'is_featured' => $review->is_featured,
            'helpful_count' => $review->helpful_count,
            'not_helpful_count' => $review->not_helpful_count,
            'user' => $review->user ? [
                'id' => $review->user->id,
                'name' => $review->user->name,
                'email' => $review->user->email,
            ] : null,
            'reviewable' => $review->reviewable ? [
                'id' => $review->reviewable->id,
                'name' => $review->reviewable->name ?? $review->reviewable->title,
                'type' => class_basename($review->reviewable_type),
            ] : null,
            'created_at' => $review->created_at->toIso8601String(),
            'updated_at' => $review->updated_at->toIso8601String(),
        ];
    }
}

