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

    public function show(EventSummary $summary)
    {
        if ($summary->event->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        return view('admin.summary.show', compact('summary'));
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

        $tanggal = Carbon::parse($request->tanggal_summary);


        // Kalkulasi Rekap Penggunaan Logistik
        $rekapLogistik = LogPenggunaanLogistik::whereDate('tanggal_penggunaan', $tanggal)
            ->join('inventaris_logistiks', 'log_penggunaan_logistiks.inventaris_logistik_id', '=', 'inventaris_logistiks.id')
            ->where('inventaris_logistiks.event_id', $request->event_id)
            ->select('inventaris_logistiks.nama_item', 'inventaris_logistiks.satuan', DB::raw('SUM(log_penggunaan_logistiks.jumlah_digunakan) as total_digunakan'))
            ->groupBy('inventaris_logistiks.nama_item', 'inventaris_logistiks.satuan')
            ->get()
            ->toArray();

        // 2. Kalkulasi Rekap Vendor & Makanan
        $rekapQuery = DistribusiDetail::query()
            ->join('distribusis', 'distribusi_details.distribusi_id', '=', 'distribusis.id')
            ->join('vendors', 'distribusi_details.vendor_id_snapshot', '=', 'vendors.id')
            ->where('distribusis.event_id', $request->event_id)
            ->whereDate('distribusis.created_at', $tanggal)
            ->select(
                'vendors.nama_vendor as vendor_nama',
                DB::raw('COUNT(distribusi_details.id) as total_diambil')
            )
            ->groupBy('vendors.nama_vendor')
            ->orderBy('vendors.nama_vendor');

        $vendorRekap = $rekapQuery->get()->toArray();
        $makananRekap = $rekapQuery->get()->toArray(); // Untuk snapshot, data vendor dan makanan sama


        $summary = EventSummary::updateOrCreate([
            'event_id' => $request->event_id,
            'tanggal_summary' => $tanggal,
        ], [
            'sisa_galon' => $request->sisa_galon,
            'catatan_harian' => $request->catatan_harian,
            'rekap_penggunaan_logistik' => $rekapLogistik,
            'vendor_bertugas_hari_ini' => $vendorRekap,
            'rekap_penggunaan_makanan' => $makananRekap,
        ]);

        return redirect()->route('admin.summaries.show', $summary->id)
            ->with('success', 'Laporan harian berhasil digenerate/diperbarui.');
    }
}
