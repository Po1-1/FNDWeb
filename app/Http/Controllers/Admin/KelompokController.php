<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\JadwalKelompok;
use App\Models\Kelompok;
use App\Models\Mahasiswa;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class KelompokController extends Controller
{
    /**
     * Menampilkan daftar semua kelompok untuk event aktif.
     */
    public function index()
    {
        $activeEventId = session('active_event_id');
        $kelompoks = Kelompok::where('event_id', $activeEventId)
            ->withCount('mahasiswas')
            ->orderBy('nama')
            ->paginate(15);

        return view('admin.kelompok.index', compact('kelompoks'));
    }

    public function create()
    {
        $activeEventId = session('active_event_id');
        $vendors = Vendor::where('event_id', $activeEventId)->orderBy('nama_vendor')->get();
        return view('admin.kelompok.create', compact('vendors'));
    }

    public function store(Request $request)
    {
        $activeEventId = session('active_event_id');
        if (!$activeEventId) {
            return redirect()->route('admin.events.index')->with('error', 'Silakan pilih event yang aktif terlebih dahulu.');
        }

        $request->validate([
            'nama' => [
                'required',
                'string',
                'max:255',
                Rule::unique('kelompoks')->where('event_id', $activeEventId),
            ],
        ]);

        Kelompok::create([
            'event_id' => $activeEventId,
            'nama' => $request->nama,
        ]);

        return redirect()->route('admin.kelompok.index')->with('success', 'Kelompok berhasil ditambahkan.');
    }

    public function edit(Kelompok $kelompok)
    {
        $activeEventId = session('active_event_id');
        if ($kelompok->event_id != $activeEventId) { abort(404); }

        $activeEvent = Event::find($activeEventId);
        if (!$activeEvent) { return redirect()->route('admin.events.index')->with('error', 'Event aktif tidak ditemukan.'); }

        $totalHari = $activeEvent->jumlah_hari;
        $vendors = Vendor::where('event_id', $activeEventId)->orderBy('nama_vendor')->get();
        
        $kelompok->load(['mahasiswas.alergi', 'jadwal']);

        return view('admin.kelompok.edit', compact('kelompok', 'totalHari', 'vendors'));
    }

    public function update(Request $request, Kelompok $kelompok)
    {
        $activeEventId = session('active_event_id');
        if ($kelompok->event_id != $activeEventId) { abort(404); }

        $request->validate([
            'nama' => ['required', 'string', 'max:255', Rule::unique('kelompoks')->where('event_id', $activeEventId)->ignore($kelompok->id)],
            'custom_vendor.*' => 'nullable|exists:vendors,id',
            'jadwal.*.*' => 'nullable|exists:vendors,id',
        ]);

        DB::transaction(function () use ($request, $kelompok) {
            $kelompok->update(['nama' => $request->nama]);

            if ($request->has('custom_vendor')) {
                foreach ($request->custom_vendor as $mhsId => $vendorId) {
                    Mahasiswa::where('id', $mhsId)->where('kelompok_id', $kelompok->id)->update(['custom_vendor_id' => $vendorId ?: null]);
                }
            }

            $kelompok->jadwal()->delete();
            if ($request->has('jadwal')) {
                foreach ($request->jadwal as $hari => $waktuData) {
                    foreach ($waktuData as $waktu => $vendorId) {
                        if ($vendorId) {
                            JadwalKelompok::create([
                                'kelompok_id' => $kelompok->id, 'hari_ke' => $hari, 'waktu_makan' => $waktu, 'vendor_id' => $vendorId,
                            ]);
                        }
                    }
                }
            }
        });

        return redirect()->back()->with('success', 'Data kelompok, jadwal, dan override mahasiswa berhasil diperbarui.');
    }

    public function destroy(Kelompok $kelompok)
    {
        if ($kelompok->event_id != session('active_event_id')) {
            abort(404);
        }
        if ($kelompok->mahasiswas()->count() > 0) {
            return back()->with('error', 'Kelompok tidak bisa dihapus karena masih memiliki anggota.');
        }
        $kelompok->delete();
        return redirect()->route('admin.kelompok.index')->with('success', 'Kelompok berhasil dihapus.');
    }
}
