<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'nim',
        'nama',
        'prodi',
        'kelompok_id',
        'no_urut',
        'is_vegan',
        'user_id',
        'custom_vendor_id', 
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

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

    // Logika Penentuan Vendor 
    public function getVendorFor($hari, $waktu)
    {
        // Cek apakah mahasiswa ini punya override vendor khusus.
        if ($this->custom_vendor_id) {
            return $this->customVendor;
        }

        // Menggunakan jadwal default dari kelompoknya.
        if ($this->kelompok) {
            return $this->kelompok->getVendorOn($hari, $waktu);
        }

        // Jika mahasiswa bahkan tidak punya kelompok, kembalikan null.
        return null;
    }
}
