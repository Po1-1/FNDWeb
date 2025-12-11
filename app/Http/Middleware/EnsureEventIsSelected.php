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
        // Rute yang dikecualikan dari pengecekan ini
        $excludedRoutes = [
            'admin.events.index',
            'admin.events.create',
            'admin.events.store',
            'admin.events.edit',
            'admin.events.update',
            'admin.events.destroy',
            'admin.events.setActive', // Rute ini akan kita buat nanti
        ];

        // Jika tidak ada event yang dipilih di session DAN rute saat ini bukan bagian dari event management
        if (!session()->has('active_event_id') && !in_array($request->route()->getName(), $excludedRoutes)) {
            return redirect()->route('admin.events.index')
                ->with('warning', 'Silakan buat atau pilih sebuah event terlebih dahulu untuk melanjutkan.');
        }

        return $next($request);
    }
}
