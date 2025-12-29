<?php

namespace App\Http\Controllers\Api;

use App\Traits\ApiResponse;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    use ApiResponse;
    /**
     * Get all roles
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        // Check authorization (if Gate is configured)
        try {
            $this->authorize('users.view'); // Only admins can view roles
        } catch (\Exception $e) {
            // If authorization fails or Gate not configured, check role
            $user = auth()->user();
            if (!$user || !in_array($user->role, ['admin', 'super_admin'])) {
                return response()->json([
                    'success' => false,
                    'error' => [
                        'code' => 'UNAUTHORIZED',
                        'message' => 'You do not have permission to view roles.',
                    ]
                ], 403);
            }
        }

        $roles = Role::with('permissions')->get();

        return response()->json([
            'success' => true,
            'roles' => $roles->map(function ($role) {
                return [
                    'id' => $role->id,
                    'name' => $role->name,
                    'guard_name' => $role->guard_name,
                    'permissions' => $role->permissions->pluck('name'),
                    'permissions_count' => $role->permissions->count(),
                ];
            }),
        ]);
    }

    /**
     * Get all permissions
     *
     * @return JsonResponse
     */
    public function permissions(): JsonResponse
    {
        // Check authorization (if Gate is configured)
        try {
            $this->authorize('users.view');
        } catch (\Exception $e) {
            // If authorization fails or Gate not configured, check role
            $user = auth()->user();
            if (!$user || !in_array($user->role, ['admin', 'super_admin'])) {
                return response()->json([
                    'success' => false,
                    'error' => [
                        'code' => 'UNAUTHORIZED',
                        'message' => 'You do not have permission to view permissions.',
                    ]
                ], 403);
            }
        }

        $permissions = Permission::all();

        return response()->json([
            'success' => true,
            'permissions' => $permissions->map(function ($permission) {
                return [
                    'id' => $permission->id,
                    'name' => $permission->name,
                    'guard_name' => $permission->guard_name,
                ];
            }),
        ]);
    }

    /**
     * Get current user's roles and permissions
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function myPermissions(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'roles' => $user->getRoleNames(),
            'permissions' => $user->getAllPermissions()->pluck('name'),
            'role' => $user->role, // Legacy role field
        ]);
    }

    /**
     * Assign role to user
     *
     * @param Request $request
     * @param User $user
     * @return JsonResponse
     */
    public function assignRole(Request $request, User $user): JsonResponse
    {
        // Check authorization
        try {
            $this->authorize('users.update');
        } catch (\Exception $e) {
            $currentUser = auth()->user();
            if (!$currentUser || !in_array($currentUser->role, ['admin', 'super_admin'])) {
                return response()->json([
                    'success' => false,
                    'error' => [
                        'code' => 'UNAUTHORIZED',
                        'message' => 'You do not have permission to assign roles.',
                    ]
                ], 403);
            }
        }

        $request->validate([
            'role' => 'required|string|exists:roles,name',
        ]);

        $user->assignRole($request->role);

        return response()->json([
            'success' => true,
            'message' => "Role '{$request->role}' assigned to user successfully.",
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->getRoleNames(),
            ],
        ]);
    }

    /**
     * Remove role from user
     *
     * @param Request $request
     * @param User $user
     * @return JsonResponse
     */
    public function removeRole(Request $request, User $user): JsonResponse
    {
        // Check authorization
        try {
            $this->authorize('users.update');
        } catch (\Exception $e) {
            $currentUser = auth()->user();
            if (!$currentUser || !in_array($currentUser->role, ['admin', 'super_admin'])) {
                return response()->json([
                    'success' => false,
                    'error' => [
                        'code' => 'UNAUTHORIZED',
                        'message' => 'You do not have permission to remove roles.',
                    ]
                ], 403);
            }
        }

        $request->validate([
            'role' => 'required|string|exists:roles,name',
        ]);

        $user->removeRole($request->role);

        return response()->json([
            'success' => true,
            'message' => "Role '{$request->role}' removed from user successfully.",
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->getRoleNames(),
            ],
        ]);
    }
}

