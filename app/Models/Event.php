<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    // Nama tabel adalah 'events' (plural), jadi tidak perlu properti $table
    
    protected $fillable = [
        'nama_event',
        'tanggal_mulai',
        'tanggal_selesai',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'is_active' => 'boolean',
    ];

    // Relasi: Satu Event memiliki banyak log Distribusi
    public function distribusi()
    {
        return $this->hasMany(Distribusi::class);
    }

    // Relasi: Satu Event memiliki banyak data EventSummary (per hari)
    public function eventSummary()
    {
        return $this->hasMany(EventSummary::class);
    }
}