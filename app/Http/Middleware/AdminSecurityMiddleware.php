<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\AuditLog;

class AdminSecurityMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        
        // Check if user is authenticated
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please log in to access the admin panel.');
        }

        // Check if user is admin
        if (!$user->isAdmin()) {
            Log::warning('Non-admin user attempted to access admin panel', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->url(),
            ]);
            
            return redirect()->route('home')->with('error', 'Access denied. Admin privileges required.');
        }

        // Check IP whitelist (if configured)
        if (config('admin.ip_whitelist_enabled', false)) {
            $allowedIPs = config('admin.allowed_ips', []);
            if (!empty($allowedIPs) && !in_array($request->ip(), $allowedIPs)) {
                Log::critical('Blocked admin access from unauthorized IP', [
                    'user_id' => $user->id,
                    'user_email' => $user->email,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'url' => $request->url(),
                ]);
                
                return response()->view('errors.403', ['message' => 'Access denied from this IP address.'], 403);
            }
        }

        // Check if 2FA is required and enabled
        if (config('admin.2fa_required', true) && !$user->hasTwoFactorEnabled()) {
            // Allow access to 2FA setup pages
            if (!$request->routeIs('admin.two-factor.*') && !$request->routeIs('admin.settings')) {
                return redirect()->route('admin.two-factor.setup')
                    ->with('warning', 'Two-factor authentication is required for admin access.');
            }
        }

        // Log admin access (for sensitive operations)
        if ($this->isSensitiveOperation($request)) {
            AuditLog::create([
                'user_id' => $user->id,
                'action' => 'admin_access',
                'model_type' => null,
                'model_id' => null,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->url(),
                'metadata' => [
                    'method' => $request->method(),
                    'route' => $request->route() ? $request->route()->getName() : null,
                ],
            ]);
        }

        // Set admin-specific session configuration
        $this->configureAdminSession($request);

        return $next($request);
    }

    /**
     * Check if the request is a sensitive admin operation.
     */
    private function isSensitiveOperation(Request $request): bool
    {
        $sensitiveRoutes = [
            'admin.users.*',
            'admin.settings',
            'admin.audit-logs.*',
            'admin.database.*',
            'admin.debug',
            'admin.two-factor.*',
        ];

        foreach ($sensitiveRoutes as $route) {
            if ($request->routeIs($route)) {
                return true;
            }
        }

        // Check for sensitive HTTP methods
        if (in_array($request->method(), ['POST', 'PUT', 'DELETE', 'PATCH'])) {
            return true;
        }

        return false;
    }

    /**
     * Configure admin-specific session settings.
     */
    private function configureAdminSession(Request $request): void
    {
        // Set shorter session timeout for admin
        $request->session()->put('admin_session', true);
        $request->session()->put('admin_last_activity', now());
        
        // Check for admin session timeout
        $lastActivity = $request->session()->get('admin_last_activity');
        $timeout = config('admin.session_timeout', 30); // 30 minutes
        
        if ($lastActivity && $lastActivity->addMinutes($timeout)->isPast()) {
            $request->session()->forget(['admin_session', 'admin_last_activity']);
            Auth::logout();
            
            Log::info('Admin session expired', [
                'user_id' => Auth::id(),
                'ip_address' => $request->ip(),
            ]);
        }
    }
}