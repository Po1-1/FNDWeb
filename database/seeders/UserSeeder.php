<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@fnd.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // 2. Kasir
        User::create([
            'name' => 'Kasir User',
            'email' => 'kasir@fnd.test',
            'password' => Hash::make('password'),
            'role' => 'kasir',
            'email_verified_at' => now(),
        ]);
        
        // Catatan: User Mentor akan dibuat di MahasiswaSeeder
    }
}