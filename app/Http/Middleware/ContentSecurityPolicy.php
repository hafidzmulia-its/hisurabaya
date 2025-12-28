<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ContentSecurityPolicy
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        
        $response->headers->set('Content-Security-Policy', 
            "default-src 'self'; " .
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://unpkg.com; " .
            "style-src 'self' 'unsafe-inline' https://unpkg.com; " .
            "img-src 'self' data: https:; " .
            "font-src 'self' data:;"
        );
        
        return $response;
    }
}