<?php

namespace App\Http\Controllers\Api;

use App\Traits\ApiResponse;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    use ApiResponse;
    /**
     * Get user profile
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function show(Request $request): JsonResponse
    {
        $user = $request->user()->load('addresses');

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'role' => $user->role,
                'roles' => $user->getRoleNames(),
                'two_factor_enabled' => $user->hasTwoFactorEnabled(),
                'created_at' => $user->created_at->toIso8601String(),
                'updated_at' => $user->updated_at->toIso8601String(),
                'addresses' => $user->addresses ? $user->addresses->map(function ($address) {
                    return $this->formatAddress($address);
                })->values() : [],
            ],
        ]);
    }

    /**
     * Update user profile
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        $user = $request->user();

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => [
                'sometimes',
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'phone' => 'sometimes|nullable|string|max:20',
            'date_of_birth' => 'sometimes|nullable|date',
            'address' => 'sometimes|nullable|string|max:1000',
        ]);

        $data = [];

        if ($request->filled('name')) {
            $data['name'] = $request->name;
        }

        if ($request->filled('email') && $request->email !== $user->email) {
            $data['email'] = $request->email;
        }

        if ($request->filled('country_code')) {
            $data['country_code'] = $request->country_code;
        }

        if ($request->filled('phone')) {
            $data['phone'] = $request->phone;
        }

        if ($request->filled('date_of_birth')) {
            $data['date_of_birth'] = $request->date_of_birth;
        }

        if ($request->filled('address')) {
            $data['address'] = $request->address;
        }

        $user->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully.',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'country_code' => $user->country_code,
                'phone' => $user->phone,
                'date_of_birth' => $user->date_of_birth,
                'address' => $user->address,
                'updated_at' => $user->updated_at->toIso8601String(),
            ],
        ]);
    }

    /**
     * Change password
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function changePassword(Request $request): JsonResponse
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = $request->user();

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'INVALID_PASSWORD',
                    'message' => 'Current password is incorrect.',
                ]
            ], 400);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        // Revoke all tokens except current (force re-login for security)
        // Optionally: $user->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Password changed successfully.',
        ]);
    }

    /**
     * Update user avatar
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updateAvatar(Request $request): JsonResponse
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = $request->user();

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');

            // Delete old avatar if exists and not default
            // if ($user->avatar) {
            //     Storage::disk('public')->delete($user->avatar);
            // }

            // Save full URL or relative path based on your preference
            // Here we save the relative path: 'avatars/filename.jpg'
            // To display: asset('storage/' . $user->avatar)
            $user->update(['avatar' => '/storage/' . $path]); // Storing accessible URL directly
        }

        return response()->json([
            'success' => true,
            'message' => 'Avatar updated successfully.',
            'data' => [
                'avatar' => $user->avatar,
            ],
        ]);
    }

    /**
     * Get user addresses
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function addresses(Request $request): JsonResponse
    {
        $user = $request->user();

        $addresses = $user->addresses()
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'addresses' => $addresses->map(function ($address) {
                    return $this->formatAddress($address);
                })->values(),
                'default_billing' => $user->defaultBillingAddress() ? $this->formatAddress($user->defaultBillingAddress()) : null,
                'default_shipping' => $user->defaultShippingAddress() ? $this->formatAddress($user->defaultShippingAddress()) : null,
            ],
        ]);
    }

    /**
     * Create new address
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function createAddress(Request $request): JsonResponse
    {
        $request->validate([
            'type' => 'required|string|in:billing,shipping',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'phone' => 'nullable|string|max:20',
            'is_default' => 'boolean',
        ]);

        $user = $request->user();

        // If this is set as default, unset other default addresses of the same type
        if ($request->boolean('is_default')) {
            Address::where('user_id', $user->id)
                ->where('type', $request->type)
                ->update(['is_default' => false]);
        }

        $address = Address::create([
            'user_id' => $user->id,
            'type' => $request->type,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'company' => $request->company,
            'address_line_1' => $request->address_line_1,
            'address_line_2' => $request->address_line_2,
            'city' => $request->city,
            'state' => $request->state,
            'postal_code' => $request->postal_code,
            'country' => $request->country,
            'phone' => $request->phone,
            'is_default' => $request->boolean('is_default', false),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Address created successfully.',
            'data' => $this->formatAddress($address),
        ], 201);
    }

    /**
     * Update address
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function updateAddress(Request $request, int $id): JsonResponse
    {
        $user = $request->user();

        $request->validate([
            'first_name' => 'sometimes|required|string|max:255',
            'last_name' => 'sometimes|required|string|max:255',
            'company' => 'nullable|string|max:255',
            'address_line_1' => 'sometimes|required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'sometimes|required|string|max:255',
            'state' => 'sometimes|required|string|max:255',
            'postal_code' => 'sometimes|required|string|max:20',
            'country' => 'sometimes|required|string|max:100',
            'phone' => 'nullable|string|max:20',
            'is_default' => 'boolean',
        ]);

        $address = Address::where('user_id', $user->id)->find($id);

        if (!$address) {
            return $this->notFoundResponse('Address', 'The address you are trying to access was not found.');
        }

        // If setting as default, unset other defaults of same type
        if ($request->filled('is_default') && $request->boolean('is_default')) {
            Address::where('user_id', $user->id)
                ->where('type', $address->type)
                ->where('id', '!=', $id)
                ->update(['is_default' => false]);
        }

        $address->update($request->only([
            'first_name',
            'last_name',
            'company',
            'address_line_1',
            'address_line_2',
            'city',
            'state',
            'postal_code',
            'country',
            'phone',
            'is_default',
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Address updated successfully.',
            'data' => $this->formatAddress($address->fresh()),
        ]);
    }

    /**
     * Delete address
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function deleteAddress(Request $request, int $id): JsonResponse
    {
        $user = $request->user();

        $address = Address::where('user_id', $user->id)->find($id);

        if (!$address) {
            return $this->notFoundResponse('Address', 'The address you are trying to access was not found.');
        }

        $address->delete();

        return response()->json([
            'success' => true,
            'message' => 'Address deleted successfully.',
        ]);
    }

    /**
     * Set default address
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function setDefaultAddress(Request $request, int $id): JsonResponse
    {
        $user = $request->user();

        $address = Address::where('user_id', $user->id)->find($id);

        if (!$address) {
            return $this->notFoundResponse('Address', 'The address you are trying to access was not found.');
        }

        // Unset other defaults of the same type
        Address::where('user_id', $user->id)
            ->where('type', $address->type)
            ->where('id', '!=', $id)
            ->update(['is_default' => false]);

        $address->update(['is_default' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Default address updated successfully.',
            'data' => $this->formatAddress($address->fresh()),
        ]);
    }

    /**
     * Export user data (GDPR compliance)
     *
     * @param Request $request
     * @return \Illuminate\Http\Response|JsonResponse
     */
    public function exportData(Request $request)
    {
        $user = $request->user();

        // Collect all user data
        $data = [
            'profile' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'role' => $user->role,
                'roles' => $user->getRoleNames(),
                'two_factor_enabled' => $user->hasTwoFactorEnabled(),
                'created_at' => $user->created_at->toIso8601String(),
                'updated_at' => $user->updated_at->toIso8601String(),
            ],
            'addresses' => $user->addresses->map(function ($address) {
                return $this->formatAddress($address);
            }),
            'orders' => $user->orders->map(function ($order) {
                return [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'total_amount' => $order->total_amount,
                    'status' => $order->status,
                    'payment_status' => $order->payment_status,
                    'created_at' => $order->created_at->toIso8601String(),
                    'order_items' => $order->orderItems->map(function ($item) {
                        return [
                            'item_id' => $item->item_id,
                            'item_type' => $item->item_type,
                            'quantity' => $item->quantity,
                            'price' => $item->price,
                            'subtotal' => $item->subtotal,
                        ];
                    }),
                ];
            }),
            'reviews' => $user->reviews->map(function ($review) {
                return [
                    'id' => $review->id,
                    'rating' => $review->rating,
                    'title' => $review->title,
                    'content' => $review->content,
                    'images' => $review->images,
                    'is_verified_purchase' => $review->is_verified_purchase,
                    'is_approved' => $review->is_approved,
                    'created_at' => $review->created_at->toIso8601String(),
                    'updated_at' => $review->updated_at->toIso8601String(),
                ];
            }),
            'cart_items' => $user->cartItems->map(function ($item) {
                return [
                    'id' => $item->id,
                    'item_id' => $item->item_id,
                    'item_type' => $item->item_type,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'created_at' => $item->created_at->toIso8601String(),
                ];
            }),
            'wishlist_items' => $user->wishlistItems->map(function ($item) {
                return [
                    'id' => $item->id,
                    'item_id' => $item->item_id,
                    'item_type' => $item->item_type,
                    'created_at' => $item->created_at->toIso8601String(),
                ];
            }),
            'loyalty_points' => [
                'total_earned' => $user->loyaltyPoints()->where('type', 'earned')->sum('points'),
                'total_redeemed' => abs($user->loyaltyPoints()->where('type', 'redeemed')->sum('points')),
                'current_balance' => $user->current_loyalty_points,
                'points_history' => $user->loyaltyPoints->map(function ($point) {
                    return [
                        'id' => $point->id,
                        'points' => $point->points,
                        'type' => $point->type,
                        'source' => $point->source,
                        'description' => $point->description,
                        'created_at' => $point->created_at->toIso8601String(),
                        'expires_at' => $point->expires_at?->toIso8601String(),
                    ];
                }),
            ],
            'plant_care_reminders' => $user->plantCareReminders->map(function ($reminder) {
                return [
                    'id' => $reminder->id,
                    'reminder_type' => $reminder->reminder_type,
                    'title' => $reminder->title,
                    'description' => $reminder->description,
                    'scheduled_date' => $reminder->scheduled_date->toIso8601String(),
                    'is_completed' => $reminder->is_completed,
                    'created_at' => $reminder->created_at->toIso8601String(),
                ];
            }),
            'exported_at' => now()->toIso8601String(),
        ];

        // Return as JSON download
        return response()->json($data, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="user-data-' . $user->id . '-' . now()->format('Y-m-d') . '.json"',
        ]);
    }

    /**
     * Delete account
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteAccount(Request $request): JsonResponse
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $user = $request->user();

        // Verify password
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'INVALID_PASSWORD',
                    'message' => 'Password is incorrect.',
                ]
            ], 400);
        }

        // Revoke all tokens
        $user->tokens()->delete();

        // Delete user (cascade will handle related data)
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Account deleted successfully.',
        ]);
    }

    /**
     * Format address for API response
     *
     * @param Address $address
     * @return array
     */
    private function formatAddress(Address $address): array
    {
        return [
            'id' => $address->id,
            'type' => $address->type,
            'first_name' => $address->first_name,
            'last_name' => $address->last_name,
            'full_name' => $address->full_name,
            'company' => $address->company,
            'address_line_1' => $address->address_line_1,
            'address_line_2' => $address->address_line_2,
            'city' => $address->city,
            'state' => $address->state,
            'postal_code' => $address->postal_code,
            'country' => $address->country,
            'phone' => $address->phone,
            'is_default' => $address->is_default,
            'full_address' => $address->full_address,
            'created_at' => $address->created_at->toIso8601String(),
            'updated_at' => $address->updated_at->toIso8601String(),
        ];
    }
}

