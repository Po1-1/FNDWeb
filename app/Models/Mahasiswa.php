<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'nim',
        'nama',
        'prodi',
        'kelompok_id',
        'no_urut',
        'is_vegan',
        'user_id',
        'custom_vendor_id', // Baru
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function alergi()
    {
        return $this->belongsToMany(Alergi::class, 'mahasiswa_alergi');
    }

    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class);
    }

    // Relasi Vendor Khusus
    public function customVendor()
    {
        return $this->belongsTo(Vendor::class, 'custom_vendor_id');
    }

    // Logika Penentuan Vendor (Penting!)
    public function getVendorFor($hari, $waktu)
    {
        // 1. Cek apakah anak ini punya vendor khusus? (Override)
        if ($this->custom_vendor_id) {
            return $this->customVendor;
        }

        // 2. Jika tidak, ambil vendor kelompok pada waktu tersebut
        return $this->kelompok->getVendorOn($hari, $waktu);
    }
}