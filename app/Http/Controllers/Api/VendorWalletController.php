<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VendorTransaction;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VendorWalletController extends Controller
{
    use ApiResponse;

    /**
     * Get vendor wallet balance and transaction history
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

        $vendorId = $user->vendor->id;

        // Calculate balance
        $balance = VendorTransaction::where('vendor_id', $vendorId)
            ->where('status', 'completed') // Only count completed transactions
            ->sum('amount');

        // Get recent transactions
        $transactions = VendorTransaction::where('vendor_id', $vendorId)
            ->latest()
            ->paginate(20);

        return $this->successResponse([
            'balance' => $balance,
            'transactions' => $transactions
        ]);
    }

    /**
     * Request a payout (Placeholder)
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function requestPayout(Request $request): JsonResponse
    {
        // In a real app, this would create a payout request record
        // For now, we'll just return a success message
        return $this->successResponse(null, 'Payout request submitted successfully.');
    }
}
