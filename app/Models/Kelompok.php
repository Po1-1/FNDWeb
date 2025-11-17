<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelompok extends Model
{
    protected $fillable = [
        'nama',
        'vendor_id',
    ];

    // Relasi: Satu Kelompok punya banyak Mahasiswa
    public function mahasiswas()
    {
        return $this->hasMany(Mahasiswa::class);
    }
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
