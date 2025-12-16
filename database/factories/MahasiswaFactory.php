<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Kelompok;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Mahasiswa>
 */
class MahasiswaFactory extends Factory
{
    public function definition(): array
    {
        $prodi = fake()->randomElement(['Teknik Informatika', 'Sistem Informasi', 'DKV', 'Panitia']);

        // Ambil ID Kelompok secara acak dari database
        // Ini mengasumsikan KelompokSeeder sudah berjalan
        // Kita filter agar tidak mengambil "Panitia Inti" secara acak
        $kelompokId = Kelompok::where('nama', '!=', 'Panitia Inti')
            ->inRandomOrder()
            ->first()
            ->id;

        return [
            'nim' => '110' . fake()->unique()->numberBetween(1000, 9999),
            'nama' => fake()->name(),
            'prodi' => $prodi,
            'kelompok_id' => $kelompokId, 
            'no_urut' => fake()->numberBetween(1, 100),
            'is_vegan' => fake()->boolean(10), 
            'user_id' => null, 
        ];
    }
}
