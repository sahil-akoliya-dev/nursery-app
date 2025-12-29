<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PlantCareController extends Controller
{
    use ApiResponse;

    /**
     * Get plant finder results based on quiz answers
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function plantFinderResults(Request $request): JsonResponse
    {
        $request->validate([
            'light_level' => 'required|string',
            'watering_frequency' => 'required|string',
            'pet_friendly' => 'required|boolean',
        ]);

        $query = Product::active()->inStock();

        // Filter by Light Level (mapped to plant characteristics)
        // This is a simplified logic for the demo
        if ($request->light_level === 'low') {
            $query->where('description', 'like', '%low light%');
        } elseif ($request->light_level === 'bright') {
            $query->where('description', 'like', '%bright%');
        }

        // Filter by Watering
        if ($request->watering_frequency === 'low') {
            $query->where('description', 'like', '%drought%');
        }

        // Filter by Pet Friendly
        if ($request->pet_friendly) {
            $query->where('description', 'like', '%pet friendly%')
                ->orWhere('description', 'like', '%non-toxic%');
        }

        // Get top 3 matches
        $products = $query->limit(3)->get();

        // If no matches found, return popular plants
        if ($products->isEmpty()) {
            $products = Product::active()->inStock()->featured()->limit(3)->get();
        }

        return $this->successResponse([
            'matches' => $products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'price' => $product->price,
                    'image' => $product->formatted_images[0] ?? null,
                    'match_score' => 95, // Simulated score
                    'reason' => 'Matches your light and watering preferences perfectly.'
                ];
            })
        ], 'Here are your perfect plant matches!');
    }
}
