<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InventarisLogistik;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class InventarisLogistikController extends Controller
{
    // Menampilkan daftar semua item logistik
    public function index()
    {
        $activeEventId = session('active_event_id');
        $logistiks = InventarisLogistik::where('event_id', $activeEventId)->orderBy('nama_item')->paginate(10);
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
        $activeEventId = session('active_event_id');
        $request->validate([
            'nama_item' => [
                'required',
                'string',
                'max:255',
                Rule::unique('inventaris_logistiks')->where(function ($query) use ($activeEventId) {
                    return $query->where('event_id', $activeEventId);
                }),
            ],
            'stok_awal' => 'required|integer|min:0',
            'satuan' => 'required|string|max:50',
        ]);

        InventarisLogistik::create([
            'event_id' => $activeEventId,
            'nama_item' => $request->nama_item,
            'stok_awal' => $request->stok_awal,
            'satuan' => $request->satuan,
        ]);

        return redirect()->route('admin.logistik.index')->with('success', 'Item logistik berhasil ditambahkan.');
    }

    // Menampilkan detail (opsional, bisa dilewati jika tidak perlu)
    public function show(InventarisLogistik $logistik)
    {
        if ($logistik->event_id != session('active_event_id')) {
            abort(404);
        }
        $logistik->load('logPenggunaan');
        return view('admin.logistik.show', compact('logistik'));
    }

    // Menampilkan form edit
    public function edit(InventarisLogistik $logistik)
    {
        if ($logistik->event_id != session('active_event_id')) {
            abort(404);
        }
        return view('admin.logistik.edit', compact('logistik'));
    }

    // Update item di database
    public function update(Request $request, InventarisLogistik $logistik)
    {
        if ($logistik->event_id != session('active_event_id')) {
            abort(404);
        }
        $activeEventId = session('active_event_id');
        $request->validate([
            'nama_item' => [
                'required',
                'string',
                'max:255',
                Rule::unique('inventaris_logistiks')->where(function ($query) use ($activeEventId) {
                    return $query->where('event_id', $activeEventId);
                })->ignore($logistik->id),
            ],
            'stok_awal' => 'required|integer|min:0',
            'satuan' => 'required|string|max:50',
        ]);

        $logistik->update($request->all());

        return redirect()->route('admin.logistik.index')->with('success', 'Item logistik berhasil diperbarui.');
    }

    // Hapus item
    public function destroy(InventarisLogistik $logistik)
    {
        if ($logistik->event_id != session('active_event_id')) {
            abort(404);
        }
        $logistik->delete();
        return redirect()->route('admin.logistik.index')->with('success', 'Item logistik berhasil dihapus.');
    }
}
