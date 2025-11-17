<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kelompok;             // <-- TAMBAHKAN
use App\Models\InventarisLogistik;


class KasirDashboardController extends Controller
{
    // Menampilkan halaman utama Kasir (untuk scan/input kelompok)
    public function index()
    {
        // Ambil data untuk dropdown di form
        $kelompoks = Kelompok::orderBy('nama')->get();
        $logistiks = InventarisLogistik::orderBy('nama_item')->get();
        // View ini akan berisi form untuk mencatat distribusi
        return view('kasir.dashboard', compact('kelompoks', 'logistiks'));
    }
}