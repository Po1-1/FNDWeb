<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alergi;
use Illuminate\Http\Request;

class AlergiController extends Controller
{
    // Tampilkan semua alergi
    public function index()
    {
        $alergis = Alergi::paginate(10);
        return view('admin.alergi.index', compact('alergis'));
    }

    // Tampilkan form create
    public function create()
    {
        return view('admin.alergi.create');
    }

    // Simpan alergi baru
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:alergis',
            'deskripsi' => 'nullable|string',
        ]);

        Alergi::create($request->all());

        return redirect()->route('admin.alergi.index')
                         ->with('success', 'Data alergi berhasil ditambahkan.');
    }

    // Tampilkan form edit
    public function edit(Alergi $alergi)
    {
        return view('admin.alergi.edit', compact('alergi'));
    }

    // Update alergi
    public function update(Request $request, Alergi $alergi)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:alergis,nama,' . $alergi->id,
            'deskripsi' => 'nullable|string',
        ]);

        $alergi->update($request->all());

        return redirect()->route('admin.alergi.index')
                         ->with('success', 'Data alergi berhasil diperbarui.');
    }

    // Hapus alergi
    public function destroy(Alergi $alergi)
    {
        // Proteksi: Jangan hapus alergi jika masih terhubung ke mahasiswa
        if ($alergi->mahasiswa()->count() > 0) {
            return redirect()->route('admin.alergi.index')
                             ->with('error', 'Alergi tidak dapat dihapus karena masih digunakan oleh data mahasiswa.');
        }

        $alergi->delete();
        return redirect()->route('admin.alergi.index')
                         ->with('success', 'Data alergi berhasil dihapus.');
    }
}