<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbsensiDetail extends Model
{
    protected $fillable = ['absensi_id', 'mahasiswa_id', 'is_hadir', 'keterangan'];
}