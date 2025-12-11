<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kelompok;
use App\Models\InventarisLogistik;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;

class KasirDashboardController extends Controller
{
    // Menampilkan halaman utama Kasir (untuk scan/input kelompok)
    public function index()
    {
        // Cari event yang aktif untuk tenant user ini
        $activeEvent = Event::where('tenant_id', Auth::user()->tenant_id)
                            ->where('is_active', true)
                            ->first();

        if (!$activeEvent) {
            return view('kasir.dashboard-no-event');
        }

        // Ambil data yang relevan dengan event aktif saja
        $kelompoks = Kelompok::where('event_id', $activeEvent->id)->orderBy('nama')->get();
        $logistiks = InventarisLogistik::where('event_id', $activeEvent->id)->orderBy('nama_item')->get();
        
        // View ini akan berisi form untuk mencatat distribusi
        return view('kasir.dashboard', compact('kelompoks', 'logistiks', 'activeEvent'));
    }
}