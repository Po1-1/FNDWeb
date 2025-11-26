<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DistribusiDetail extends Model
{
    protected $guarded = [];

    // Ini menyimpan snapshot vendor saat transaksi terjadi
    public function vendor() 
    { 
        return $this->belongsTo(Vendor::class, 'vendor_id_snapshot'); 
    }
}