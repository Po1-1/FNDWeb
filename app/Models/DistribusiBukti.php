<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DistribusiBukti extends Model
{
    use HasFactory;

    protected $fillable = [
        'distribusi_id',
        'image_path',
        'catatan',
    ];

    public function distribusi()
    {
        return $this->belongsTo(Distribusi::class);
    }
}
