<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kelompok;
use App\Models\Vendor;

class KelompokSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil ID Vendor A dan B (Contoh)
        $vendorA = Vendor::where('nama_vendor', 'LIKE', '%Vendor A%')->first();
        $vendorB = Vendor::where('nama_vendor', 'LIKE', '%Vendor B%')->first();
        $vendorC = Vendor::where('nama_vendor', 'LIKE', '%Vendor C%')->first();
        $vendorD = Vendor::where('nama_vendor', 'LIKE', '%Vendor D%')->first();

        // Buat data kelompok dengan relasi ID
        Kelompok::create(['nama' => 'Kelompok 1', 'vendor_id' => $vendorA->id ?? null]);
        Kelompok::create(['nama' => 'Kelompok 2', 'vendor_id' => $vendorB->id ?? null]);
        Kelompok::create(['nama' => 'Kelompok 3', 'vendor_id' => $vendorC->id ?? null]);
        Kelompok::create(['nama' => 'Kelompok 4', 'vendor_id' => $vendorD->id ?? null]);
        Kelompok::create(['nama' => 'Panitia Inti', 'vendor_id' => null]);
    }
}