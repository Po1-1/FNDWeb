<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Distribusi;
use App\Models\LogPenggunaanLogistik;
use App\Models\Event; // Untuk mendapatkan event aktif
use App\Models\Kelompok;

class DistribusiController extends Controller
{
    // Mencatat pengambilan Makanan
    public function catatMakanan(Request $request)
    {
        // 1. Validasi input baru
        $request->validate([
            'kelompok_id' => 'required|exists:kelompoks,id',
            'jumlah_pengambilan' => 'required|integer|min:0',
            'catatan' => 'nullable|string|max:1000',
        ]);

        $eventAktif = Event::where('is_active', true)->first();
        
        // 2. Simpan ke database dengan kolom baru
        Distribusi::create([
            'event_id' => $eventAktif ? $eventAktif->id : null,
            'user_id' => Auth::id(), // ID Kasir
            'kelompok_id' => $request->kelompok_id,
            'tipe' => 'makanan',
            'jumlah_pengambilan' => $request->jumlah_pengambilan,
            'catatan' => $request->catatan,
        ]);

        // 3. Beri pesan sukses yang lebih jelas
        $kelompok = Kelompok::find($request->kelompok_id);
        return redirect()->route('kasir.dashboard')
                         ->with('success', "Distribusi ({$request->jumlah_pengambilan} porsi) untuk {$kelompok->nama} berhasil dicatat.");
    }

    // Mencatat pengambilan Logistik
    public function catatLogistik(Request $request)
    {
        // 1. Tambahkan 'catatan' ke validasi
        $request->validate([
            'inventaris_logistik_id' => 'required|exists:inventaris_logistiks,id',
            'jumlah_digunakan' => 'required|integer|min:1',
            'catatan' => 'nullable|string|max:1000', // <-- TAMBAHAN
        ]);

        // 2. Tambahkan 'catatan' saat create
        LogPenggunaanLogistik::create([
            'inventaris_logistik_id' => $request->inventaris_logistik_id,
            'user_id' => Auth::id(),
            'jumlah_digunakan' => $request->jumlah_digunakan,
            'tanggal_penggunaan' => now(),
            'catatan' => $request->catatan, // <-- TAMBAHAN
        ]);

        return redirect()->route('kasir.dashboard')
                         ->with('success', "Pengambilan logistik berhasil dicatat.");
    }
}