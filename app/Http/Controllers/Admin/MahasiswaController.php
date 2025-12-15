<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Kelompok;
use App\Models\Alergi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Imports\MahasiswaImport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;

class MahasiswaController extends Controller
{
    /**
     * Menampilkan semua mahasiswa dengan searching & pagination
     */
    public function index(Request $request)
    {
        $activeEventId = session('active_event_id');
        $search = $request->input('search');

        $mahasiswas = Mahasiswa::query()
            // FILTER UTAMA BERDASARKAN EVENT AKTIF
            ->where('event_id', $activeEventId)

            ->with(['kelompok', 'alergi'])

            // 💡 FIX 1: PENCEGAHAN DUPLIKASI QUERY
            // Pilih kolom spesifik dan paksa hasil unik berdasarkan ID Mahasiswa.
            ->select('mahasiswas.*')
            ->distinct('mahasiswas.id')

            ->when($search, function ($q, $search) use ($activeEventId) {
                $q->where(function ($subQ) use ($search, $activeEventId) { // Menggunakan subQ untuk grouping OR conditions

                    $subQ->where('nama', 'LIKE', "%{$search}%")
                        ->orWhere('nim', 'LIKE', "%{$search}%")

                        // FIX 2: BATASI PENCARIAN KELOMPOK HANYA PADA EVENT AKTIF
                        // Mencegah Mahasiswa dari Event lain muncul
                        ->orWhereHas('kelompok', function ($kelompokQuery) use ($search, $activeEventId) {
                            $kelompokQuery
                                ->where('nama', 'LIKE', "%{$search}%")
                                ->where('event_id', $activeEventId);
                        });
                });
            })
            ->orderBy('nama')
            ->paginate(15)
            ->appends($request->except('page'));

        return view('admin.mahasiswa.index', compact('mahasiswas'));
    }

    /**
     * Menampilkan form untuk membuat mahasiswa baru
     */
    public function create()
    {
        $activeEventId = session('active_event_id');
        // Ambil kelompok dan alergi yang termasuk dalam event aktif saja
        $kelompoks = Kelompok::where('event_id', $activeEventId)->orderBy('nama')->get();
        // Ambil alergi berdasarkan tenant (bukan event)
        $alergis = Alergi::where('tenant_id', Auth::user()->tenant_id)->orderBy('nama')->get();

        return view('admin.mahasiswa.create', compact('kelompoks', 'alergis'));
    }

