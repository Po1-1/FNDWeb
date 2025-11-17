<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InventarisLogistik>
 */
class InventarisLogistikFactory extends Factory
{
    public function definition(): array
    {
        $item = fake()->randomElement(['Galon Air', 'Plastik Sampah', 'Botol Minum']);
        $satuan = ($item == 'Galon Air') ? 'galon' : (($item == 'Plastik Sampah') ? 'roll' : 'pcs');

        return [
            'nama_item' => $item,
            'stok_awal' => fake()->numberBetween(100, 500),
            'satuan' => $satuan,
        ];
    }
}