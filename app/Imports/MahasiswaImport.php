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
use Illuminate\Support\Facades\Auth; // Tambahkan ini

class MahasiswaImport implements ToModel, WithHeadingRow, WithValidation
{
    private $activeEventId;

    public function __construct()
    {
        // Ambil event_id yang aktif saat objek import dibuat
        $this->activeEventId = session('active_event_id');
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // 1. LOGIKA KELOMPOK (Dengan set_vendor opsional)
        
        // Cek jika 'set_vendor' ada di Excel & tidak kosong. 
        // Jika tidak, beri nilai default.

        // Cari Kelompok berdasarkan nama. 
        // Jika tidak ada, buat baru dengan set_vendor (yang mungkin default).
        $kelompok = Kelompok::firstOrCreate(
            [
                'nama' => $row['kelompok'] // Kunci pencarian
            ]
        );

        // 2. LOGIKA MENTOR (Panitia = Mentor)
        // (Pastikan file Excel Anda punya kolom 'prodi')
        $userId = null;
        if (strtoupper($row['prodi']) === 'PANITIA') {
            
            $email = Str::slug($row['nama'], '.') . '@mentor.test';
            
            $user = User::firstOrCreate(
                ['email' => $email],
                [
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
            $isVegan = in_array(strtoupper($row['is_vegan']), ['1', 'TRUE', 'YA']);
        }

        // 4. BUAT MAHASISWA
        return new Mahasiswa([
            'event_id'    => $this->activeEventId, // <-- INI KUNCINYA
            'nim'         => $row['nim'],
            'nama'        => $row['nama'],
            'prodi'       => $row['prodi'],
            'kelompok_id' => $kelompok->id, 
            'no_urut'     => $row['no_urut'],
            'is_vegan'    => $isVegan,
            'user_id'     => $userId,
        ]);
    }

    /**
     * Aturan validasi untuk setiap baris Excel.
     */
    public function rules(): array
    {
        return [
            'nim' => 'required|unique:mahasiswas,nim',
            'nama' => 'required|string|max:255',
            'prodi' => 'required|string', // Kolom 'prodi' tetap wajib
            
            'kelompok' => 'required|string',
            'no_urut' => 'required|integer',
            'is_vegan' => 'nullable',
        ];
    }
}