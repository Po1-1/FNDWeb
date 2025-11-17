<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Event;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        Event::create([
            'nama_event' => 'FND Event 2025',
            'tanggal_mulai' => now()->addDays(10),
            'tanggal_selesai' => now()->addDays(17),
            'is_active' => true,
        ]);
    }
}