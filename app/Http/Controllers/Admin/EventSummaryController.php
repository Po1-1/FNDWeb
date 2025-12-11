<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventSummary;
use App\Models\LogPenggunaanLogistik;
use App\Models\Distribusi;
use App\Models\DistribusiDetail;
use App\Models\InventarisLogistik;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class EventSummaryController extends Controller
{
    public function index(Request $request)
    {
        $events = Event::all();
        $activeEvent = Event::where('is_active', true)->first();
        $selectedEventId = $request->get('event_id', $activeEvent->id ?? null);

        $summaries = EventSummary::where('event_id', $selectedEventId)
            ->orderBy('tanggal_summary', 'desc')
            ->paginate(15);

        return view('admin.summary.index', compact('summaries', 'events', 'selectedEventId'));
    }

    public function showGeneratorForm(Request $request)
    {
        $event = Event::where('is_active', true)->first();
        if (!$event) {
            return redirect()->route('admin.summaries.index')->with('error', 'Tidak ada event aktif.');
        }

        $tanggal = Carbon::parse($request->get('tanggal', now()->format('Y-m-d')));
        $summary = EventSummary::where('event_id', $event->id)->whereDate('tanggal_summary', $tanggal)->first();

        $logistikNotes = LogPenggunaanLogistik::whereDate('tanggal_penggunaan', $tanggal)->whereNotNull('catatan')->get();

        // Note: Makanan notes diambil dari header
        $makananNotes = Distribusi::whereDate('created_at', $tanggal)->whereNotNull('catatan')->with('kelompok:id,nama')->get();

        $rekapLogistik = LogPenggunaanLogistik::with('inventarisLogistik:id,nama_item,satuan')
            ->whereDate('tanggal_penggunaan', $tanggal)
            ->select('inventaris_logistik_id', DB::raw('SUM(jumlah_digunakan) as total_digunakan'))
            ->groupBy('inventaris_logistik_id')
            ->get()
            ->map(function ($log) {
                return [
                    'nama_item' => $log->inventarisLogistik->nama_item ?? 'Item Dihapus',
                    'total_digunakan' => (int) $log->total_digunakan,
                    'satuan' => $log->inventarisLogistik->satuan ?? 'pcs'
                ];
            });

        return view('admin.summary.generate', compact('event', 'tanggal', 'summary', 'logistikNotes', 'makananNotes', 'rekapLogistik'));
    }

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

        // 1. KALKULASI LOGISTIK (DENGAN SISA STOK)
        $logistikAwal = InventarisLogistik::where('event_id', $event->id)->get()->keyBy('id');
        $logistikUsage = LogPenggunaanLogistik::where('event_id', $event->id)
            ->whereDate('tanggal_penggunaan', '<=', $tanggal) // Akumulasi dari awal event
            ->select('inventaris_logistik_id', DB::raw('SUM(jumlah_digunakan) as total_digunakan'))
            ->groupBy('inventaris_logistik_id')
            ->get()
            ->keyBy('inventaris_logistik_id');

        $rekapLogistikSnapshot = $logistikAwal->map(function ($item) use ($logistikUsage) {
            $penggunaan = $logistikUsage->get($item->id);
            $totalDigunakan = $penggunaan ? $penggunaan->total_digunakan : 0;
            return [
                'nama_item' => $item->nama_item,
                'stok_awal' => (int) $item->stok_awal,
                'total_digunakan' => (int) $totalDigunakan,
                'sisa_stok' => (int) $item->stok_awal - $totalDigunakan,
                'satuan' => $item->satuan
            ];
        })->values()->all();

        // 2. KALKULASI PENGGUNAAN MAKANAN (DARI DETAIL - LEBIH AKURAT)
        // Mengambil data dari tabel distribusi_details yang menyimpan vendor_id_snapshot
        $rekapMakananQuery = DistribusiDetail::query()
            ->join('distribusis', 'distribusi_details.distribusi_id', '=', 'distribusis.id')
            ->whereDate('distribusis.created_at', $tanggal)
            ->where('distribusis.tipe', 'makanan')
            ->with('vendor')
            ->select('vendor_id_snapshot', DB::raw('count(*) as total'))
            ->groupBy('vendor_id_snapshot')
            ->get();

        $rekapMakananSnapshot = [];
        foreach ($rekapMakananQuery as $item) {
            $rekapMakananSnapshot[] = [
                'vendor_nama' => $item->vendor->nama_vendor ?? 'Vendor Terhapus',
                'total_diambil' => $item->total
            ];
        }

        // 3. Simpan Snapshot
        $summary = EventSummary::updateOrCreate(
            [
                'event_id' => $event->id,
                'tanggal_summary' => $tanggal,
            ],
            [
                'rekap_penggunaan_logistik' => $rekapLogistikSnapshot,
                'rekap_penggunaan_makanan' => $rekapMakananSnapshot,
                'sisa_galon' => $request->sisa_galon,
                'catatan_harian' => $request->catatan_harian,
            ]
        );

        return redirect()->route('admin.summaries.show', $summary)->with('success', 'Laporan harian berhasil di-generate.');
    }

    public function show(EventSummary $summary)
    {
        return view('admin.summary.show', compact('summary'));
    }
}
