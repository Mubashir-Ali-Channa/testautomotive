<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecureHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Security headers
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Static pages caching overrides
        if ($response->isSuccessful() && ($request->isMethod('GET') || $request->isMethod('HEAD'))) {
            $path = $request->getPathInfo();
            if ($path === '/about' || $path === '/locations') {
                $response->headers->set('Cache-Control', 'public, max-age=3600, must-revalidate');
                $response->headers->remove('Pragma');
                $response->headers->set('Expires', gmdate('D, d M Y H:i:s', time() + 3600) . ' GMT');
            }
        }

         // Content Security Policy
        $csp = "default-src 'self'; "
             . "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://unpkg.com; "
             . "style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://fonts.googleapis.com https://unpkg.com https://cdnjs.cloudflare.com; "
             . "font-src 'self' https://fonts.gstatic.com https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; "
             . "img-src 'self' data: https: http: blob:; "
             . "connect-src 'self' https://*.tile.openstreetmap.org; "
             . "frame-src 'self'; "
             . "object-src 'none';";
        
        $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}
