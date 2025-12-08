<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Mahasiswa;

class MentorDashboardController extends Controller
{
    // Menampilkan dashboard mentor
    public function index()
    {
        // Mentor login sebagai User. Kita perlu data Mahasiswa-nya.
        $user = Auth::user();

        // Asumsi relasi user() di Model Mahasiswa sudah di-set
        $mentorData = Mahasiswa::where('user_id', $user->id)->first();

        if (!$mentorData) {
            // Handle jika data mentor (di tabel mhs) tidak ditemukan
            abort(404, 'Data Mentor tidak ditemukan.');
        }

        return view('mentor.dashboard', compact('user', 'mentorData'));
    }

    // Menampilkan data anggota kelompok mentor
    public function showKelompok()
    {
        // 1. Dapatkan data mentor (dari tabel mahasiswa)
        $mentorData = Mahasiswa::where('user_id', Auth::id())->firstOrFail();

        // 2. Dapatkan nama kelompoknya
        $kelompok = $mentorData->kelompok;

        // 3. Cari semua mahasiswa (termasuk mentor itu sendiri)
        //    yang ada di kelompok yang sama
        $anggotas = Mahasiswa::where('kelompok', $kelompok)
            ->with('alergi') // Eager load alergi
            ->orderBy('nama', 'asc')
            ->get();

        return view('mentor.kelompok', compact('anggotas', 'kelompok'));
    }

    /**
     * Fitur Pencarian Khusus Mentor
     */
    public function search(Request $request)
    {
        $query = $request->input('query');

        $mahasiswas = Mahasiswa::query()
            ->with(['alergi', 'kelompok.vendor'])
            ->when($query, function ($q, $query) {
                $q->where('nama', 'LIKE', "%{$query}%")
                    ->orWhere('nim', 'LIKE', "%{$query}%")
                    ->orWhereHas('kelompok', function ($kelompokQuery) use ($query) {
                        $kelompokQuery->where('nama', 'LIKE', "%{$query}%");
                    });
            })
            ->orderBy('nama')
            ->paginate(15)
            ->appends($request->except('page'));

        // Kita gunakan view baru khusus mentor (atau reuse view lama dengan layout berbeda)
        return view('mentor.search-results', [
            'results' => $mahasiswas,
            'query' => $query,
        ]);
    }
}
