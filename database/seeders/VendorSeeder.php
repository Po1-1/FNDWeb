<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Vendor;

class VendorSeeder extends Seeder
{
    public function run(): void
    {
        // Membuat 5 Vendor
        Vendor::factory(5)->create();
    }
}