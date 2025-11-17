<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Alergi;

class AlergiSeeder extends Seeder
{
    public function run(): void
    {
        $alergi = [
            ['nama' => 'Kacang', 'deskripsi' => 'Alergi terhadap semua jenis kacang.'],
            ['nama' => 'Seafood', 'deskripsi' => 'Alergi terhadap udang, kepiting, ikan laut.'],
            ['nama' => 'Telur', 'deskripsi' => 'Alergi terhadap telur ayam.'],
            ['nama' => 'Susu', 'deskripsi' => 'Alergi laktosa atau produk turunan susu.'],
            ['nama' => 'Gluten', 'deskripsi' => 'Alergi terhadap tepung/gandum.'],
        ];

        foreach ($alergi as $a) {
            Alergi::create($a);
        }
    }
}