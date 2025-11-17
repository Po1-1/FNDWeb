<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Makanan>
 */
class MakananFactory extends Factory
{
    public function definition(): array
    {
        // Contoh bahan untuk relasi alergi
        $bahan = fake()->randomElement([
            'Ayam, Nasi, Sayur, Kacang',
            'Ikan, Nasi, Tahu, Tempe',
            'Telur, Nasi, Udang, Kerupuk',
            'Susu, Roti, Keju, Daging Sapi',
            'Tepung, Sayuran, Jamur (Vegan)'
        ]);
        
        $isVegan = str_contains($bahan, 'Vegan');

        return [
            'vendor_id' => \App\Models\Vendor::factory(), // Otomatis membuat vendor baru
            'nama_menu' => 'Menu ' . fake()->word(),
            'deskripsi' => fake()->sentence(),
            'bahan' => $bahan,
            'is_vegan' => $isVegan,
        ];
    }
}