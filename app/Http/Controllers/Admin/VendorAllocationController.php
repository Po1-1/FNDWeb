<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\JadwalKelompok;
use App\Models\Kelompok;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendorAllocationController extends Controller
{
    /**
     * Menampilkan halaman alokasi untuk satu vendor.
     */
    public function show(Request $request, Vendor $vendor)
    {
        $activeEventId = session('active_event_id');
        $activeEvent = Event::findOrFail($activeEventId);

        // Pastikan vendor ini milik event yang aktif
        if ($vendor->event_id != $activeEventId) {
            abort(404, 'Vendor tidak ditemukan di event ini.');
        }

        $hariKe = $request->input('hari_ke', 1);
        $totalHari = $activeEvent->total_hari;

        $kelompoks = Kelompok::where('event_id', $activeEventId)->orderBy('nama')->get();

        // Ambil semua jadwal di hari ini untuk semua kelompok
        $jadwalHariIni = JadwalKelompok::where('hari_ke', $hariKe)
            ->whereIn('kelompok_id', $kelompoks->pluck('id'))
            ->with('vendor:id,nama_vendor')
            ->get();

        // Buat map untuk pengecekan cepat di view: [kelompok_id][waktu_makan] => jadwal
        $jadwalMap = [];
        foreach ($jadwalHariIni as $jadwal) {
            $jadwalMap[$jadwal->kelompok_id][$jadwal->waktu_makan] = $jadwal;
        }

        return view('admin.vendors.allocation', compact(
            'vendor',
            'kelompoks',
            'totalHari',
            'hariKe',
            'jadwalMap'
        ));
    }

    /**
     * Menyimpan data alokasi dari form.
     */
    public function store(Request $request, Vendor $vendor)
    {
        $request->validate([
            'hari_ke' => 'required|integer|min:1',
            'jadwal' => 'nullable|array',
        ]);

        $hariKe = $request->hari_ke;
        $jadwalInput = $request->input('jadwal', []);

        DB::transaction(function () use ($vendor, $hariKe, $jadwalInput) {
            $activeEventId = session('active_event_id');
            $kelompokIds = Kelompok::where('event_id', $activeEventId)->pluck('id');

            foreach ($kelompokIds as $kelompokId) {
                foreach (['pagi', 'siang', 'sore', 'malam'] as $waktu) {
                    $isChecked = isset($jadwalInput[$kelompokId][$waktu]);

                    if ($isChecked) {
                        // Jika dicentang: buat atau timpa jadwal yang ada dengan vendor ini.
                        JadwalKelompok::updateOrCreate(
                            [
                                'kelompok_id' => $kelompokId,
                                'hari_ke' => $hariKe,
                                'waktu_makan' => $waktu,
                            ],
                            ['vendor_id' => $vendor->id]
                        );
                    } else {
                        // Jika tidak dicentang: hapus jadwal HANYA JIKA jadwal itu milik vendor ini.
                        // Ini mencegah kita menghapus jadwal yang sudah diatur untuk vendor lain.
                        JadwalKelompok::where('kelompok_id', $kelompokId)
                            ->where('hari_ke', $hariKe)
                            ->where('waktu_makan', $waktu)
                            ->where('vendor_id', $vendor->id)
                            ->delete();
                    }
                }
            }
        });

        return redirect()->back()->with('success', 'Alokasi kelompok untuk ' . $vendor->nama_vendor . ' berhasil diperbarui.');
    }
}
