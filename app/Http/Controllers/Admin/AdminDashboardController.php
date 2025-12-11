<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\EventSummary;
use App\Models\Vendor;
use App\Models\InventarisLogistik; // <-- Tambahkan ini

class AdminDashboardController extends Controller
{
    /**
     * Menampilkan dashboard admin.
     */
    public function index()
    {
        // Ambil ID event yang aktif dari session
        $activeEventId = session('active_event_id');

        // Ambil data statistik yang terikat pada event aktif
        $totalMahasiswa = Mahasiswa::where('event_id', $activeEventId)->count();
        $totalVendor = Vendor::where('event_id', $activeEventId)->count();
        
        // Ambil summary event terakhir untuk event yang aktif
        $summary = EventSummary::where('event_id', $activeEventId)
                                ->orderBy('tanggal_summary', 'desc')
                                ->first();

        return view('admin.dashboard', compact('totalMahasiswa', 'totalVendor', 'summary'));
    }
}