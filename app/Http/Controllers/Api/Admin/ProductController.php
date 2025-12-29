<?php

namespace App\Http\Controllers\Api\Admin;

use App\Traits\ApiResponse;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    use ApiResponse;
    /**
     * List all products (admin)
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $query = Product::with('category');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by status
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        // Filter by stock
        if ($request->filled('stock_status')) {
            switch ($request->stock_status) {
                case 'in_stock':
                    $query->where('in_stock', true)->where('stock_quantity', '>', 0);
                    break;
                case 'low_stock':
                    $query->where('stock_quantity', '<', 10)->where('stock_quantity', '>', 0);
                    break;
                case 'out_of_stock':
                    $query->where(function ($q) {
                        $q->where('stock_quantity', '<=', 0)->orWhere('in_stock', false);
                    });
                    break;
            }
        }

        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $perPage = min($request->get('per_page', 20), 50);
        $products = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => collect($products->items())->map(function ($product) {
                return $this->formatProduct($product);
            })->values(),
            'meta' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
            ],
        ]);
    }

    /**
     * Get single product (admin)
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $product = Product::with(['category'])->find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'PRODUCT_NOT_FOUND',
                    'message' => 'Product not found.',
                ]
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $this->formatProduct($product, true),
        ]);
    }

    /**
     * Create new product
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'category_id' => 'required|exists:categories,id',
            'stock_quantity' => 'required|integer|min:0',
            'images' => 'nullable|array',
            'images.*' => 'nullable|string',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        $product = Product::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . time(),
            'description' => $request->description,
            'price' => $request->price,
            'sale_price' => $request->sale_price,
            'category_id' => $request->category_id,
            'stock_quantity' => $request->stock_quantity,
            'in_stock' => $request->stock_quantity > 0,
            'images' => $request->images ?? [],
            'is_active' => $request->boolean('is_active', true),
            'is_featured' => $request->boolean('is_featured', false),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Product created successfully.',
            'data' => $this->formatProduct($product->fresh(['category']), true),
        ], 201);
    }

    /**
     * Update product
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'PRODUCT_NOT_FOUND',
                    'message' => 'Product not found.',
                ]
            ], 404);
        }

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'price' => 'sometimes|required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'category_id' => 'sometimes|required|exists:categories,id',
            'stock_quantity' => 'sometimes|required|integer|min:0',
            'images' => 'nullable|array',
            'images.*' => 'nullable|string',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        $data = [];

        if ($request->filled('name')) {
            $data['name'] = $request->name;
            $data['slug'] = Str::slug($request->name) . '-' . $product->id;
        }

        if ($request->filled('description')) {
            $data['description'] = $request->description;
        }

        if ($request->filled('price')) {
            $data['price'] = $request->price;
        }

        if ($request->has('sale_price')) {
            $data['sale_price'] = $request->sale_price;
        }

        if ($request->filled('category_id')) {
            $data['category_id'] = $request->category_id;
        }

        if ($request->filled('stock_quantity')) {
            $data['stock_quantity'] = $request->stock_quantity;
            $data['in_stock'] = $request->stock_quantity > 0;
        }

        if ($request->has('images')) {
            $data['images'] = $request->images;
        }

        if ($request->has('is_active')) {
            $data['is_active'] = $request->boolean('is_active');
        }

        if ($request->has('is_featured')) {
            $data['is_featured'] = $request->boolean('is_featured');
        }

        $product->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully.',
            'data' => $this->formatProduct($product->fresh(['category']), true),
        ]);
    }

    /**
     * Delete product
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'PRODUCT_NOT_FOUND',
                    'message' => 'Product not found.',
                ]
            ], 404);
        }

        // Check if product has orders
        $hasOrders = \App\Models\OrderItem::where('item_type', Product::class)
            ->where('item_id', $product->id)
            ->exists();

        if ($hasOrders) {
            // Soft delete by deactivating
            $product->update(['is_active' => false]);

            return response()->json([
                'success' => true,
                'message' => 'Product deactivated (cannot delete products with order history).',
                'data' => $this->formatProduct($product->fresh(), true),
            ]);
        }

        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully.',
        ]);
    }

    /**
     * Format product for API response
     *
     * @param Product $product
     * @param bool $detailed
     * @return array
     */
    private function formatProduct(Product $product, bool $detailed = false): array
    {
        $data = [
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'description' => $product->description,
            'price' => (float) $product->price,
            'sale_price' => $product->sale_price ? (float) $product->sale_price : null,
            'stock_quantity' => $product->stock_quantity,
            'in_stock' => $product->in_stock,
            'is_active' => $product->is_active,
            'is_featured' => $product->is_featured,
            'images' => $product->images ?? [],
            'category' => $product->category ? [
                'id' => $product->category->id,
                'name' => $product->category->name,
                'slug' => $product->category->slug,
            ] : null,
            'created_at' => $product->created_at->toIso8601String(),
            'updated_at' => $product->updated_at->toIso8601String(),
        ];

        if ($detailed) {
            // Get review statistics
            $reviews = \App\Models\Review::where('reviewable_type', Product::class)
                ->where('reviewable_id', $product->id)
                ->where('is_approved', true)
                ->get();

            $data['reviews'] = [
                'count' => $reviews->count(),
                'average_rating' => round($reviews->avg('rating') ?? 0, 2),
            ];

            // Get order statistics
            $orderItems = \App\Models\OrderItem::where('item_type', Product::class)
                ->where('item_id', $product->id)
                ->get();

            $data['sales'] = [
                'total_sold' => $orderItems->sum('quantity'),
                'total_revenue' => round($orderItems->sum(function ($item) {
                    return $item->quantity * $item->price;
                }), 2),
            ];
        }

        return $data;
    }
}

