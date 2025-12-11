<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Distribusi;
use App\Models\DistribusiDetail;
use App\Models\LogPenggunaanLogistik;
use App\Models\Event;
use App\Models\Kelompok;
use App\Models\Mahasiswa;
use Carbon\Carbon;
use Illuminate\Validation\Rule; // <-- Tambahkan ini

class DistribusiController extends Controller
{
    private function getActiveEvent()
    {
        return Event::where('tenant_id', Auth::user()->tenant_id) // <-- Perbaikan di sini
            ->where('is_active', true)
            ->firstOrFail();
    }

    // 1. Method Catat Makanan Lama -> DIHAPUS/DIGANTI dengan alur Checklist
    // Kita ganti dashboard logic di KasirDashboardController untuk redirect ke sini

    // 2. Halaman Form Checklist (Langkah 1: Pilih Hari/Kelompok)
    // (Logic ini dipindahkan ke View Dashboard Kasir langsung, lihat View di bawah)

    // 3. Tampilkan Data Anak (Langkah 2) - Dipanggil dari Form Dashboard
    public function loadChecklist(Request $request)
    {
        $request->validate([
            'kelompok_id' => 'required|exists:kelompoks,id',
            'hari_ke' => 'required',
            'waktu_makan' => 'required'
        ]);

        $activeEvent = $this->getActiveEvent();
        $kelompok = Kelompok::where('event_id', $activeEvent->id)
            ->with(['mahasiswas.alergi', 'mahasiswas.customVendor'])
            ->find($request->kelompok_id);

        if (!$kelompok) {
            abort(404, 'Kelompok tidak ditemukan di event ini.');
        }

        $hariKe = $request->hari_ke;
        $waktuMakan = $request->waktu_makan;
        $vendorKelompok = $kelompok->getVendorOn($hariKe, $waktuMakan);

        return view('kasir.distribusi.checklist', compact('kelompok', 'hariKe', 'waktuMakan', 'vendorKelompok'));
    }

    // 4. Simpan Transaksi Makanan (Langkah 3)
    public function storeChecklist(Request $request)
    {
        $activeEvent = $this->getActiveEvent();

        $distribusi = Distribusi::create([
            'event_id' => $activeEvent->id,
            'user_id' => Auth::id(), // <-- Perbaikan di sini
            'kelompok_id' => $request->kelompok_id,
            'tipe' => 'makanan',
            'hari_ke' => $request->hari_ke,
            'waktu_makan' => $request->waktu_makan,
            'catatan' => $request->catatan,
            'jumlah_pengambilan' => count($request->hadir ?? []), // Hitung yg dicentang
        ]);

        // B. Simpan Detail per Mahasiswa (Snapshot Vendor)
        if ($request->has('hadir')) {
            foreach ($request->hadir as $mhsId) {
                $mhs = Mahasiswa::find($mhsId);

                // Tentukan vendor mana yang dipakai anak ini SAAT INI (Snapshot)
                $vendor = $mhs->getVendorFor($request->hari_ke, $request->waktu_makan);
                $vendorIdSnapshot = $vendor ? $vendor->id : null;

                if ($vendorIdSnapshot) {
                    DistribusiDetail::create([
                        'distribusi_id' => $distribusi->id,
                        'mahasiswa_id' => $mhs->id,
                        'vendor_id_snapshot' => $vendorIdSnapshot
                    ]);
                }
            }
        }

        return redirect()->route('kasir.dashboard')->with('success', 'Data Makan berhasil disimpan.');
    }

    // 5. Catat Logistik (Tetap)
    public function catatLogistik(Request $request)
    {
        $activeEvent = $this->getActiveEvent();
        $request->validate([
            'inventaris_logistik_id' => ['required', Rule::exists('inventaris_logistiks', 'id')->where('event_id', $activeEvent->id)],
            'jumlah_digunakan' => 'required|integer|min:1',
            'catatan' => 'nullable|string|max:1000',
        ]);

        LogPenggunaanLogistik::create([
            'inventaris_logistik_id' => $request->inventaris_logistik_id,
            'user_id' => Auth::id(), // <-- Perbaikan di sini
            'jumlah_digunakan' => $request->jumlah_digunakan,
            'tanggal_penggunaan' => now(),
            'catatan' => $request->catatan,
        ]);

        return redirect()->route('kasir.dashboard')->with('success', "Pengambilan logistik berhasil dicatat.");
    }
}
