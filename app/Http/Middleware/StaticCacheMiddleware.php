<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StaticCacheMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Apply caching headers for static content responses
        if ($response->isSuccessful() && $request->isMethod('GET')) {
            $response->headers->set('Cache-Control', 'public, max-age=3600, must-revalidate');
            $response->headers->set('Pragma', 'cache');
            $response->headers->set('Expires', gmdate('D, d M Y H:i:s', time() + 3600) . ' GMT');
        }

        return $response;
    }
}
