<?php

namespace App\Models;

use App\Models\Traits\BelongsToTenant; 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory, BelongsToTenant; 

    
    protected $fillable = [
        'tenant_id',
        'nama_event',
        'tanggal_mulai',
        'tanggal_selesai',
        'is_active',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'is_active' => 'boolean',
    ];


    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

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