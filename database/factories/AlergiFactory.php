<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Alergi>
 */
class AlergiFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nama' => fake()->word(),
            'deskripsi' => fake()->sentence(),
        ];
    }
}