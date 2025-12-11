<?php

namespace App\Imports;

use App\Models\Mahasiswa;
use App\Models\Kelompok;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class MahasiswaImport implements ToModel, WithHeadingRow, WithValidation, ShouldQueue, WithChunkReading
{
    private $activeEventId;
    private $tenantId; // Simpan tenantId

    public function __construct()
    {
        $this->activeEventId = session('active_event_id');
        $this->tenantId = Auth::user()->tenant_id; // Ambil tenantId saat job dibuat
    }

    public function model(array $row)
    {
        // 1. LOGIKA KELOMPOK (Sederhana)
        // Cari Kelompok berdasarkan nama. Jika tidak ada, buat baru.
        $kelompok = Kelompok::firstOrCreate(
            [
                'event_id' => $this->activeEventId,
                'nama' => $row['kelompok']
            ],
            [
                'event_id' => $this->activeEventId,
                'nama' => $row['kelompok']
            ]
        );

        // 2. LOGIKA MENTOR (Panitia = Mentor)
        $userId = null;
        if (isset($row['prodi']) && strtoupper($row['prodi']) === 'PANITIA') {
            $email = Str::slug($row['nama'], '.') . '@mentor.test';
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'tenant_id' => $this->tenantId, // Gunakan tenantId yang disimpan
                    'name' => $row['nama'],
                    'password' => Hash::make('password'),
                    'role' => 'mentor',
                    'email_verified_at' => now()
                ]
            );
            $userId = $user->id;
        }

        // 3. LOGIKA IS_VEGAN
        $isVegan = false;
        if (isset($row['is_vegan'])) {
            $isVegan = in_array(strtoupper($row['is_vegan']), ['1', 'TRUE', 'YA', 'YES']);
        }

        // 4. BUAT MAHASISWA
        $mahasiswa = Mahasiswa::create([
            'event_id'    => $this->activeEventId,
            'nim'         => $row['nim'],
            'nama'        => $row['nama'],
            'prodi'       => $row['prodi'] ?? null,
            'kelompok_id' => $kelompok->id,
            'no_urut'     => $row['no'], // Ambil dari kolom 'no'
            'is_vegan'    => $isVegan,
            'user_id'     => $userId,
        ]);

        return $mahasiswa;
    }

    public function rules(): array
    {
        return [
            'nim' => 'required|unique:mahasiswas,nim,NULL,id,event_id,' . $this->activeEventId,
            'nama' => 'required|string|max:255',
            'prodi' => 'nullable|string',
            'kelompok' => 'required|string',
            'no' => 'required|integer', // Ubah 'no_urut' menjadi 'no'
            'is_vegan' => 'nullable',
        ];
    }

    // Method untuk memproses file dalam potongan (chunks)
    public function chunkSize(): int
    {
        return 200; // Proses 200 baris per job, sesuaikan jika perlu
    }
}