<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_vendor',
        'kontak',
    ];

    // Relasi: Satu Vendor menyediakan banyak Makanan
    public function makanan()
    {
        return $this->hasMany(Makanan::class);
    }
    public function kelompoks()
    {
        return $this->hasMany(Kelompok::class);
    }
}