<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Mahasiswa;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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

        // 1. Hitung Hari Ke-
        $startDate = $activeEvent->tanggal_mulai instanceof Carbon ? $activeEvent->tanggal_mulai : Carbon::parse($activeEvent->tanggal_mulai);
        $startDate = $startDate->startOfDay();
        $today = Carbon::now()->startOfDay();
        
        $hariKe = ($startDate->year < 2000) ? 1 : ((int) $startDate->diffInDays($today, false) + 1);
        if ($hariKe < 1) $hariKe = 1;

        // 2. Cek Status & Jumlah Absensi Hari Ini
        $mentorProfile = Mahasiswa::where('user_id', $user->id)
            ->where('event_id', $activeEvent->id)
            ->first();
        
        $assignedKelompokId = $mentorProfile ? $mentorProfile->kelompok_id : null;
        
        // Struktur data baru: menyimpan status boolean DAN jumlah hadir
        $summaryAbsensi = [
            'pagi' => ['filled' => false, 'hadir' => 0, 'total' => 0],
            'siang' => ['filled' => false, 'hadir' => 0, 'total' => 0],
            'sore' => ['filled' => false, 'hadir' => 0, 'total' => 0],
            'malam' => ['filled' => false, 'hadir' => 0, 'total' => 0],
        ];

        if ($assignedKelompokId) {
            foreach ($summaryAbsensi as $waktu => $val) {
                $absensi = Absensi::where('event_id', $activeEvent->id)
                    ->where('kelompok_id', $assignedKelompokId)
                    ->where('hari_ke', $hariKe)
                    ->where('waktu_makan', $waktu)
                    ->withCount(['details as hadir_count' => function ($query) {
                        $query->where('is_hadir', true);
                    }])
                    ->withCount('details as total_count')
                    ->first();

                if ($absensi) {
                    $summaryAbsensi[$waktu] = [
                        'filled' => true,
                        'hadir' => $absensi->hadir_count,
                        'total' => $absensi->total_count
                    ];
                }
            }
        }

        return view('mentor.dashboard', compact('user', 'activeEvent', 'hariKe', 'summaryAbsensi', 'assignedKelompokId'));
    }
}
