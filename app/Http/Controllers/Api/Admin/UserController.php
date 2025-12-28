<?php

namespace App\Http\Controllers\Api\Admin;

use App\Traits\ApiResponse;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    use ApiResponse;
    /**
     * List all users
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = User::with(['roles'])->withCount(['orders', 'reviews']);

            // Filter by role
            if ($request->filled('role')) {
                $query->where('role', $request->role);
            }

            // Search
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }

            // Filter by verified email
            if ($request->filled('email_verified')) {
                if ($request->boolean('email_verified')) {
                    $query->whereNotNull('email_verified_at');
                } else {
                    $query->whereNull('email_verified_at');
                }
            }

            $sortBy = $request->get('sort', 'created_at');
            $sortOrder = $request->get('order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            $perPage = min($request->get('per_page', 20), 50);
            $users = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $users->map(function ($user) {
                    return $this->formatUser($user);
                }),
                'meta' => [
                    'current_page' => $users->currentPage(),
                    'last_page' => $users->lastPage(),
                    'per_page' => $users->perPage(),
                    'total' => $users->total(),
                ],
            ]);
        } catch (\Exception $e) {
            \Log::error('Admin User Index Error: ' . $e->getMessage());
            return $this->serverErrorResponse('Failed to retrieve users. Please try again.');
        }
    }

    /**
     * Get single user
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $user = User::withCount(['orders', 'reviews', 'loyaltyPoints'])
            ->with(['roles'])
            ->find($id);

        if (!$user) {
            return $this->notFoundResponse('User', 'The user you are looking for was not found.');
        }

        return response()->json([
            'success' => true,
            'data' => $this->formatUser($user, true),
        ]);
    }

    /**
     * Create new user
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:customer,admin,manager',
            'phone' => 'nullable|string|max:20',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
        ]);

        // Assign role
        $user->assignRole($request->role);

        return response()->json([
            'success' => true,
            'message' => 'User created successfully.',
            'data' => $this->formatUser($user->fresh(), true),
        ], 201);
    }

    /**
     * Update user
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $user = User::find($id);

        if (!$user) {
            return $this->notFoundResponse('User', 'The user you are looking for was not found.');
        }

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
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'sometimes|required|string|in:customer,admin,manager',
            'phone' => 'nullable|string|max:20',
        ]);

        $data = [];

        if ($request->filled('name')) {
            $data['name'] = $request->name;
        }

        if ($request->filled('email')) {
            $data['email'] = $request->email;
        }

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->filled('role')) {
            $data['role'] = $request->role;
            // Update role assignment
            $user->syncRoles([$request->role]);
        }

        if ($request->has('phone')) {
            $data['phone'] = $request->phone;
        }

        $user->update($data);

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully.',
            'data' => $this->formatUser($user->fresh(['roles']), true),
        ]);
    }

    /**
     * Delete user
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $currentUser = auth()->user();
        $user = User::find($id);

        if (!$user) {
            return $this->notFoundResponse('User', 'The user you are looking for was not found.');
        }

        // Prevent deleting yourself
        if ($user->id === $currentUser->id) {
            return $this->badRequestResponse(
                'You cannot delete your own account. Please contact another administrator to delete this account.',
                'CANNOT_DELETE_SELF'
            );
        }

        // Only users with users.delete permission can delete users (Super Admin only)
        if (!$currentUser->can('users.delete')) {
            return $this->forbiddenResponse(
                'Only Super Administrators can delete users.',
                'INSUFFICIENT_PERMISSION'
            );
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully.',
        ]);
    }

    /**
     * Format user for API response
     *
     * @param User $user
     * @param bool $detailed
     * @return array
     */
    private function formatUser(User $user, bool $detailed = false): array
    {
        // Safely get role names
        try {
            $roleNames = $user->getRoleNames();
        } catch (\Exception $e) {
            $roleNames = collect([$user->role ?? 'customer']);
        }

        $data = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'role' => $user->role ?? 'customer',
            'roles' => $roleNames,
            'created_at' => is_string($user->created_at) ? $user->created_at : $user->created_at->toIso8601String(),
            'updated_at' => is_string($user->updated_at) ? $user->updated_at : $user->updated_at->toIso8601String(),
        ];

        if ($detailed) {
            $data['stats'] = [
                'orders_count' => $user->orders_count ?? $user->orders()->count(),
                'reviews_count' => $user->reviews_count ?? $user->reviews()->count(),
                'loyalty_points' => $user->current_loyalty_points ?? 0,
                'total_spent' => round($user->orders()->sum('total_amount') ?? 0, 2),
            ];
        }

        return $data;
    }
}

