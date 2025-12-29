<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    use ApiResponse;

    /**
     * Get user's addresses
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $addresses = $request->user()->addresses()->latest()->get();
        return $this->successResponse($addresses);
    }

    /**
     * Store a new address
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'label' => 'sometimes|nullable|string|max:50', // e.g., Home, Work (or Type validation)
            'type' => 'nullable|string|in:delivery,billing', // Add type validation
            'first_name' => 'nullable|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:100',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'is_default' => 'boolean'
        ]);

        // Default type to 'delivery' if not provided
        if (!isset($validated['type'])) {
            $validated['type'] = 'delivery';
        }

        $user = $request->user();

        // Fallback for names if not provided
        if (empty($validated['first_name'])) {
            $nameParts = explode(' ', $user->name, 2);
            $validated['first_name'] = $nameParts[0] ?? '';
            $validated['last_name'] = $validated['last_name'] ?? ($nameParts[1] ?? '');
        }

        // If this is the first address or set as default, unset other defaults
        if ($request->is_default || $user->addresses()->count() === 0) {
            $user->addresses()->update(['is_default' => false]);
            $validated['is_default'] = true;
        }

        $address = $user->addresses()->create($validated);

        return $this->successResponse($address, 'Address added successfully.');
    }

    /**
     * Update an address
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $address = $request->user()->addresses()->find($id);

        if (!$address) {
            return $this->notFoundResponse('Address');
        }

        $validated = $request->validate([
            'label' => 'sometimes|nullable|string|max:50',
            'type' => 'nullable|string|in:delivery,billing',
            'first_name' => 'sometimes|nullable|string|max:100',
            'last_name' => 'sometimes|nullable|string|max:100',
            'phone' => 'sometimes|nullable|string|max:20',
            'company' => 'sometimes|nullable|string|max:100',
            'address_line_1' => 'sometimes|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'sometimes|string|max:100',
            'state' => 'sometimes|string|max:100',
            'postal_code' => 'sometimes|string|max:20',
            'country' => 'sometimes|string|max:100',
            'is_default' => 'boolean'
        ]);

        if ($request->is_default) {
            $request->user()->addresses()->where('id', '!=', $id)->update(['is_default' => false]);
        }

        $address->update($validated);

        return $this->successResponse($address, 'Address updated successfully.');
    }

    /**
     * Delete an address
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $address = $request->user()->addresses()->find($id);

        if (!$address) {
            return $this->notFoundResponse('Address');
        }

        $address->delete();

        return $this->successResponse(null, 'Address deleted successfully.');
    }
}
