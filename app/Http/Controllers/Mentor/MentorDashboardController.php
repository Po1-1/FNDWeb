<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\DistribusiBukti;
use App\Models\Event;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MentorDashboardController extends Controller
{
    private function getActiveEvent()
    {
        return Event::where('tenant_id', Auth::user()->tenant_id)
                      ->where('is_active', true)
                      ->first();
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $activeEvent = $this->getActiveEvent();

        if (!$activeEvent) {
            return view('mentor.dashboard-no-event');
        }

        // --- UBAH BAGIAN INI SAJA ---
        $mentorData = Mahasiswa::where('user_id', $user->id)
            ->where('event_id', $activeEvent->id)
            ->with('kelompok')
            ->first();
        // -------------------------

        $query = $request->input('query');

        // Method `when()` akan otomatis menerapkan filter HANYA JIKA $query tidak kosong.
        // Jika $query kosong, query ini akan mengambil SEMUA mahasiswa.
        $results = Mahasiswa::query()
            ->where('event_id', $activeEvent->id) // <-- Filter mahasiswa dari event aktif
            ->with(['alergi', 'kelompok.vendor'])
            ->when($query, function ($q, $query) {
                $q->where(function ($q) use ($query) {
                    $q->where('nama', 'LIKE', "%{$query}%")
                        ->orWhere('nim', 'LIKE', "%{$query}%");
                });
            })
            ->orderBy('nama')
            ->paginate(15)
            ->appends($request->except('page'));

        // --- UBAH COMPACT() UNTUK MENAMBAHKAN $mentorData ---
        return view('mentor.dashboard', compact('user', 'results', 'query', 'mentorData'));
    }

    // Menampilkan data anggota kelompok mentor (Halaman Info)
    public function showKelompok()
    {
        $user = Auth::user();
        $activeEvent = $this->getActiveEvent();
        if (!$activeEvent) return redirect()->route('mentor.dashboard')->with('error', 'Tidak ada event yang aktif.');

        $mentorData = Mahasiswa::where('user_id', $user->id)->where('event_id', $activeEvent->id)->first();

        if (!$mentorData || !$mentorData->kelompok_id) {
            return redirect()->route('mentor.dashboard')->with('error', 'Anda tidak terdaftar di kelompok manapun pada event ini.');
        }

        $kelompok = $mentorData->kelompok()->with('mahasiswas')->first();

        return view('mentor.kelompok.show', compact('user', 'mentorData', 'kelompok'));
    }

    // --- TAMBAHKAN METHOD BARU INI ---
    /**
     * Menampilkan halaman untuk mengelola bukti distribusi.
     */
    public function manageKelompok()
    {
        $user = Auth::user();
        $activeEvent = $this->getActiveEvent();
        if (!$activeEvent) return redirect()->route('mentor.dashboard')->with('error', 'Tidak ada event yang aktif.');

        $mentorData = Mahasiswa::where('user_id', $user->id)->where('event_id', $activeEvent->id)->first();

        if (!$mentorData || !$mentorData->kelompok_id) {
            return redirect()->route('mentor.dashboard')->with('error', 'Anda tidak terdaftar di kelompok manapun pada event ini.');
        }

        // Ambil data kelompok beserta anggota dan riwayat distribusi + buktinya
        $kelompok = $mentorData->kelompok()
            ->with([
                'mahasiswas',
                'distribusi' => function ($query) use ($activeEvent) {
                    $query->where('event_id', $activeEvent->id)
                        ->with('buktis')
                        ->orderBy('created_at', 'desc');
                }
            ])
            ->first();

        // Arahkan ke view baru: manage.blade.php
        return view('mentor.kelompok.manage', compact('user', 'mentorData', 'kelompok'));
    }

    /**
     * Menyimpan gambar bukti distribusi.
     */
    public function storeBukti(Request $request)
    {
        $request->validate([
            'distribusi_id' => 'required|exists:distribusis,id',
            'bukti_gambar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
            'catatan' => 'nullable|string|max:255',
        ]);

        // Simpan file gambar
        $path = $request->file('bukti_gambar')->store('public/bukti_distribusi');

        // Buat record di database
        DistribusiBukti::create([
            'distribusi_id' => $request->distribusi_id,
            'image_path' => $path,
            'catatan' => $request->catatan,
        ]);

        return back()->with('success', 'Bukti berhasil di-upload.');
    }

    /**
     * Menghapus gambar bukti distribusi.
     */
    public function destroyBukti(DistribusiBukti $bukti)
    {
        // Hapus file dari storage
        Storage::delete($bukti->image_path);

        // Hapus record dari database
        $bukti->delete();

        return back()->with('success', 'Bukti berhasil dihapus.');
    }
}
