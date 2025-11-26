<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Distribusi extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'user_id',
        'kelompok_id',
        'tipe',
        'hari_ke',      // Baru
        'waktu_makan',  // Baru
        'jumlah_pengambilan',
        'catatan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class);
    }

    // Relasi ke Detail (Anak-anak yang makan)
    public function details()
    {
        return $this->hasMany(DistribusiDetail::class);
    }
}