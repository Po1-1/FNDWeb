<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\EventSummary;
use App\Models\Vendor;
use App\Models\InventarisLogistik;
use App\Models\Event;

class AdminDashboardController extends Controller
{
    /**
     * Menampilkan dashboard admin.
     */
    public function index()
    {
        $activeEventId = session('active_event_id');
        $activeEvent = Event::find($activeEventId); // Ambil data event aktif

        // Ambil data statistik yang terikat pada event aktif
        $totalMahasiswa = Mahasiswa::where('event_id', $activeEventId)->count();
        $totalVendor = Vendor::where('event_id', $activeEventId)->count();
        
        // Ambil summary event terakhir untuk event yang aktif
        $summary = EventSummary::where('event_id', $activeEventId)
                                ->orderBy('tanggal_summary', 'desc')
                                ->first();

        // Jika tidak ada summary, coba cari stok awal galon dari data master logistik.
        if (!$summary) {
            $galonLogistik = InventarisLogistik::where('event_id', $activeEventId)
                                                ->where('nama_item', 'like', '%galon%')
                                                ->first();
            
            $summary = new EventSummary([
                'sisa_galon' => $galonLogistik ? $galonLogistik->stok_awal : 0
            ]);
        }

        // Kirim data event aktif ke view
        return view('admin.dashboard', compact('totalMahasiswa', 'totalVendor', 'summary', 'activeEvent'));
    }
}