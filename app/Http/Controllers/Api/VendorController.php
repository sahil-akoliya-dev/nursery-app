<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\NewVendorApplication;
use App\Models\User;

class VendorController extends Controller
{
    use ApiResponse;

    /**
     * Register as a vendor
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        $user = $request->user();

        // Check if user is already a vendor
        if ($user->vendor) {
            return $this->badRequestResponse('You are already registered as a vendor.');
        }

        $request->validate([
            'store_name' => 'required|string|max:255|unique:vendors,store_name',
            'description' => 'nullable|string|max:1000',
        ]);

        // Generate slug
        $slug = Str::slug($request->store_name);

        // Ensure slug is unique
        if (Vendor::where('store_slug', $slug)->exists()) {
            $slug = $slug . '-' . Str::random(4);
        }

        try {
            $vendor = Vendor::create([
                'user_id' => $user->id,
                'store_name' => $request->store_name,
                'store_slug' => $slug,
                'description' => $request->description,
                'status' => 'pending', // Requires admin approval
                'commission_rate' => 10.00, // Default 10%
            ]);

            // Notify Admins
            try {
                // Find admins (e.g., users with 'admin' or 'super_admin' role)
                // This query depends on your role setup.
                // Assuming 'admin' role receives notifications.
                $admins = User::role('admin')->get();
                foreach ($admins as $admin) {
                    Mail::to($admin->email)->send(new NewVendorApplication($vendor));
                }
            } catch (\Exception $e) {
                Log::error('Failed to send new vendor notification: ' . $e->getMessage());
            }

            // Assign vendor role
            return response()->json([
                'message' => 'Vendor registration submitted. Awaiting admin approval.',
                'vendor' => $vendor,
                'status' => 'pending'
            ], 201);
        } catch (\Exception $e) {
            return $this->serverErrorResponse($e->getMessage());
        }
    }

    /**
     * Get vendor profile
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function profile(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!$user->vendor) {
            return $this->notFoundResponse('Vendor Profile', 'You are not registered as a vendor.');
        }

        return $this->successResponse($user->vendor);
    }

    /**
     * Update vendor profile
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        $user = $request->user();
        $vendor = $user->vendor;

        if (!$vendor) {
            return $this->notFoundResponse('Vendor Profile', 'You are not registered as a vendor.');
        }

        $request->validate([
            'store_name' => 'sometimes|string|max:255|unique:vendors,store_name,' . $vendor->id,
            'description' => 'nullable|string|max:1000',
            'logo_path' => 'nullable|string',
            'banner_path' => 'nullable|string',
        ]);

        if ($request->has('store_name')) {
            $vendor->store_name = $request->store_name;
            $vendor->store_slug = Str::slug($request->store_name);
        }

        if ($request->has('description')) {
            $vendor->description = $request->description;
        }

        if ($request->has('logo_path')) {
            $vendor->logo_path = $request->logo_path;
        }

        if ($request->has('banner_path')) {
            $vendor->banner_path = $request->banner_path;
        }

        $vendor->save();

        return $this->successResponse($vendor, 'Vendor profile updated successfully.');
    }
}
