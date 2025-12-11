<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Tenant; // <-- Tambahkan ini
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 0. Developer (Super Admin) - Tidak punya tenant
        User::create([
            'name' => 'Developer',
            'email' => 'dev@fnd.test',
            'password' => Hash::make('password'),
            'role' => 'developer',
            'email_verified_at' => now(),
        ]);

        // Buat Tenant Default untuk data yang sudah ada
        $defaultTenant = Tenant::create(['name' => 'FND Default']);

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
            'tenant_id' => $defaultTenant->id, // Kaitkan ke tenant
            'name' => 'Kasir FND',
            'email' => 'kasir@fnd.test',
            'password' => Hash::make('password'),
            'role' => 'kasir',
            'email_verified_at' => now(),
        ]);
        
        // Catatan: User Mentor akan dibuat di MahasiswaSeeder
    }
}