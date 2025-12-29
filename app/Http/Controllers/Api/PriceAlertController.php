<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PriceAlert;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PriceAlertController extends Controller
{
    public function index()
    {
        $alerts = Auth::user()->priceAlerts()
            ->with('product')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $alerts
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'target_price' => 'required|numeric|min:0'
        ]);

        $product = Product::findOrFail($request->product_id);

        // Check if alert already exists for this product
        $existingAlert = PriceAlert::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->where('is_active', true)
            ->first();

        if ($existingAlert) {
            return response()->json([
                'success' => false,
                'message' => 'You already have an active alert for this product.'
            ], 422);
        }

        $alert = PriceAlert::create([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
            'target_price' => $request->target_price,
            'current_price' => $product->price,
            'is_active' => true
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Price alert set successfully!',
            'data' => $alert
        ], 201);
    }

    public function destroy($id)
    {
        $alert = Auth::user()->priceAlerts()->findOrFail($id);
        $alert->delete();

        return response()->json([
            'success' => true,
            'message' => 'Price alert removed.'
        ]);
    }
}
