<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Makanan;
use App\Models\Vendor;
use Illuminate\Http\Request;

class MakananController extends Controller
{
    // Menampilkan daftar makanan
    public function index()
    {
        // Eager load relasi 'vendor' untuk efisiensi
        $makanans = Makanan::with('vendor')->paginate(10);
        return view('admin.makanan.index', compact('makanans'));
    }

    // Menampilkan form create
    public function create()
    {
        $vendors = Vendor::all(); // Diperlukan untuk dropdown
        return view('admin.makanan.create', compact('vendors'));
    }

    // Menyimpan makanan baru
    public function store(Request $request)
    {
        $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'nama_menu' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'bahan' => 'required|string',
            'is_vegan' => 'boolean',
        ]);

        // Handle checkbox 'is_vegan'
        $data = $request->all();
        $data['is_vegan'] = $request->has('is_vegan');

        Makanan::create($data);

        return redirect()->route('admin.makanan.index')
                         ->with('success', 'Menu makanan berhasil ditambahkan.');
    }

    // Menampilkan form edit
    public function edit(Makanan $makanan)
    {
        $vendors = Vendor::all();
        return view('admin.makanan.edit', compact('makanan', 'vendors'));
    }

    // Update makanan
    public function update(Request $request, Makanan $makanan)
    {
        $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'nama_menu' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'bahan' => 'required|string',
            'is_vegan' => 'boolean',
        ]);

        $data = $request->all();
        $data['is_vegan'] = $request->has('is_vegan');

        $makanan->update($data);

        return redirect()->route('admin.makanan.index')
                         ->with('success', 'Menu makanan berhasil diperbarui.');
    }

    // Hapus makanan
    public function destroy(Makanan $makanan)
    {
        $makanan->delete();
        return redirect()->route('admin.makanan.index')
                         ->with('success', 'Menu makanan berhasil dihapus.');
    }
}