<?php

namespace App\Models;

use App\Models\Traits\BelongsToTenant; // <-- Gunakan Trait ini
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alergi extends Model
{
    use HasFactory, BelongsToTenant; // <-- Terapkan Trait

    protected $fillable = [
        'tenant_id', // <-- Ganti dari event_id
        'nama',
        'deskripsi',
    ];

    // Hapus relasi event(), ganti dengan tenant() dari Trait
    // public function event() { ... }

    public function mahasiswa()
    {
        return $this->belongsToMany(Mahasiswa::class, 'mahasiswa_alergi');
    }
}