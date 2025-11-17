<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelompok;
use Illuminate\Http\Request;
use App\Models\Vendor;

class KelompokController extends Controller
{
    // Menampilkan semua kelompok
    public function index()
    {
        $kelompoks = Kelompok::withCount('vendor','mahasiswas')->paginate(15);
        return view('admin.kelompok.index', compact('kelompoks'));
    }

    // (Opsional) Form Create
    public function create()
    {
        // Ambil semua vendor untuk dropdown
        $vendors = Vendor::orderBy('nama_vendor')->get();
        return view('admin.kelompok.create', compact('vendors'));
    }

    // (Opsional) Store Create
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|unique:kelompoks',
            'vendor_id' => 'nullable|exists:vendors,id', // Boleh kosong
        ]);

        Kelompok::create([
            'nama' => $request->nama,
            'vendor_id' => $request->vendor_id,
        ]);

        return redirect()->route('admin.kelompok.index')
                         ->with('success', 'Kelompok baru berhasil dibuat.');
    }

    // Menampilkan form edit (INI YANG PENTING)
    public function edit(Kelompok $kelompok)
    {
        // Ambil semua vendor untuk dropdown
        $vendors = Vendor::orderBy('nama_vendor')->get(); 
        
        // Kirim $kelompok DAN $vendors ke view
        return view('admin.kelompok.edit', compact('kelompok', 'vendors'));
    }

    // UPDATE VENDOR (INI INTI LOGIKANYA)
    public function update(Request $request, Kelompok $kelompok)
    {
        $request->validate([
            'nama' => 'required|string|unique:kelompoks,nama,' . $kelompok->id,
            'vendor_id' => 'nullable|exists:vendors,id',
        ]);

        // CUKUP UPDATE SATU BARIS INI
        $kelompok->update([
            'nama' => $request->nama,
            'vendor_id' => $request->vendor_id,
        ]);
        
        // Selesai! Semua mahasiswa di kelompok ini otomatis ikut vendor baru.

        return redirect()->route('admin.kelompok.index')
                         ->with('success', 'Set vendor untuk ' . $kelompok->nama . ' berhasil diperbarui.');
    }

    // (Opsional) Hapus
    public function destroy(Kelompok $kelompok)
    {
        // Hati-hati: Pastikan tidak ada mahasiswa di dalamnya
        if ($kelompok->mahasiswas()->count() > 0) {
            return back()->with('error', 'Kelompok tidak bisa dihapus karena masih memiliki anggota.');
        }
        $kelompok->delete();
        return redirect()->route('admin.kelompok.index')->with('success', 'Kelompok berhasil dihapus.');
    }
}