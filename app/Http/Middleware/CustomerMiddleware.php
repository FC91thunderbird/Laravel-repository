<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
dd($request->user());

        if ($request->user() && $request->user()->role === 'customer') {
            return $next($request);
        }

        // If not a customer, redirect or respond accordingly
        return response()->json(['error' => 'Unauthorized'], 403);
        
        // return $next($request);
    }
}
