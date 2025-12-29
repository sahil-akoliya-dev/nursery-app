<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * Adds security headers to all responses.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Strict Transport Security (HSTS) - only in production with HTTPS
        if (config('app.env') === 'production' && $request->secure()) {
            $response->headers->set(
                'Strict-Transport-Security',
                'max-age=31536000; includeSubDomains; preload'
            );
        }

        // Prevent clickjacking
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // Prevent MIME type sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // XSS Protection (legacy browsers)
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Referrer Policy
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Permissions Policy (formerly Feature Policy)
        $response->headers->set(
            'Permissions-Policy',
            'geolocation=(), microphone=(), camera=()'
        );

        // Content Security Policy
        $csp = $this->buildCSP();
        $response->headers->set('Content-Security-Policy', $csp);

        // Remove server information
        $response->headers->remove('X-Powered-By');
        $response->headers->remove('Server');

        return $response;
    }

    /**
     * Build Content Security Policy header.
     */
    private function buildCSP(): string
    {
        $base = "'self'";
        $frontendUrl = config('app.frontend_url', 'http://localhost:5500');

        // Parse frontend URL to get origin
        $parsed = parse_url($frontendUrl);
        $frontendOrigin = ($parsed['scheme'] ?? 'http') . '://' . ($parsed['host'] ?? 'localhost') . (isset($parsed['port']) ? ':' . $parsed['port'] : '');
        if (empty($frontendOrigin) || $frontendOrigin === '://') {
            $frontendOrigin = $frontendUrl;
        }

        $policies = [
            "default-src {$base}",
            "script-src {$base} 'unsafe-inline' 'unsafe-eval' https://cdn.tailwindcss.com https://unpkg.com https://cdn.jsdelivr.net https://cdnjs.cloudflare.com",
            "style-src {$base} 'unsafe-inline' https://fonts.googleapis.com https://unpkg.com https://cdn.jsdelivr.net",
            "img-src {$base} data: https: https://images.unsplash.com",
            "font-src {$base} data: https://fonts.gstatic.com",
            "connect-src {$base} {$frontendOrigin} https://cdn.jsdelivr.net https://unpkg.com",
            "frame-ancestors {$base}",
            "form-action {$base} {$frontendOrigin}",
            "base-uri {$base}",
        ];

        return implode('; ', $policies);
    }
}

