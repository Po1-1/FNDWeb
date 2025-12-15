<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * 
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Cek apakah user sudah login DAN rolenya sesuai
        if (!Auth::check() || Auth::user()->role !== $role) {
            return redirect('dashboard');
        }

        return $next($request);
    }
}