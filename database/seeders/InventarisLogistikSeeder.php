<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\InventarisLogistik;

class InventarisLogistikSeeder extends Seeder
{
    public function run(): void
    {
        InventarisLogistik::create([
            'nama_item' => 'Galon Air',
            'stok_awal' => 100,
            'satuan' => 'galon'
        ]);
        
        InventarisLogistik::create([
            'nama_item' => 'Plastik Sampah (Roll)',
            'stok_awal' => 200,
            'satuan' => 'roll'
        ]);

        InventarisLogistik::create([
            'nama_item' => 'Botol Minum 600ml',
            'stok_awal' => 500,
            'satuan' => 'pcs'
        ]);
    }
}