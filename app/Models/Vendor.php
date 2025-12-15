<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id', 
        'nama_vendor',
        'kontak',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    // Relasi: Satu Vendor bisa punya banyak Makanan
    public function makanans()
    {
        return $this->hasMany(Makanan::class);
    }
}