<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelompok extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'nama',
        // 'vendor_id' sudah tidak ada lagi
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function mahasiswas()
    {
        return $this->hasMany(Mahasiswa::class);
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
        // Jika ada jadwal spesifik, pakai vendor dari jadwal itu.
        return $jadwal ? $jadwal->vendor : null; 
    }

    public function vendor()
    {
        return $this->belongsTo(\App\Models\Vendor::class, 'vendor_id');
    }
}