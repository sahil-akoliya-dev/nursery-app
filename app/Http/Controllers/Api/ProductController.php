<?php

namespace App\Http\Controllers\Api;

use App\Traits\ApiResponse;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use ApiResponse;
    /**
     * Get list of products with filtering and pagination
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $query = Product::with('category')->where('is_active', true);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        // Category filter (Recursive)
        if ($request->filled('category')) {
            $categoryIdentifier = $request->category;

            // Find the category first to get its ID
            $category = Category::where('slug', $categoryIdentifier)
                ->orWhere('id', $categoryIdentifier)
                ->first();

            if ($category) {
                // Get this category and all its children
                $categoryIds = Category::where('id', $category->id)
                    ->orWhere('parent_id', $category->id)
                    ->pluck('id');

                $query->whereIn('category_id', $categoryIds);
            }
        }

        // Price filters
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Stock filter
        if ($request->filled('in_stock')) {
            if ($request->boolean('in_stock')) {
                $query->where('in_stock', true)->where('stock_quantity', '>', 0);
            }
        }

        // Featured filter
        if ($request->filled('featured')) {
            $query->where('is_featured', $request->boolean('featured'));
        }

        // Sort options
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');

        switch ($sortBy) {
            case 'price_low':
                $query->orderByRaw('COALESCE(sale_price, price) ASC');
                break;
            case 'price_high':
                $query->orderByRaw('COALESCE(sale_price, price) DESC');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'popular':
                // You can implement popularity based on order items count
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $perPage = min($request->get('per_page', 20), 100); // Max 100 per page
        $products = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $products->items(),
            'meta' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
                'from' => $products->firstItem(),
                'to' => $products->lastItem(),
            ],
            'links' => [
                'first' => $products->url(1),
                'last' => $products->url($products->lastPage()),
                'prev' => $products->previousPageUrl(),
                'next' => $products->nextPageUrl(),
            ],
        ]);
    }

    /**
     * Get single product details
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $product = Product::with('category')
            ->where('is_active', true)
            ->find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'PRODUCT_NOT_FOUND',
                    'message' => 'Product not found or inactive.',
                ]
            ], 404);
        }

        // Get related products from the same category
        $relatedProducts = Product::with('category')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->limit(4)
            ->get();

        // Calculate average rating from reviews
        $reviews = \App\Models\Review::where('reviewable_type', Product::class)
            ->where('reviewable_id', $product->id)
            ->where('is_approved', true)
            ->get();
        $averageRating = $reviews->avg('rating') ?? 0;
        $reviewCount = $reviews->count();

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'description' => $product->description,
                'short_description' => $product->short_description,
                'price' => $product->price,
                'sale_price' => $product->sale_price,
                'final_price' => $product->sale_price ?? $product->price,
                'stock_quantity' => $product->stock_quantity,
                'in_stock' => $product->in_stock,
                'is_featured' => $product->is_featured,
                'sku' => $product->sku,
                'images' => $product->formatted_images,
                'care_instructions' => $product->care_instructions ?? [],
                'plant_characteristics' => $product->plant_characteristics ?? [],
                'category' => [
                    'id' => $product->category->id,
                    'name' => $product->category->name,
                    'slug' => $product->category->slug,
                ],
                'rating' => [
                    'average' => round($averageRating, 1),
                    'count' => $reviewCount,
                    'distribution' => $this->getRatingDistribution($reviews),
                ],
                'related_products' => $relatedProducts->map(function ($related) {
                    return [
                        'id' => $related->id,
                        'name' => $related->name,
                        'slug' => $related->slug,
                        'price' => $related->price,
                        'sale_price' => $related->sale_price,
                        'final_price' => $related->sale_price ?? $related->price,
                        'image' => $related->images[0] ?? null,
                    ];
                }),
                'created_at' => $product->created_at->toIso8601String(),
                'updated_at' => $product->updated_at->toIso8601String(),
            ],
        ]);
    }

    /**
     * Get rating distribution for product
     *
     * @param \Illuminate\Database\Eloquent\Collection $reviews
     * @return array
     */
    private function getRatingDistribution($reviews): array
    {
        $distribution = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];

        foreach ($reviews as $review) {
            $distribution[$review->rating] = ($distribution[$review->rating] ?? 0) + 1;
        }

        $total = $reviews->count();

        return array_map(function ($count) use ($total) {
            return [
                'count' => $count,
                'percentage' => $total > 0 ? round(($count / $total) * 100, 1) : 0,
            ];
        }, $distribution);
    }
}

