<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\VendorApproved;
use App\Mail\VendorRejected;

class AdminVendorController extends Controller
{
    use ApiResponse;

    /**
     * List all vendors with optional status filter
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $status = $request->query('status');

        $vendors = Vendor::with('user:id,name,email')
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->latest()
            ->paginate(20);

        return $this->successResponse($vendors);
    }

    /**
     * Approve a vendor
     *
     * @param int $id
     * @return JsonResponse
     */
    public function approve($id)
    {
        $vendor = Vendor::with('user')->findOrFail($id);

        if ($vendor->status === 'approved') {
            return response()->json(['message' => 'Vendor is already approved'], 400);
        }

        $vendor->update([
            'status' => 'approved',
            'approved_at' => now(),
            'rejected_at' => null // Clear rejected timestamp if re-approving
        ]);

        $vendor->user->assignRole('vendor');

        // Send Notification
        try {
            Mail::to($vendor->user->email)->send(new VendorApproved($vendor));
        } catch (\Exception $e) {
            // Log error but don't fail the request
            \Log::error('Failed to send vendor approval email: ' . $e->getMessage());
        }

        return response()->json([
            'message' => 'Vendor approved successfully',
            'vendor' => $vendor
        ]);
    }

    /**
     * Reject a vendor
     *
     * @param int $id
     * @return JsonResponse
     */
    public function reject(Request $request, $id)
    {
        $vendor = Vendor::with('user')->findOrFail($id);

        if ($vendor->status === 'rejected') {
            return response()->json(['message' => 'Vendor is already rejected'], 400);
        }

        $reason = $request->input('reason'); // Optional reason

        $vendor->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'approved_at' => null
        ]);

        if ($vendor->user->hasRole('vendor')) {
            $vendor->user->removeRole('vendor');
        }

        // Send Notification
        try {
            Mail::to($vendor->user->email)->send(new VendorRejected($vendor, $reason));
        } catch (\Exception $e) {
            \Log::error('Failed to send vendor rejection email: ' . $e->getMessage());
        }

        return response()->json([
            'message' => 'Vendor application rejected',
            'vendor' => $vendor
        ]);
    }

    /**
     * Suspend a vendor
     *
     * @param int $id
     * @return JsonResponse
     */
    public function suspend(int $id): JsonResponse
    {
        $vendor = Vendor::find($id);

        if (!$vendor) {
            return $this->notFoundResponse('Vendor');
        }

        if ($vendor->status !== 'approved') {
            return $this->badRequestResponse('Only approved vendors can be suspended.');
        }

        $vendor->update(['status' => 'suspended']);

        return $this->successResponse($vendor, 'Vendor suspended successfully.');
    }

    /**
     * Unsuspend a vendor
     *
     * @param int $id
     * @return JsonResponse
     */
    public function unsuspend(int $id): JsonResponse
    {
        $vendor = Vendor::with('user')->find($id);

        if (!$vendor) {
            return $this->notFoundResponse('Vendor');
        }

        if ($vendor->status !== 'suspended') {
            return $this->badRequestResponse('Vendor is not suspended.');
        }

        $vendor->update(['status' => 'approved']);

        // Ensure role is assigned
        if (!$vendor->user->hasRole('vendor')) {
            $vendor->user->assignRole('vendor');
        }

        return $this->successResponse($vendor, 'Vendor unsuspended successfully.');
    }
}
