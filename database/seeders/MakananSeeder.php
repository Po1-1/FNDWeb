<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Vendor;
use App\Models\Makanan;

class MakananSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil semua vendor
        $vendors = Vendor::all();

        if ($vendors->isEmpty()) {
            $this->command->error('Tidak ada Vendor. Jalankan VendorSeeder terlebih dahulu.');
            return;
        }

        // Buat 10 Menu
        Makanan::factory(10)->create([
            // Override vendor_id agar menggunakan vendor yang sudah ada
            'vendor_id' => $vendors->random()->id,
        ]);

        // Buat 2 Menu Vegan khusus
        Makanan::factory(2)->create([
            'vendor_id' => $vendors->random()->id,
            'nama_menu' => 'Paket Nasi Vegan',
            'bahan' => 'Nasi, Tempe Bacem, Tahu, Sayur Bening, Jamur Krispi',
            'is_vegan' => true,
        ]);
    }
}