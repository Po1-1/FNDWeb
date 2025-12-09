<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InventarisLogistik;
use Illuminate\Http\Request;

class InventarisLogistikController extends Controller
{
    // Menampilkan daftar semua item logistik
    public function index()
    {
        $logistiks = InventarisLogistik::paginate(10);
        return view('admin.logistik.index', compact('logistiks'));
    }

    // Menampilkan form untuk item baru
    public function create()
    {
        return view('admin.logistik.create');
    }

    // Menyimpan item baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_item' => 'required|string|max:255|unique:inventaris_logistiks',
            'stok_awal' => 'required|integer|min:0',
            'satuan' => 'required|string|max:50',
        ]);

        InventarisLogistik::create($request->all());

        return redirect()->route('admin.logistik.index')
            ->with('success', 'Item logistik berhasil ditambahkan.');
    }

    // Menampilkan detail (opsional, bisa dilewati jika tidak perlu)
    public function show(InventarisLogistik $logistik)
    {
        return view('admin.logistik.show', compact('logistik'));
    }

    // Menampilkan form edit
    public function edit(InventarisLogistik $logistik)
    {
        // $logistik di-inject otomatis (Route Model Binding)
        return view('admin.logistik.edit', compact('logistik'));
    }

    // Update item di database
    public function update(Request $request, InventarisLogistik $logistik)
    {
        $request->validate([
            'nama_item' => 'required|string|max:255|unique:inventaris_logistiks,nama_item,' . $logistik->id,
            'stok_awal' => 'required|integer|min:0',
            'satuan' => 'required|string|max:50',
        ]);

        $logistik->update($request->all());

        return redirect()->route('admin.logistik.index')
            ->with('success', 'Item logistik berhasil diperbarui.');
    }

    // Hapus item
    public function destroy(InventarisLogistik $logistik)
    {
        // Tambahkan cek relasi jika ada

        $logistik->delete();
        return redirect()->route('admin.logistik.index')
            ->with('success', 'Item logistik berhasil dihapus.');
    }
}