    /**
     * Menyimpan mahasiswa baru
     */
    public function store(Request $request)
    {
        $activeEventId = session('active_event_id');

        $validator = Validator::make($request->all(), [
            'nim' => 'required|string|unique:mahasiswas|max:20',
            'nama' => 'required|string|max:255',
            'prodi' => 'required|string',
            'kelompok_id' => 'required|exists:kelompoks,id',
            'no_urut' => 'required|integer',
            'is_vegan' => 'boolean',
            'alergis' => 'nullable|array',
            'alergis.*' => 'exists:alergis,id',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.mahasiswa.create')
                ->withErrors($validator)
                ->withInput();
        }

        $dataMahasiswa = $request->except('alergis');
        $dataMahasiswa['is_vegan'] = $request->has('is_vegan');
        $dataMahasiswa['event_id'] = $activeEventId; // <-- TAMBAHKAN EVENT ID

        $mahasiswa = new Mahasiswa($dataMahasiswa);
        $mahasiswa->save();

        $mahasiswa->alergi()->sync($request->input('alergis', []));

        return redirect()->route('admin.mahasiswa.index')
            ->with('success', 'Data mahasiswa berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail satu mahasiswa
     */
    public function show(Mahasiswa $mahasiswa)
    {
        $mahasiswa->load('alergi', 'kelompok.vendor');
        return view('admin.mahasiswa.show', compact('mahasiswa'));
    }

    /**
     * Menampilkan form untuk mengedit mahasiswa
     */
    public function edit(Mahasiswa $mahasiswa)
    {
        $activeEventId = session('active_event_id');
        // Pastikan mahasiswa ini dari event yang benar
        if ($mahasiswa->event_id != $activeEventId) {
            abort(404);
        }

        $kelompoks = Kelompok::where('event_id', $activeEventId)->orderBy('nama')->get();
        // Ambil alergi berdasarkan tenant (bukan event)
        $alergis = Alergi::where('tenant_id', Auth::user()->tenant_id)->orderBy('nama')->get();

        // Pastikan relasi alergi ter-load
        $mahasiswa->load('alergi');

        // Siapkan array id alergi agar view tidak error
        $mahasiswaAlergiIds = $mahasiswa->alergi->pluck('id')->toArray();

        return view('admin.mahasiswa.edit', compact('mahasiswa', 'kelompoks', 'alergis', 'mahasiswaAlergiIds'));
    }

    /**
     * Update data mahasiswa
     */
    public function update(Request $request, Mahasiswa $mahasiswa)
    {
        $activeEventId = session('active_event_id');
        // Pastikan mahasiswa ini dari event yang benar
        if ($mahasiswa->event_id != $activeEventId) {
            abort(404);
        }

        $validator = Validator::make($request->all(), [
            'nim' => 'required|string|max:20|unique:mahasiswas,nim,' . $mahasiswa->id,
            'nama' => 'required|string|max:255',
            'prodi' => 'required|string',
            'kelompok_id' => 'required|exists:kelompoks,id',
            'no_urut' => 'required|integer',
            'is_vegan' => 'boolean',
            'alergis' => 'nullable|array',
            'alergis.*' => 'exists:alergis,id',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.mahasiswa.edit', $mahasiswa->id)
                ->withErrors($validator)
                ->withInput();
        }

        $dataMahasiswa = $request->except('alergis');
        $dataMahasiswa['is_vegan'] = $request->has('is_vegan');

        $mahasiswa->update($dataMahasiswa);
        $mahasiswa->alergi()->sync($request->input('alergis', []));

        return redirect()->route('admin.mahasiswa.index')
            ->with('success', 'Data mahasiswa berhasil diperbarui.');
    }

    /**
     * Menghapus SATU data mahasiswa
     */
    public function destroy(Mahasiswa $mahasiswa)
    {
        // Hapus relasi detail distribusi dulu jika ada (manual cascade)
        DB::table('distribusi_details')->where('mahasiswa_id', $mahasiswa->id)->delete();

        $mahasiswa->delete();
        return redirect()->route('admin.mahasiswa.index')
            ->with('success', 'Data mahasiswa berhasil dihapus.');
    }

    /**
     * Menghapus SEMUA mahasiswa KECUALI Panitia (Fitur Reset)
     */
    public function destroyAll()
    {
        // 1. Ambil Daftar ID Mahasiswa yang BUKAN Panitia
        $idsToDelete = Mahasiswa::where('prodi', '!=', 'Panitia')->pluck('id');

        if ($idsToDelete->isEmpty()) {
            return redirect()->route('admin.mahasiswa.index')
                ->with('error', 'Tidak ada data mahasiswa biasa untuk dihapus.');
        }

        // 2. Hapus Data Relasi Terlebih Dahulu (Manual Cascade) untuk menghindari error Foreign Key

        // A. Hapus data di Pivot Alergi
        DB::table('mahasiswa_alergi')
            ->whereIn('mahasiswa_id', $idsToDelete)
            ->delete();

        // B. Hapus data di Detail Distribusi (Riwayat Makan/Checklist Kasir)
        // Ini wajib dihapus karena tabel ini punya foreign key ke mahasiswas
        DB::table('distribusi_details')
            ->whereIn('mahasiswa_id', $idsToDelete)
            ->delete();

        // 3. Setelah bersih, baru hapus Mahasiswanya
        $deletedCount = Mahasiswa::whereIn('id', $idsToDelete)->delete();

        return redirect()->route('admin.mahasiswa.index')
            ->with('success', "Berhasil mereset {$deletedCount} data mahasiswa. Data Panitia Inti aman.");
    }

    /**
     * Menampilkan form import
     */
    public function showImportForm()
    {
        return view('admin.mahasiswa.import');
    }

    /**
     * Proses import Excel
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        try {
            // Ganti dari import() menjadi queueImport()
            Excel::queueImport(new MahasiswaImport, $request->file('file'));

            return redirect()->route('admin.mahasiswa.index')
                ->with('success', 'Data mahasiswa sedang diimpor di background. Proses mungkin memakan waktu beberapa menit.');
        } catch (\Exception $e) {
            return redirect()->route('admin.mahasiswa.import.form')
                ->with('error', 'Terjadi kesalahan saat memulai proses import: ' . $e->getMessage());
        }
    }
}
