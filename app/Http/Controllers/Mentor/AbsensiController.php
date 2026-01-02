<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Kelompok;
use App\Models\Absensi;
use App\Models\AbsensiDetail;
use App\Models\Mahasiswa; // <--- Tambahkan Import Mahasiswa
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    public function create(Request $request)
    {
        $user = Auth::user();
        $activeEvent = Event::where('tenant_id', $user->tenant_id)
            ->where('is_active', true)
            ->first();

        if (!$activeEvent) {
            return redirect()->route('mentor.dashboard')->with('error', 'Tidak ada event aktif.');
        }

        // 1. Hitung Hari Ke- (Default Otomatis)
        $startDate = $activeEvent->tanggal_mulai instanceof Carbon ? $activeEvent->tanggal_mulai : Carbon::parse($activeEvent->tanggal_mulai);
        $startDate = $startDate->startOfDay();
        $today = Carbon::now()->startOfDay();
        
        $hariKe = ($startDate->year < 2000) ? 1 : ((int) $startDate->diffInDays($today, false) + 1);
        if ($hariKe < 1) $hariKe = 1;

        // OVERRIDE: Jika ada parameter dari URL (klik dashboard), pakai itu
        if ($request->has('hari_ke')) {
            $hariKe = $request->hari_ke;
        }

        // 2. Tentukan Waktu Makan (Default Otomatis)
        $hour = Carbon::now()->hour;
        $waktuMakan = 'pagi';
        if ($hour >= 10 && $hour < 15) $waktuMakan = 'siang';
        elseif ($hour >= 15 && $hour < 18) $waktuMakan = 'sore';
        elseif ($hour >= 18) $waktuMakan = 'malam';

        // OVERRIDE: Jika ada parameter dari URL (klik dashboard), pakai itu
        if ($request->has('waktu_makan')) {
            $waktuMakan = $request->waktu_makan;
        }

        // --- LOGIKA BARU: DETEKSI KELOMPOK MENTOR ---
        // Cari data mahasiswa yang terhubung dengan user ini di event aktif
        $mentorProfile = Mahasiswa::where('user_id', $user->id)
            ->where('event_id', $activeEvent->id)
            ->first();

        $assignedKelompokId = $mentorProfile ? $mentorProfile->kelompok_id : null;

        // Jika Mentor punya kelompok tetap, kunci pilihannya
        if ($assignedKelompokId) {
            $kelompoks = Kelompok::where('id', $assignedKelompokId)->get();
            
            // Paksa request untuk menggunakan ID ini jika belum ada request
            if (!$request->has('kelompok_id')) {
                $request->merge(['kelompok_id' => $assignedKelompokId]);
            }
        } else {
            // Jika akun ini tidak terhubung ke mahasiswa manapun (misal admin testing), tampilkan semua
            $kelompoks = Kelompok::where('event_id', $activeEvent->id)->orderBy('nama')->get();
        }
        // ---------------------------------------------

        $selectedKelompok = null;
        // Ambil ID dari request (yang mungkin sudah kita merge di atas)
        if ($request->has('kelompok_id')) {
            $selectedKelompok = Kelompok::with('mahasiswas')->find($request->kelompok_id);
        }

        return view('mentor.absensi.create', compact('activeEvent', 'hariKe', 'waktuMakan', 'kelompoks', 'selectedKelompok', 'assignedKelompokId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kelompok_id' => 'required',
            'hari_ke' => 'required',
            'waktu_makan' => 'required',
            'kehadiran' => 'array'
        ]);

        $activeEvent = Event::where('tenant_id', Auth::user()->tenant_id)->where('is_active', true)->first();

        // Simpan Header
        $absensi = Absensi::updateOrCreate(
            [
                'event_id' => $activeEvent->id,
                'kelompok_id' => $request->kelompok_id,
                'hari_ke' => $request->hari_ke,
                'waktu_makan' => $request->waktu_makan,
            ],
            ['mentor_id' => Auth::id()]
        );

        // Simpan Detail (Hapus yang lama dulu biar bersih)
        $absensi->details()->delete();

        $kelompok = Kelompok::find($request->kelompok_id);
        foreach ($kelompok->mahasiswas as $mhs) {
            $isHadir = isset($request->kehadiran[$mhs->id]);
            AbsensiDetail::create([
                'absensi_id' => $absensi->id,
                'mahasiswa_id' => $mhs->id,
                'is_hadir' => $isHadir,
                'keterangan' => $isHadir ? 'Hadir' : 'Absen'
            ]);
        }

        return redirect()->route('mentor.dashboard')->with('success', 'Absensi berhasil disimpan.');
    }
}