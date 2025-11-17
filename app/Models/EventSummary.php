<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventSummary extends Model
{
    use HasFactory;


    protected $fillable = [
        'event_id',
        'tanggal_summary',
        'sisa_galon',
        'rekap_penggunaan_logistik',
        'vendor_bertugas_hari_ini',
        'rekap_penggunaan_makanan', // <-- BARU
        'catatan_harian',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'tanggal_summary' => 'date',
        'rekap_penggunaan_logistik' => 'array',
        'vendor_bertugas_hari_ini' => 'array',
        'rekap_penggunaan_makanan' => 'array',
    ];

    // Relasi: Summary ini milik satu Event
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}