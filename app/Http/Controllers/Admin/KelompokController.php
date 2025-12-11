<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelompok;
use App\Models\Mahasiswa;
use App\Models\Vendor;
use App\Models\JadwalKelompok;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; // PASTIKAN INI ADA

class KelompokController extends Controller
{
    public function index()
    {
        $activeEventId = session('active_event_id');

        $kelompoks = Kelompok::where('event_id', $activeEventId)
            ->withCount('mahasiswas')
            ->orderBy('nama')
            ->paginate(10);

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
                Rule::unique('kelompoks')->where(function ($query) use ($activeEventId) {
                    return $query->where('event_id', $activeEventId);
                }),
            ],
            'vendor_id' => 'nullable|exists:vendors,id',
        ]);

        Kelompok::create([
            'event_id' => $activeEventId,
            'nama' => $request->nama,
            'vendor_id' => $request->vendor_id,
        ]);

        return redirect()->route('admin.kelompok.index')->with('success', 'Kelompok berhasil ditambahkan.');
    }

    public function show(Kelompok $kelompok)
    {
        // Eager load relasi untuk efisiensi
        $kelompok->load('mahasiswas', 'vendor');
        return view('admin.kelompok.show', compact('kelompok'));
    }

    public function edit(Kelompok $kelompok)
    {
        if ($kelompok->event_id != session('active_event_id')) {
            abort(404);
        }

        $activeEvent = Event::find(session('active_event_id'));
        if (!$activeEvent) {
            return redirect()->route('admin.events.index')->with('error', 'Silakan pilih event.');
        }

        $totalHari = $activeEvent->jumlah_hari; // <-- Gunakan kolom baru
        $vendors = Vendor::where('event_id', $activeEvent->id)->orderBy('nama_vendor')->get();
        
        $kelompok->load(['mahasiswas', 'jadwal']);

        return view('admin.kelompok.edit', compact('kelompok', 'totalHari', 'vendors'));
    }

    // UPDATE: Menyimpan Jadwal & Custom Vendor
    public function update(Request $request, Kelompok $kelompok)
    {
        $activeEventId = session('active_event_id');
        if ($kelompok->event_id != $activeEventId) {
            abort(404);
        }

        $request->validate([
            'nama' => [
                'required',
                'string',
                'max:255',
                // INI PERBAIKANNYA: Aturan unik sekarang scoped ke event aktif
                // dan mengabaikan ID kelompok yang sedang diedit.
                Rule::unique('kelompoks')->where(function ($query) use ($activeEventId) {
                    return $query->where('event_id', $activeEventId);
                })->ignore($kelompok->id),
            ],
        ]);

        // 1. Update Nama Dasar
        $kelompok->update(['nama' => $request->nama]);

        // 2. Update Custom Vendor per Mahasiswa (Override)
        if ($request->has('custom_vendor')) {
            foreach ($request->custom_vendor as $mhsId => $vendorId) {
                $mhs = Mahasiswa::find($mhsId);
                if ($mhs) {
                    // Jika kosong, set null (ikut kelompok)
                    $mhs->update(['custom_vendor_id' => $vendorId ?: null]);
                }
            }
        }

        // 3. Update Jadwal Kelompok (Grid)
        if ($request->has('jadwal')) {
            // Hapus jadwal lama agar bersih
            $kelompok->jadwal()->delete();

            foreach ($request->jadwal as $hari => $waktuData) {
                foreach ($waktuData as $waktu => $vendorId) {
                    if ($vendorId) {
                        // Sekarang JadwalKelompok sudah dikenali
                        JadwalKelompok::create([
                            'kelompok_id' => $kelompok->id,
                            'hari_ke' => $hari,
                            'waktu_makan' => $waktu,
                            'vendor_id' => $vendorId
                        ]);
                    }
                }
            }
        }

        return redirect()->back()->with('success', 'Data Kelompok, Jadwal, dan Mahasiswa berhasil diperbarui.');
    }

    public function destroy(Kelompok $kelompok)
    {
        if ($kelompok->mahasiswas()->count() > 0) {
            return back()->with('error', 'Kelompok tidak bisa dihapus karena masih memiliki anggota.');
        }
        $kelompok->delete();
        return redirect()->route('admin.kelompok.index')->with('success', 'Kelompok berhasil dihapus.');
    }
}