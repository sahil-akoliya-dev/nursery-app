<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VendorProductController extends Controller
{
    use ApiResponse;

    /**
     * List vendor's products
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

        $products = Product::where('vendor_id', $user->vendor->id)
            ->paginate(20);

        return $this->successResponse($products);
    }

    /**
     * Create a new product
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!$user->vendor) {
            return $this->forbiddenResponse('You are not a registered vendor.');
        }

        if ($user->vendor->status !== 'approved') {
            return $this->forbiddenResponse('Your vendor account is pending approval.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'images' => 'nullable|array',
        ]);

        try {
            $product = Product::create([
                'vendor_id' => $user->vendor->id,
                'name' => $request->name,
                'slug' => Str::slug($request->name) . '-' . Str::random(6),
                'description' => $request->description,
                'price' => $request->price,
                'stock_quantity' => $request->stock_quantity,
                'in_stock' => $request->stock_quantity > 0,
                'category_id' => $request->category_id,
                'images' => $request->images ?? [],
                'is_active' => true,
            ]);

            return $this->successResponse($product, 'Product created successfully.', 201);
        } catch (\Exception $e) {
            return $this->serverErrorResponse($e->getMessage());
        }
    }

    /**
     * Update a product
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $user = $request->user();

        if (!$user->vendor) {
            return $this->forbiddenResponse('You are not a registered vendor.');
        }

        $product = Product::where('vendor_id', $user->vendor->id)->find($id);

        if (!$product) {
            return $this->notFoundResponse('Product', 'Product not found or access denied.');
        }

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'price' => 'sometimes|numeric|min:0',
            'stock_quantity' => 'sometimes|integer|min:0',
            'category_id' => 'sometimes|exists:categories,id',
            'images' => 'nullable|array',
        ]);

        $product->update($request->all());

        if ($request->has('stock_quantity')) {
            $product->in_stock = $request->stock_quantity > 0;
            $product->save();
        }

        return $this->successResponse($product, 'Product updated successfully.');
    }

    /**
     * Delete a product
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $user = $request->user();

        if (!$user->vendor) {
            return $this->forbiddenResponse('You are not a registered vendor.');
        }

        $product = Product::where('vendor_id', $user->vendor->id)->find($id);

        if (!$product) {
            return $this->notFoundResponse('Product', 'Product not found or access denied.');
        }

        $product->delete();

        return $this->successResponse(null, 'Product deleted successfully.');
    }
}
