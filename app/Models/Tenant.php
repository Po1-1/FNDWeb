<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    /**
     * Relasi: Satu Tenant memiliki banyak User (admin, mentor, kasir).
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}