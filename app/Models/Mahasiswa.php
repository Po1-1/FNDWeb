<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'nim',
        'nama',
        'prodi',
        'kelompok_id',
        'set_vendor',
        'no_urut',
        'is_vegan',
        'user_id',
    ];

    // Relasi: Satu Mahasiswa (Mentor) memiliki satu akun User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: Satu Mahasiswa bisa punya banyak Alergi (Many-to-Many)
    public function alergi()
    {
        return $this->belongsToMany(Alergi::class, 'mahasiswa_alergi');
    }

    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class);
    }
}