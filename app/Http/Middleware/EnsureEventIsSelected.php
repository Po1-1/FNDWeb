<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureEventIsSelected
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Jika tidak ada event yang dipilih di session...
        if (!session()->has('active_event_id')) {
            
            // ...DAN kita TIDAK sedang mencoba mengakses halaman manajemen event...
            // Pengecekan URL lebih andal daripada pengecekan nama rute di sini.
            if (!$request->is('admin/events*')) {

                // ...maka paksa redirect ke halaman pemilihan event dengan pesan error.
                return redirect()->route('admin.events.index')
                    ->with('error', 'Silakan pilih atau buat event yang aktif terlebih dahulu.');
            }
        }

        // Jika ada event yang dipilih, ATAU jika kita sedang di halaman event, lanjutkan request.
        return $next($request);
    }
}
