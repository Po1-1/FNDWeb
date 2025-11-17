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
        'kelompok_id',      // <-- DIUBAH
        'tipe',
        'jumlah_pengambilan', // <-- BARU
        'catatan',
    ];

    // Relasi: Satu log Distribusi dicatat oleh satu User (Kasir)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: Satu log Distribusi milik satu Event
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class);
    }
}