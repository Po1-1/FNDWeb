<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vendor>
 */
class VendorFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nama_vendor' => 'Vendor ' . fake()->company(),
            'kontak' => fake()->phoneNumber(),
        ];
    }
}