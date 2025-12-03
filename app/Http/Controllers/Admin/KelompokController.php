<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelompok;
use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\JadwalKelompok;
use App\Models\Mahasiswa;
use App\Models\Event;
use Carbon\Carbon;

class KelompokController extends Controller
{
    public function index()
    {
        $kelompoks = Kelompok::withCount('vendor', 'mahasiswas')->paginate(15);
        return view('admin.kelompok.index', compact('kelompoks'));
    }

    public function create()
    {
        $vendors = Vendor::orderBy('nama_vendor')->get();
        return view('admin.kelompok.create', compact('vendors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|unique:kelompoks',
            'vendor_id' => 'nullable|exists:vendors,id',
        ]);

        Kelompok::create($request->all());

        return redirect()->route('admin.kelompok.index')
            ->with('success', 'Kelompok baru berhasil dibuat.');
    }

    // EDIT: Menampilkan Grid Jadwal & List Mahasiswa
    public function edit(Kelompok $kelompok)
    {
        $vendors = Vendor::orderBy('nama_vendor')->get();
        
        // Load mahasiswa beserta info custom vendor & alergi
        $kelompok->load(['mahasiswas.alergi', 'mahasiswas.customVendor', 'jadwal']);

        // Hitung durasi event untuk membuat kolom tabel
        $event = Event::where('is_active', true)->first();
        $totalHari = $event ? Carbon::parse($event->tanggal_mulai)->diffInDays($event->tanggal_selesai) + 1 : 3; // Default 3 hari jika tidak ada event

        return view('admin.kelompok.edit', compact('kelompok', 'vendors', 'totalHari'));
    }

    // UPDATE: Menyimpan Jadwal & Custom Vendor
    public function update(Request $request, Kelompok $kelompok)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:kelompoks,nama,' . $kelompok->id,
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