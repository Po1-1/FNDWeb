<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EventSummary;
use App\Models\Event;
use App\Models\LogPenggunaanLogistik;
use App\Models\InventarisLogistik;
use App\Models\Kelompok;
use App\Models\Distribusi; // Pastikan ini ada
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class EventSummaryController extends Controller
{
    /**
     * PERBAIKAN: Tampilkan semua rekap (Aman jika tidak ada event aktif)
     */
    public function index(Request $request)
    {
        $events = Event::all();
        
        // Cek dulu event aktif, baru ambil ID-nya
        $activeEvent = Event::where('is_active', true)->first();
        $selectedEventId = $request->get('event_id', $activeEvent->id ?? null); // Aman jika $activeEvent null

        $summaries = EventSummary::where('event_id', $selectedEventId)
                                 ->orderBy('tanggal_summary', 'desc')
                                 ->paginate(15);
                                 
        return view('admin.summary.index', compact('summaries', 'events', 'selectedEventId'));
    }

    /**
     * PERBAIKAN: Menampilkan form generate DAN mengirim log kasir
     */
    public function showGeneratorForm(Request $request)
{
    $event = Event::where('is_active', true)->first();
    if (!$event) {
        return redirect()->route('admin.summaries.index')
                         ->with('error', 'Tidak ada event yang aktif! Aktifkan satu event terlebih dahulu.');
    }

    $tanggal = Carbon::parse($request->get('tanggal', now()->format('Y-m-d')));

    $summary = EventSummary::where('event_id', $event->id)
                           ->whereDate('tanggal_summary', $tanggal)
                           ->first();

    // --- TARIK LOG DARI KASIR ---
    $logistikNotes = LogPenggunaanLogistik::whereDate('tanggal_penggunaan', $tanggal)
                        ->whereNotNull('catatan')
                        ->get();

    $makananNotes = Distribusi::whereDate('created_at', $tanggal)
                        ->whereNotNull('catatan')
                        ->with('kelompok:id,nama')
                        ->get();

    // --- KALKULASI PREVIEW (LOGIKA BARU DINAMIS) ---
    $rekapLogistik = LogPenggunaanLogistik::with('inventarisLogistik:id,nama_item,satuan')
        ->whereDate('tanggal_penggunaan', $tanggal)
        // Gunakan DB::raw() untuk menjumlahkan
        ->select('inventaris_logistik_id', DB::raw('SUM(jumlah_digunakan) as total_digunakan'))
        ->groupBy('inventaris_logistik_id')
        ->get()
        ->map(function ($log) {
            // Ubah data menjadi array yang rapi
            return [
                'nama_item' => $log->inventarisLogistik->nama_item ?? 'Item Dihapus',
                'total_digunakan' => (int) $log->total_digunakan,
                'satuan' => $log->inventarisLogistik->satuan ?? 'pcs'
            ];
        });

    return view('admin.summary.generate', compact(
        'event', 
        'tanggal', 
        'summary', 
        'logistikNotes', 
        'makananNotes',
        'rekapLogistik' 
    ));
}

    /**
     * PERBAIKAN: Proses kalkulasi dan penyimpanan snapshot (Lengkap)
     */
    public function generateSnapshot(Request $request)
{
    $request->validate([
        'event_id' => 'required|exists:events,id',
        'tanggal_summary' => 'required|date',
        'sisa_galon' => 'required|integer|min:0',
        'catatan_harian' => 'nullable|string',
    ]);

    $event = Event::find($request->event_id);
    $tanggal = Carbon::parse($request->tanggal_summary);

    // --- MULAI LOGIKA KALKULASI (SAAT GENERATE) ---

    // 1. KALKULASI LOGISTIK (DINAMIS)
    $logistikUsage = LogPenggunaanLogistik::with('inventarisLogistik:id,nama_item,satuan')
        ->whereDate('tanggal_penggunaan', $tanggal)
        ->select('inventaris_logistik_id', DB::raw('SUM(jumlah_digunakan) as total_digunakan'))
        ->groupBy('inventaris_logistik_id')
        ->get();

    $rekapLogistikSnapshot = $logistikUsage->map(function ($log) {
        return [
            'nama_item' => $log->inventarisLogistik->nama_item ?? 'Item Dihapus',
            'total_digunakan' => (int) $log->total_digunakan,
            'satuan' => $log->inventarisLogistik->satuan ?? 'pcs'
        ];
    })->values()->all(); // ->values() untuk reset keys

    // 2. KALKULASI VENDOR BERTUGAS
    $vendorNames = Kelompok::whereHas('vendor')
                    ->with('vendor:id,nama_vendor')
                    ->get()
                    ->pluck('vendor.nama_vendor')
                    ->unique()
                    ->values()
                    ->all();

    // 3. KALKULASI PENGGUNAAN MAKANAN
    $distribusis = Distribusi::with('kelompok.vendor')
                    ->where('tipe', 'makanan')
                    ->whereDate('created_at', $tanggal)
                    ->get();

    $rekapMakanan = $distribusis
        ->filter(fn($dist) => $dist->kelompok && $dist->kelompok->vendor)
        ->groupBy('kelompok.vendor.nama_vendor')
        ->map(fn($group) => $group->sum('jumlah_pengambilan'));

    $rekapMakananSnapshot = [];
    foreach ($rekapMakanan as $namaVendor => $total) {
        $rekapMakananSnapshot[] = ['vendor_nama' => $namaVendor, 'total_diambil' => $total];
    }

    // --- SELESAI KALKULASI ---

    // 4. Simpan sebagai Snapshot
    $summary = EventSummary::updateOrCreate(
        [
            'event_id' => $event->id,
            'tanggal_summary' => $tanggal,
        ],
        [
            // Data Kalkulasi
            'rekap_penggunaan_logistik' => $rekapLogistikSnapshot, // <-- DATA BARU
            'rekap_penggunaan_makanan' => $rekapMakananSnapshot,
            'vendor_bertugas_hari_ini' => $vendorNames,

            // Data Input Manual Admin
            'sisa_galon' => $request->sisa_galon, 
            'catatan_harian' => $request->catatan_harian,
        ]
    );

    return redirect()->route('admin.summaries.show', $summary)
                     ->with('success', 'Laporan harian berhasil di-generate/update.');
}

    /**
     * Menampilkan detail satu rekap (Read-Only)
     */
    public function show(EventSummary $summary)
    {
        return view('admin.summary.show', compact('summary'));
    }
}