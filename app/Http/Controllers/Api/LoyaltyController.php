<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoyaltyController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $transactions = $user->pointTransactions()
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'points_balance' => $user->points,
                'transactions' => $transactions
            ]
        ]);
    }

    public function redeem(Request $request)
    {
        $request->validate([
            'points' => 'required|integer|min:100',
            'cart_total' => 'required|numeric|min:0'
        ]);

        $user = Auth::user();
        $pointsToRedeem = $request->points;

        if ($user->points < $pointsToRedeem) {
            return response()->json(['message' => 'Insufficient points'], 400);
        }

        // Redemption Rate: 100 points = $1
        $discountAmount = $pointsToRedeem / 100;

        // Cannot redeem more than cart total
        if ($discountAmount > $request->cart_total) {
            return response()->json(['message' => 'Cannot redeem more than cart total'], 400);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'points_redeemed' => $pointsToRedeem,
                'discount_amount' => $discountAmount,
                'description' => "Redeemed {$pointsToRedeem} points"
            ]
        ]);
    }
}
