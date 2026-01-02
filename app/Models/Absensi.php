<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $fillable = ['event_id', 'kelompok_id', 'mentor_id', 'hari_ke', 'waktu_makan'];

    public function details()
    {
        return $this->hasMany(AbsensiDetail::class);
    }
}