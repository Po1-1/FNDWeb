<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Makanan extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id', 
        'vendor_id',
        'nama_menu',
        'deskripsi',
        'bahan',
        'is_vegan',
        'image_path',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    // Relasi: Satu Makanan dimiliki oleh satu Vendor
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}