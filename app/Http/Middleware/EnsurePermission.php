<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Ensure user has the required permission(s)
 * 
 * Usage in routes:
 * Route::middleware(['auth:sanctum', 'permission:products.create'])->group(...);
 * Route::middleware(['auth:sanctum', 'permission:products.create|products.update'])->group(...);
 */
class EnsurePermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$permissions): Response
    {
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'UNAUTHORIZED',
                    'message' => 'Authentication required.',
                ]
            ], 401);
        }

        $user = auth()->user();

        // Check if user has any of the required permissions
        $hasPermission = false;
        foreach ($permissions as $permission) {
            if ($user->can($permission)) {
                $hasPermission = true;
                break;
            }
        }

        if (!$hasPermission) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'FORBIDDEN',
                    'message' => 'Insufficient permissions. Required permission(s): ' . implode(', ', $permissions),
                ]
            ], 403);
        }

        return $next($request);
    }
}

