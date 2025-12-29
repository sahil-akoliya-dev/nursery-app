<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GiftCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class GiftCardController extends Controller
{
    public function index()
    {
        $cards = GiftCard::where('user_id', Auth::id())
            ->where('current_balance', '>', 0)
            ->where('is_active', true)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $cards
        ]);
    }

    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'cart_total' => 'required|numeric|min:0'
        ]);

        $card = GiftCard::where('code', $request->code)->first();

        if (!$card) {
            return response()->json(['message' => 'Invalid gift card code'], 404);
        }

        if (!$card->isValid()) {
            return response()->json(['message' => 'Gift card is expired or empty'], 400);
        }

        // Check if user is authorized to use it (optional: only owner can use?)
        // For now, anyone with code can use it, like a real gift card

        $maxDiscount = min($card->current_balance, $request->cart_total);

        return response()->json([
            'success' => true,
            'data' => [
                'code' => $card->code,
                'type' => 'gift_card',
                'discount_amount' => round($maxDiscount, 2),
                'balance_remaining' => $card->current_balance - $maxDiscount,
                'description' => "Gift Card Balance: \${$card->current_balance}"
            ]
        ]);
    }

    public function purchase(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10|max:500',
            'recipient_email' => 'nullable|email'
        ]);

        // In a real app, verify payment here first

        $code = strtoupper(Str::random(12));

        $card = GiftCard::create([
            'code' => $code,
            'initial_value' => $request->amount,
            'current_balance' => $request->amount,
            'user_id' => Auth::id(), // Purchaser owns it initially
            'expires_at' => now()->addYear(),
            'is_active' => true
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Gift card purchased successfully!',
            'data' => $card
        ], 201);
    }
}
