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
        $activeEventId = session('active_event_id');
        $vendors = Vendor::where('event_id', $activeEventId)
            ->orderBy('nama_vendor')
            ->paginate(10);
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
        $activeEventId = session('active_event_id');
        $request->validate([
            'nama_vendor' => 'required|string|max:255',
            'kontak' => 'nullable|string|max:255',
        ]);

        Vendor::create([
            'event_id' => $activeEventId,
            'nama_vendor' => $request->nama_vendor,
            'kontak' => $request->kontak,
        ]);

        return redirect()->route('admin.vendors.index')->with('success', 'Vendor berhasil ditambahkan.');
    }

    // Menampilkan form untuk mengedit vendor
    public function edit(Vendor $vendor)
    {
        if ($vendor->event_id != session('active_event_id')) {
            abort(404);
        }
        return view('admin.vendors.edit', compact('vendor'));
    }

    // Update data vendor di database
    public function update(Request $request, Vendor $vendor)
    {
        if ($vendor->event_id != session('active_event_id')) {
            abort(404);
        }
        $request->validate([
            'nama_vendor' => 'required|string|max:255',
            'kontak' => 'nullable|string|max:255',
        ]);

        $vendor->update($request->all());

        return redirect()->route('admin.vendors.index')->with('success', 'Vendor berhasil diperbarui.');
    }

    // Menghapus vendor dari database
    public function destroy(Vendor $vendor)
    {
        if ($vendor->event_id != session('active_event_id')) {
            abort(404);
        }
        $vendor->delete();
        return redirect()->route('admin.vendors.index')->with('success', 'Vendor berhasil dihapus.');
    }
}
