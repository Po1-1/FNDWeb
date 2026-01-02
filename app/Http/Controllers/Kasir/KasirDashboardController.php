<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request; // Pastikan Request di-import
use App\Models\Kelompok;
use App\Models\InventarisLogistik;
use App\Models\Event;
use App\Models\Distribusi;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class KasirDashboardController extends Controller
{
    public function index(Request $request) // Tambahkan parameter Request
    {
        // Cari event yang aktif untuk tenant user ini
        $activeEvent = Event::where('tenant_id', Auth::user()->tenant_id)
            ->where('is_active', true)
            ->first();

        if (!$activeEvent) {
            return view('kasir.dashboard-no-event');
        }

        // 1. LOGIKA HARI KE-
        // Default: Hitung otomatis
        $startDate = $activeEvent->tanggal_mulai;
        if (!($startDate instanceof Carbon)) {
            $startDate = Carbon::parse($startDate);
        }
        $startDate = $startDate->startOfDay();
        $today = Carbon::now()->startOfDay();
        
        if ($startDate->year < 2000) {
            $autoHariKe = 1; 
        } else {
            $diff = $startDate->diffInDays($today, false);
            $autoHariKe = (int) $diff + 1;
        }
        if ($autoHariKe < 1) $autoHariKe = 1;

        // PRIORITAS: Jika user memilih via dropdown (request), pakai itu. Jika tidak, pakai otomatis.
        $hariKe = $request->input('hari_ke', $autoHariKe);


        // 2. LOGIKA WAKTU MAKAN
        // Default: Hitung otomatis berdasarkan jam
        $hour = Carbon::now()->hour;
        $autoWaktuMakan = 'pagi';
        if ($hour >= 10 && $hour < 15) {
            $autoWaktuMakan = 'siang';
        } elseif ($hour >= 15 && $hour < 18) {
            $autoWaktuMakan = 'sore';
        } elseif ($hour >= 18) {
            $autoWaktuMakan = 'malam';
        }

        // PRIORITAS: Jika user memilih via dropdown (request), pakai itu. Jika tidak, pakai otomatis.
        $waktuMakan = $request->input('waktu_makan', $autoWaktuMakan);


        // Ambil data yang relevan dengan event aktif saja
        $kelompoks = Kelompok::where('event_id', $activeEvent->id)->orderBy('nama')->get();
        $logistiks = InventarisLogistik::where('event_id', $activeEvent->id)->orderBy('nama_item')->get();

        // 3. QUERY STATUS PENGAMBILAN (Sesuai Hari & Waktu yang DIPILIH)
        $sudahDiambil = Distribusi::where('event_id', $activeEvent->id)
            ->where('tipe', 'makanan')
            ->where('hari_ke', $hariKe)         // Filter sesuai pilihan
            ->where('waktu_makan', $waktuMakan) // Filter sesuai pilihan
            ->with('kelompok')
            ->latest()
            ->get();

        return view('kasir.dashboard', compact(
            'kelompoks', 
            'logistiks', 
            'activeEvent', 
            'hariKe', 
            'waktuMakan', 
            'sudahDiambil'
        ));
    }
}
