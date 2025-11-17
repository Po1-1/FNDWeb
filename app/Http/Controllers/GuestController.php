<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa; // Pastikan Anda mengimpor Model Mahasiswa

class GuestController extends Controller
{
    /**
     * Menampilkan halaman utama (welcome/home).
     */
    public function index()
    {
        // View 'welcome.blade.php' adalah halaman utama publik Anda
        return view('welcome'); 
    }

    /**
     * Menampilkan halaman "Apa itu FND".
     */
    public function about()
    {
        // Anda perlu membuat file view ini
        return view('public.about');
    }

    /**
     * Menangani logika pencarian publik.
     * Mencari berdasarkan NIM, Nama, atau Nama Kelompok.
     */
    public function search(Request $request)
    {
        $query = $request->input('query');
        
        // Mulai query pada tabel Mahasiswa
        $mahasiswas = Mahasiswa::query()
            // Eager load relasi yang kita perlukan untuk ditampilkan
            ->with(['alergi', 'kelompok.vendor'])
            
            // Lakukan pencarian jika $query tidak kosong
            ->when($query, function ($q, $query) {
                $q->where('nama', 'LIKE', "%{$query}%")
                  ->orWhere('nim', 'LIKE', "%{$query}%")
                  
                  // LOGIKA BARU: Cari di dalam relasi 'kelompok'
                  // 'orWhereHas' akan mencari mahasiswa YANG MEMILIKI
                  // kelompok dengan nama yang cocok
                  ->orWhereHas('kelompok', function ($kelompokQuery) use ($query) {
                      $kelompokQuery->where('nama', 'LIKE', "%{$query}%");
                  });
            })
            
            // Urutkan berdasarkan nama
            ->orderBy('nama')
            
            // Paginasi hasil
            ->paginate(15)
            
            // PENTING: Tambahkan query string pencarian ke link paginasi
            ->appends($request->except('page'));

        // Kirim hasil ke view
        return view('public.search-results', [
            'results' => $mahasiswas,
            'query' => $query,
        ]);
    }
}