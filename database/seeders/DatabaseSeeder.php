<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Panggil Seeder untuk data dasar yang tidak punya relasi
        $this->call([
            UserSeeder::class,         // Admin & Kasir
            AlergiSeeder::class,       // Data alergi
            VendorSeeder::class,       // Data vendor
            InventarisLogistikSeeder::class, // Data logistik
            EventSeeder::class,        // Data event
            KelompokSeeder::class,     // Data kelompok
        ]);

        // Panggil Seeder yang punya relasi
        $this->call([
            MakananSeeder::class,      // Butuh Vendor
            MahasiswaSeeder::class,    // Butuh User (Mentor) & Alergi
        ]);
        
    }
}