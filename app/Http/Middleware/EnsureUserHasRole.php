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
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $role  Role yang diizinkan (admin, kasir, mentor)
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Cek apakah user sudah login DAN rolenya sesuai
        if (!Auth::check() || Auth::user()->role !== $role) {
            // Jika tidak, kembalikan ke halaman dashboard
            // Anda bisa juga menggunakan abort(403)
            return redirect('dashboard');
        }

        return $next($request);
    }
}