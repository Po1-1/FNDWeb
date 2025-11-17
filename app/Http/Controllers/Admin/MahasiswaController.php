<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Kelompok; // Diperlukan untuk dropdown
use App\Models\Alergi;   // Diperlukan untuk checkbox
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; // Digunakan untuk validasi store()

// Untuk fitur Import
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
        $search = $request->input('search');
        
        $mahasiswas = Mahasiswa::query()
            // Eager load relasi untuk efisiensi
            ->with(['kelompok', 'alergi']) 
            
            // Logika Pencarian
            ->when($search, function ($q, $search) {
                $q->where('nama', 'LIKE', "%{$search}%")
                  ->orWhere('nim', 'LIKE', "%{$search}%")
                  // Mencari berdasarkan nama di relasi kelompok
                  ->orWhereHas('kelompok', function ($kelompokQuery) use ($search) {
                      $kelompokQuery->where('nama', 'LIKE', "%{$search}%");
                  });
            })
            ->orderBy('updated_at', 'desc')
            ->paginate(15)
            // Menjaga parameter search saat pindah halaman paginasi
            ->appends($request->except('page'));
                            
        return view('admin.mahasiswa.index', compact('mahasiswas'));
    }

    /**
     * Menampilkan form untuk membuat mahasiswa baru
     */
    public function create()
    {
        // Ambil data untuk mengisi dropdown dan checkbox
        $kelompoks = Kelompok::orderBy('nama')->get();
        $alergis = Alergi::orderBy('nama')->get();
        
        return view('admin.mahasiswa.create', compact('kelompoks', 'alergis'));
    }

    /**
     * Menyimpan mahasiswa baru (Validation & Form Insert)
     */
    public function store(Request $request)
    {
        // 1. Validasi
        $validator = Validator::make($request->all(), [
            'nim' => 'required|string|unique:mahasiswas|max:20',
            'nama' => 'required|string|max:255',
            'prodi' => 'required|string',
            'kelompok_id' => 'required|exists:kelompoks,id', // Validasi dropdown
            'no_urut' => 'required|integer',
            'is_vegan' => 'boolean',
            'alergis' => 'nullable|array', // Validasi checkbox alergi
            'alergis.*' => 'exists:alergis,id', 
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.mahasiswa.create')
                        ->withErrors($validator)
                        ->withInput();
        }

        // 2. Insert Data
        // Pisahkan data alergi dari data mahasiswa
        $dataMahasiswa = $request->except('alergis');
        // Handle checkbox 'is_vegan'
        $dataMahasiswa['is_vegan'] = $request->has('is_vegan');

        $mahasiswa = new Mahasiswa($dataMahasiswa);
        $mahasiswa->save(); // Simpan mahasiswa

        // 3. Sync Alergi (Relasi Many-to-Many)
        // 'sync' akan menambah data ke tabel pivot 'mahasiswa_alergi'
        $mahasiswa->alergi()->sync($request->input('alergis', []));

        return redirect()->route('admin.mahasiswa.index')
                         ->with('success', 'Data mahasiswa berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail satu mahasiswa (Route Model Binding)
     */
    public function show(Mahasiswa $mahasiswa)
    {
        // Load relasi yang diperlukan
        $mahasiswa->load('alergi', 'kelompok.vendor');
        
        return view('admin.mahasiswa.show', compact('mahasiswa'));
    }

    /**
     * Menampilkan form untuk mengedit mahasiswa
     */
    public function edit(Mahasiswa $mahasiswa)
    {
        $kelompoks = Kelompok::orderBy('nama')->get();
        $alergis = Alergi::orderBy('nama')->get();
        
        // Ambil ID alergi yang sudah dimiliki mahasiswa ini untuk 'checked'
        $mahasiswaAlergiIds = $mahasiswa->alergi->pluck('id')->toArray();

        return view('admin.mahasiswa.edit', compact(
            'mahasiswa', 
            'kelompoks', 
            'alergis', 
            'mahasiswaAlergiIds'
        ));
    }

    /**
     * Update data mahasiswa
     */
    public function update(Request $request, Mahasiswa $mahasiswa)
    {
        // 1. Validasi
        $request->validate([
            'nim' => 'required|string|max:20|unique:mahasiswas,nim,' . $mahasiswa->id,
            'nama' => 'required|string|max:255',
            'prodi' => 'required|string',
            'kelompok_id' => 'required|exists:kelompoks,id',
            'no_urut' => 'required|integer',
            'is_vegan' => 'boolean',
            'alergis' => 'nullable|array',
            'alergis.*' => 'exists:alergis,id',
        ]);

        // 2. Update data Mahasiswa
        $dataMahasiswa = $request->except('alergis');
        $dataMahasiswa['is_vegan'] = $request->has('is_vegan');
        
        $mahasiswa->update($dataMahasiswa);

        // 3. Sync Alergi
        // 'sync' akan otomatis update: menambah yg baru, menghapus yg di-uncheck
        $mahasiswa->alergi()->sync($request->input('alergis', []));

        return redirect()->route('admin.mahasiswa.index')
                         ->with('success', 'Data mahasiswa berhasil diperbarui.');
    }

    /**
     * Menghapus data mahasiswa
     */
    public function destroy(Mahasiswa $mahasiswa)
    {
        $mahasiswa->delete();
        return redirect()->route('admin.mahasiswa.index')
                         ->with('success', 'Data mahasiswa berhasil dihapus.');
    }
    
    /**
     * Menampilkan halaman/form untuk upload file.
     */
    public function showImportForm()
    {
        return view('admin.mahasiswa.import');
    }

    /**
     * Menjalankan proses import dari file Excel.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        try {
            Excel::import(new MahasiswaImport, $request->file('file'));
            
            return redirect()->route('admin.mahasiswa.index')
                             ->with('success', 'Data mahasiswa berhasil diimpor.');

        } catch (ValidationException $e) {
            $failures = $e->failures();
            $errors = [];
            foreach ($failures as $failure) {
                $errors[] = "Baris " . $failure->row() . ": " . implode(", ", $failure->errors()) . " (Nilai: '" . $failure->values()[$failure->attribute()] . "')";
            }
            return redirect()->route('admin.mahasiswa.import.form')
                             ->with('error', 'Gagal mengimpor data. Perbaiki error berikut:')
                             ->with('validation_errors', $errors);
        } catch (\Exception $e) {
            return redirect()->route('admin.mahasiswa.import.form')
                             ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}