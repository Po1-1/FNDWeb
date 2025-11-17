<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    // Menampilkan daftar semua vendor
    public function index()
    {
        $vendors = Vendor::paginate(10);
        return view('admin.vendors.index', compact('vendors'));
    }

    // Menampilkan form untuk membuat vendor baru
    public function create()
    {
        return view('admin.vendors.create');
    }

    // Menyimpan vendor baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'nama_vendor' => 'required|string|max:255',
            'kontak' => 'nullable|string|max:100',
        ]);

        Vendor::create($request->all());

        return redirect()->route('admin.vendors.index')
                         ->with('success', 'Vendor berhasil ditambahkan.');
    }

    // Menampilkan form untuk mengedit vendor
    public function edit(Vendor $vendor)
    {
        // Menggunakan Route Model Binding
        return view('admin.vendors.edit', compact('vendor'));
    }

    // Update data vendor di database
    public function update(Request $request, Vendor $vendor)
    {
        $request->validate([
            'nama_vendor' => 'required|string|max:255',
            'kontak' => 'nullable|string|max:100',
        ]);

        $vendor->update($request->all());

        return redirect()->route('admin.vendors.index')
                         ->with('success', 'Vendor berhasil diperbarui.');
    }

    // Menghapus vendor dari database
    public function destroy(Vendor $vendor)
    {
        // Tambahkan logika pengecekan jika vendor masih punya makanan
        if ($vendor->makanan()->count() > 0) {
            return redirect()->route('admin.vendors.index')
                             ->with('error', 'Vendor tidak bisa dihapus karena masih memiliki menu makanan.');
        }
        
        $vendor->delete();

        return redirect()->route('admin.vendors.index')
                         ->with('success', 'Vendor berhasil dihapus.');
    }
}