<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\EventSummary;
use App\Models\Vendor;

class AdminDashboardController extends Controller
{
    /**
     * Menampilkan dashboard admin.
     */
    public function index()
    {
        // Contoh pengambilan data statistik
        $totalMahasiswa = Mahasiswa::count();
        $totalVendor = Vendor::count();
        
        // Ambil summary event terakhir (contoh)
        $summary = EventSummary::orderBy('tanggal_summary', 'desc')->first();

        return view('admin.dashboard', compact('totalMahasiswa', 'totalVendor', 'summary'));
    }
}