<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventarisLogistik extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_item',
        'stok_awal',
        'satuan',
    ];

    // Relasi: Satu item Inventaris memiliki banyak Log Penggunaan
    public function logPenggunaan()
    {
        return $this->hasMany(LogPenggunaanLogistik::class);
    }
}