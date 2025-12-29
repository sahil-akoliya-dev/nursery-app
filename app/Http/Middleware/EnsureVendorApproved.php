<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureVendorApproved
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated.'
            ], 401);
        }

        // Check if vendor profile exists
        if (!$user->vendor) {
            return response()->json([
                'message' => 'Vendor profile not found.'
            ], 404);
        }

        // Check if vendor is approved
        if ($user->vendor->status !== 'approved') {
            return response()->json([
                'message' => 'Your vendor account is pending approval.',
                'status' => $user->vendor->status
            ], 403);
        }

        // Check if user has vendor role (double check)
        if (!$user->hasRole('vendor')) {
            // If approved but role missing (shouldn't happen), try to assign it?
            // Or just deny. Let's deny to be safe.
            return response()->json([
                'message' => 'Unauthorized. Vendor role required.'
            ], 403);
        }

        return $next($request);
    }
}
