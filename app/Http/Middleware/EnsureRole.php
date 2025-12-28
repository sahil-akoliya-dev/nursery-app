<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Ensure user has the required role(s)
 * 
 * Usage in routes:
 * Route::middleware(['auth:sanctum', 'role:admin|manager'])->group(...);
 * Route::middleware(['auth:sanctum', 'role:super_admin'])->group(...);
 */
class EnsureRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
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

        // Check if user has any of the required roles (Spatie only)
        $hasRole = false;
        foreach ($roles as $role) {
            // Only check Spatie roles - no legacy role field
            if ($user->hasRole($role)) {
                $hasRole = true;
                break;
            }
        }

        if (!$hasRole) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'FORBIDDEN',
                    'message' => 'Insufficient permissions. Required role(s): ' . implode(', ', $roles),
                ]
            ], 403);
        }

        return $next($request);
    }
}

