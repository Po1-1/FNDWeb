<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Makanan;
use App\Models\Vendor;
use Illuminate\Http\Request;

class MakananController extends Controller
{
    public function index()
    {
        $activeEventId = session('active_event_id');
        $makanans = Makanan::where('event_id', $activeEventId)
            ->with('vendor')
            ->orderBy('nama_menu')
            ->paginate(10);
        return view('admin.makanan.index', compact('makanans'));
    }

    public function create()
    {
        $activeEventId = session('active_event_id');
        $vendors = Vendor::where('event_id', $activeEventId)->orderBy('nama_vendor')->get();
        return view('admin.makanan.create', compact('vendors'));
    }

    public function store(Request $request)
    {
        $activeEventId = session('active_event_id');
        $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'nama_menu' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'bahan' => 'nullable|string',
            'is_vegan' => 'boolean',
        ]);

        $data = $request->except('is_vegan');
        $data['is_vegan'] = $request->has('is_vegan');
        $data['event_id'] = $activeEventId;

        Makanan::create($data);

        return redirect()->route('admin.makanan.index')->with('success', 'Menu makanan berhasil ditambahkan.');
    }

    public function edit(Makanan $makanan)
    {
        if ($makanan->event_id != session('active_event_id')) {
            abort(404);
        }
        $activeEventId = session('active_event_id');
        $vendors = Vendor::where('event_id', $activeEventId)->orderBy('nama_vendor')->get();
        return view('admin.makanan.edit', compact('makanan', 'vendors'));
    }

    public function update(Request $request, Makanan $makanan)
    {
        if ($makanan->event_id != session('active_event_id')) {
            abort(404);
        }
        $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'nama_menu' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'bahan' => 'nullable|string',
            'is_vegan' => 'boolean',
        ]);

        $data = $request->except('is_vegan');
        $data['is_vegan'] = $request->has('is_vegan');

        $makanan->update($data);

        return redirect()->route('admin.makanan.index')->with('success', 'Menu makanan berhasil diperbarui.');
    }

    public function destroy(Makanan $makanan)
    {
        if ($makanan->event_id != session('active_event_id')) {
            abort(404);
        }
        $makanan->delete();
        return redirect()->route('admin.makanan.index')->with('success', 'Menu makanan berhasil dihapus.');
    }
}