<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Mahasiswa;
use App\Models\User;
use App\Models\Alergi;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Kelompok;

class MahasiswaSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Membuat data Mahasiswa...');

        // Ambil semua ID alergi yang ada
        $alergiIds = Alergi::pluck('id');

        // 1. Buat 100 Mahasiswa biasa
        Mahasiswa::factory(100)->create()->each(function ($mahasiswa) use ($alergiIds) {
            // Beri alergi random (20% kemungkinan punya alergi)
            if (rand(1, 100) <= 20) {
                $mahasiswa->alergi()->attach(
                    $alergiIds->random(rand(1, 2))->toArray()
                );
            }
        });
        // 2. Buat 15 Mahasiswa Panitia (yang akan jadi Mentor)
        $this->command->info('Membuat data Mahasiswa Panitia (Mentor)...');
        
        // Dapatkan ID Kelompok "Panitia Inti"
        $kelompokPanitia = Kelompok::where('nama', 'Panitia Inti')->first();
        if (!$kelompokPanitia) {
            $this->command->error('Kelompok "Panitia Inti" tidak ditemukan. Pastikan ada di KelompokSeeder.');
            return;
        }

        // 3. Buat 15 Mahasiswa Panitia (yang akan jadi Mentor)
        $this->command->info('Membuat data Mahasiswa Panitia (Mentor)...');
        
        Mahasiswa::factory(15)->create([
            'prodi' => 'Panitia',
            'kelompok_id' => $kelompokPanitia->id,
        ])->each(function ($mahasiswa) {
            // --- INI LOGIKA PENTINGNYA ---
            // Jika Prodi = Panitia, buatkan akun User (Mentor)
            
            $email = Str::slug($mahasiswa->nama, '.') . '@mentor.test';

            $user = User::create([
                'name' => $mahasiswa->nama,
                'email' => $email,
                'password' => Hash::make('password'),
                'role' => 'mentor',
                'email_verified_at' => now(),
            ]);

            // Hubungkan Mahasiswa dengan akun User
            $mahasiswa->user_id = $user->id;
            $mahasiswa->save();
        });
    }
}