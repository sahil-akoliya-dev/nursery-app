<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'cart_total' => 'required|numeric|min:0'
        ]);

        $voucher = Voucher::where('code', $request->code)->first();

        if (!$voucher) {
            return response()->json(['message' => 'Invalid voucher code'], 404);
        }

        if (!$voucher->isValid()) {
            return response()->json(['message' => 'Voucher is expired or inactive'], 400);
        }

        if ($request->cart_total < $voucher->min_purchase) {
            return response()->json([
                'message' => "Minimum purchase of \${$voucher->min_purchase} required"
            ], 400);
        }

        $discount = 0;
        if ($voucher->type === 'fixed') {
            $discount = min($voucher->value, $request->cart_total);
        } else {
            $discount = $request->cart_total * ($voucher->value / 100);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'code' => $voucher->code,
                'type' => 'voucher',
                'discount_amount' => round($discount, 2),
                'description' => $voucher->type === 'fixed'
                    ? "\${$voucher->value} off"
                    : "{$voucher->value}% off"
            ]
        ]);
    }
}
