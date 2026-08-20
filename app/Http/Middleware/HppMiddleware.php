<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HppMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Logika middleware untuk route hpp
        return $next($request);
    }
}