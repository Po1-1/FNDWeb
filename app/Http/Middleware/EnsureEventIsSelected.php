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
     * 
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Jika tidak ada event yang dipilih 
        if (!session()->has('active_event_id')) {
            
            if (!$request->is('admin/events*')) {

                // paksa redirect ke halaman pemilihan event dengan pesan error.
                return redirect()->route('admin.events.index')
                    ->with('error', 'Silakan pilih atau buat event yang aktif terlebih dahulu.');
            }
        }

        // Jika ada event yang dipilih, ATAU jika kita sedang di halaman event, lanjutkan request.
        return $next($request);
    }
}
