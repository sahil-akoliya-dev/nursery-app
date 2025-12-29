<?php

namespace App\Http\Controllers\Api;

use App\Traits\ApiResponse;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use ApiResponse;
    /**
     * Get list of categories
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $query = Category::where('is_active', true);

        // Get only parent categories or include subcategories
        if ($request->filled('parent_only') && $request->boolean('parent_only')) {
            $query->whereNull('parent_id');
        }

        // Get subcategories of a specific parent
        if ($request->filled('parent_id')) {
            $query->where('parent_id', $request->parent_id);
        }

        $categories = $query->withCount(['products', 'plants'])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        // Organize into hierarchical structure if needed
        $categoriesData = $categories->map(function ($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'description' => $category->description,
                'image' => $category->image,
                'parent_id' => $category->parent_id,
                'sort_order' => $category->sort_order,
                'product_count' => $category->products_count + $category->plants_count,
                'created_at' => $category->created_at->toIso8601String(),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $categoriesData,
        ]);
    }

    /**
     * Get single category with products and subcategories
     *
     * @param string $idOrSlug
     * @return JsonResponse
     */
    public function show(string $idOrSlug): JsonResponse
    {
        $category = Category::where('is_active', true)
            ->where(function ($query) use ($idOrSlug) {
                $query->where('id', $idOrSlug)
                      ->orWhere('slug', $idOrSlug);
            })
            ->with(['products' => function ($query) {
                $query->where('is_active', true)
                      ->limit(20)
                      ->orderBy('created_at', 'desc');
            }])
            ->first();

        if (!$category) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'CATEGORY_NOT_FOUND',
                    'message' => 'Category not found or inactive.',
                ]
            ], 404);
        }

        // Get subcategories
        $subcategories = Category::where('parent_id', $category->id)
            ->where('is_active', true)
            ->withCount(['products', 'plants'])
            ->orderBy('sort_order')
            ->get();

        // Get parent category if this is a subcategory
        $parentCategory = null;
        if ($category->parent_id) {
            $parentCategory = Category::find($category->parent_id);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'description' => $category->description,
                'image' => $category->image,
                'parent_id' => $category->parent_id,
                'parent' => $parentCategory ? [
                    'id' => $parentCategory->id,
                    'name' => $parentCategory->name,
                    'slug' => $parentCategory->slug,
                ] : null,
                'subcategories' => $subcategories->map(function ($sub) {
                    return [
                        'id' => $sub->id,
                        'name' => $sub->name,
                        'slug' => $sub->slug,
                        'description' => $sub->description,
                        'product_count' => $sub->products_count + $sub->plants_count,
                    ];
                }),
                'products' => $category->products->map(function ($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'slug' => $product->slug,
                        'price' => $product->price,
                        'sale_price' => $product->sale_price,
                        'final_price' => $product->sale_price ?? $product->price,
                        'image' => $product->images[0] ?? null,
                        'in_stock' => $product->in_stock,
                    ];
                }),
                'product_count' => $category->products->count(),
                'created_at' => $category->created_at->toIso8601String(),
            ],
        ]);
    }

    /**
     * Get category tree (hierarchical structure)
     *
     * @return JsonResponse
     */
    public function tree(): JsonResponse
    {
        $categories = Category::where('is_active', true)
            ->withCount(['products', 'plants'])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        // Build tree structure
        $tree = [];
        $indexed = [];

        // First pass: create index
        foreach ($categories as $category) {
            $indexed[$category->id] = [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'description' => $category->description,
                'image' => $category->image,
                'parent_id' => $category->parent_id,
                'product_count' => $category->products_count + $category->plants_count,
                'children' => [],
            ];
        }

        // Second pass: build tree
        foreach ($indexed as $id => $category) {
            if ($category['parent_id']) {
                if (isset($indexed[$category['parent_id']])) {
                    $indexed[$category['parent_id']]['children'][] = &$indexed[$id];
                }
            } else {
                $tree[] = &$indexed[$id];
            }
        }

        return response()->json([
            'success' => true,
            'data' => $tree,
        ]);
    }
}

