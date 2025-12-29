<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VendorStoreController extends Controller
{
    use ApiResponse;

    /**
     * Get vendor details by slug
     *
     * @param string $slug
     * @return JsonResponse
     */
    public function show(string $slug): JsonResponse
    {
        $vendor = Vendor::where('store_slug', $slug)
            ->where('status', 'approved')
            ->with(['user:id,name'])
            ->first();

        if (!$vendor) {
            return $this->notFoundResponse('Vendor', 'Store not found.');
        }

        return $this->successResponse($vendor);
    }

    /**
     * Get vendor's products
     *
     * @param string $slug
     * @return JsonResponse
     */
    public function products(string $slug): JsonResponse
    {
        $vendor = Vendor::where('store_slug', $slug)
            ->where('status', 'approved')
            ->first();

        if (!$vendor) {
            return $this->notFoundResponse('Vendor', 'Store not found.');
        }

        $products = $vendor->products()
            ->where('is_active', true)
            ->latest()
            ->paginate(12);

        // Append formatted images
        $products->getCollection()->transform(function ($product) {
            $product->append('formatted_images');
            return $product;
        });

        return $this->successResponse($products);
    }
}
