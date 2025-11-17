<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogPenggunaanLogistik extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventaris_logistik_id',
        'user_id',
        'jumlah_digunakan',
        'tanggal_penggunaan',
        'catatan', // <-- TAMBAHKAN INI
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'tanggal_penggunaan' => 'date',
    ];

    // Relasi: Log ini milik satu item InventarisLogistik
    public function inventarisLogistik()
    {
        return $this->belongsTo(InventarisLogistik::class);
    }

    // Relasi: Log ini dicatat oleh satu User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}