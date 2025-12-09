<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Mahasiswa;

class MentorDashboardController extends Controller
{
    // Menampilkan dashboard mentor yang kini berfungsi sebagai halaman pencarian
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = $request->input('query');

        // Method `when()` akan otomatis menerapkan filter HANYA JIKA $query tidak kosong.
        // Jika $query kosong, query ini akan mengambil SEMUA mahasiswa.
        $results = Mahasiswa::query()
            ->with(['alergi', 'kelompok.vendor'])
            ->when($query, function ($q, $query) {
                $q->where(function ($q) use ($query) {
                    $q->where('nama', 'LIKE', "%{$query}%")
                        ->orWhere('nim', 'LIKE', "%{$query}%")
                        ->orWhereHas('kelompok', function ($kelompokQuery) use ($query) {
                            $kelompokQuery->where('nama', 'LIKE', "%{$query}%");
                        });
                });
            })
            ->orderBy('nama')
            ->paginate(15)
            ->appends($request->except('page'));

        // Kirim data ke view tanpa $mentorData
        return view('mentor.dashboard', compact('user', 'results', 'query'));
    }

    // Menampilkan data anggota kelompok mentor
    public function showKelompok()
    {
        // 1. Dapatkan data mentor (dari tabel mahasiswa)
        $mentorData = Mahasiswa::where('user_id', Auth::id())->firstOrFail();

        // 2. Dapatkan nama kelompoknya
        $kelompok = $mentorData->kelompok;
        if (!$kelompok) {
            return redirect()->route('mentor.dashboard')->with('error', 'Anda tidak terdaftar di kelompok manapun.');
        }

        // 3. Cari semua mahasiswa (termasuk mentor itu sendiri)
        //    yang ada di kelompok yang sama
        $anggotas = Mahasiswa::where('kelompok_id', $kelompok->id)
            ->with('alergi') // Eager load alergi
            ->orderBy('nama', 'asc')
            ->get();

        return view('mentor.kelompok', compact('anggotas', 'kelompok'));
    }

    // Method search() sudah tidak diperlukan lagi karena digabung ke index()
}
