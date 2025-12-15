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

            // PENCEGAHAN DUPLIKASI QUERY
            ->select('mahasiswas.*')
            ->distinct('mahasiswas.id')

            ->when($search, function ($q, $search) use ($activeEventId) {
                $q->where(function ($subQ) use ($search, $activeEventId) { // Menggunakan subQ untuk grouping OR conditions

                    $subQ->where('nama', 'LIKE', "%{$search}%")
                        ->orWhere('nim', 'LIKE', "%{$search}%")

                        // BATASI PENCARIAN KELOMPOK HANYA PADA EVENT AKTIF
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

    public function create()
    {
        $activeEventId = session('active_event_id');
        $kelompoks = Kelompok::where('event_id', $activeEventId)->orderBy('nama')->get();
        $alergis = Alergi::where('tenant_id', Auth::user()->tenant_id)->orderBy('nama')->get();

        return view('admin.mahasiswa.create', compact('kelompoks', 'alergis'));
    }
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
        $dataMahasiswa['event_id'] = $activeEventId;

        $mahasiswa = new Mahasiswa($dataMahasiswa);
        $mahasiswa->save();

        $mahasiswa->alergi()->sync($request->input('alergis', []));

        return redirect()->route('admin.mahasiswa.index')
            ->with('success', 'Data mahasiswa berhasil ditambahkan.');
    }

    public function show(Mahasiswa $mahasiswa)
    {
        $mahasiswa->load('alergi', 'kelompok.vendor');
        return view('admin.mahasiswa.show', compact('mahasiswa'));
    }

    public function edit(Mahasiswa $mahasiswa)
    {
        $activeEventId = session('active_event_id');
        if ($mahasiswa->event_id != $activeEventId) {
            abort(404);
        }

        $kelompoks = Kelompok::where('event_id', $activeEventId)->orderBy('nama')->get();
        $alergis = Alergi::where('tenant_id', Auth::user()->tenant_id)->orderBy('nama')->get();

        $mahasiswa->load('alergi');
        $mahasiswaAlergiIds = $mahasiswa->alergi->pluck('id')->toArray();

        return view('admin.mahasiswa.edit', compact('mahasiswa', 'kelompoks', 'alergis', 'mahasiswaAlergiIds'));
    }

    public function update(Request $request, Mahasiswa $mahasiswa)
    {
        $activeEventId = session('active_event_id');
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
    public function destroy(Mahasiswa $mahasiswa)
    {
        // Hapus relasi detail distribusi dulu jika ada 
        DB::table('distribusi_details')->where('mahasiswa_id', $mahasiswa->id)->delete();

        $mahasiswa->delete();
        return redirect()->route('admin.mahasiswa.index')
            ->with('success', 'Data mahasiswa berhasil dihapus.');
    }

    public function destroyAll()
    {
        // Ambil Daftar ID Mahasiswa yang BUKAN Panitia
        $idsToDelete = Mahasiswa::where('prodi', '!=', 'Panitia')->pluck('id');

        if ($idsToDelete->isEmpty()) {
            return redirect()->route('admin.mahasiswa.index')
                ->with('error', 'Tidak ada data mahasiswa biasa untuk dihapus.');
        }


        // Hapus data di Pivot Alergi
        DB::table('mahasiswa_alergi')
            ->whereIn('mahasiswa_id', $idsToDelete)
            ->delete();

        // Hapus data di Detail Distribusi 
        DB::table('distribusi_details')
            ->whereIn('mahasiswa_id', $idsToDelete)
            ->delete();

        // Setelah bersih, baru hapus Mahasiswanya
        $deletedCount = Mahasiswa::whereIn('id', $idsToDelete)->delete();

        return redirect()->route('admin.mahasiswa.index')
            ->with('success', "Berhasil mereset {$deletedCount} data mahasiswa. Data Panitia Inti aman.");
    }
    public function showImportForm()
    {
        return view('admin.mahasiswa.import');
    }
    public function import(Request $request)
    {
        set_time_limit(500);

        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        try {
            Excel::queueImport(new MahasiswaImport, $request->file('file'));

            return redirect()->route('admin.mahasiswa.index')
                ->with('success', 'Data mahasiswa sedang diimpor di background. Proses mungkin memakan waktu beberapa menit.');
        } catch (\Exception $e) {
            return redirect()->route('admin.mahasiswa.import.form')
                ->with('error', 'Terjadi kesalahan saat memulai proses import: ' . $e->getMessage());
        }
    }
}
