<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalKelompok extends Model
{
    protected $guarded = []; 

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}