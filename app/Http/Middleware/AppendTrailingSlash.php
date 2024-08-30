<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AppendTrailingSlash
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $uri = $request->getRequestUri();
        dd($uri);

        // Check if the URI already has a trailing slash or is a root URL
        if (!str_ends_with($uri, '/') && !preg_match('/\.\w+$/', $uri) && !str_contains($uri, '?')) {
            // Redirect to the URI with a trailing slash
            return redirect($uri . '/');
        }

        return $next($request);
    }
}
