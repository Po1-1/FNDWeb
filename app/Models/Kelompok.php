<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelompok extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id', // Tambahkan ini
        'nama',
        'vendor_id', // Vendor default untuk kelompok ini
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function mahasiswas()
    {
        return $this->hasMany(Mahasiswa::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function distribusi()
    {
        return $this->hasMany(Distribusi::class);
    }

    // Relasi Baru: Jadwal
    public function jadwal()
    {
        return $this->hasMany(JadwalKelompok::class);
    }

    // Helper untuk mengambil vendor pada waktu tertentu
    public function getVendorOn($hari, $waktu)
    {
        $jadwal = $this->jadwal()->where('hari_ke', $hari)->where('waktu_makan', $waktu)->first();
        // Jika ada jadwal spesifik, pakai itu. Jika tidak, pakai default vendor kelompok.
        return $jadwal ? $jadwal->vendor : $this->vendor; 
    }
}