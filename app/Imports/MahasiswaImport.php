<?php

namespace App\Imports;

use App\Models\Mahasiswa;
use App\Models\Kelompok;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache; // Tambahkan ini
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class MahasiswaImport implements ToCollection, WithHeadingRow, WithValidation, ShouldQueue, WithChunkReading, SkipsEmptyRows 
{
    private $activeEventId;
    private $tenantId;
    private $userId; // Tambahkan properti userId

    public function __construct($activeEventId, $tenantId, $userId) // Tambahkan $userId
    {
        $this->activeEventId = $activeEventId;
        $this->tenantId = $tenantId;
        $this->userId = $userId; // Simpan userId
    }

    public function collection(Collection $rows)
    {
        try {
            // === STEP 1: PROSES KELOMPOK (BULK) ===
            // Filter baris yang benar-benar punya data kelompok
            $validRows = $rows->filter(function ($row) {
                return !empty($row['kelompok']) && !empty($row['nim']);
            });

            if ($validRows->isEmpty()) {
                return;
            }

            $groupNames = $validRows->pluck('kelompok')->filter()->unique();

            $existingGroups = Kelompok::where('event_id', $this->activeEventId)
                ->whereIn('nama', $groupNames)
                ->pluck('id', 'nama');

            $groupsToCreate = $groupNames->diff($existingGroups->keys());
            $newGroupsData = [];
            $now = now();

            foreach ($groupsToCreate as $name) {
                $newGroupsData[] = [
                    'event_id' => $this->activeEventId,
                    'nama' => $name,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            if (!empty($newGroupsData)) {
                Kelompok::insert($newGroupsData);
            }

            $finalGroupMap = Kelompok::where('event_id', $this->activeEventId)
                ->whereIn('nama', $groupNames)
                ->pluck('id', 'nama');


            // === STEP 2: PERSIAPAN DATA MAHASISWA ===
            $mahasiswaInsertData = [];

            foreach ($validRows as $row) { // Loop hanya baris valid
                $groupId = $finalGroupMap[$row['kelompok']] ?? null;
                $userId = null;

                if (isset($row['prodi']) && strtoupper($row['prodi']) === 'PANITIA') {
                    $email = Str::slug($row['nama'], '.') . '@mentor.test';
                    $user = User::firstOrCreate(
                        ['email' => $email],
                        [
                            'tenant_id' => $this->tenantId,
                            'name' => $row['nama'],
                            'password' => Hash::make('password'),
                            'role' => 'mentor', 
                            'email_verified_at' => $now
                        ]
                    );
                    $userId = $user->id;
                }

                $isVeganInput = strtoupper((string) ($row['is_vegan'] ?? ''));
                $isVegan = in_array($isVeganInput, ['1', 'TRUE', 'YA', 'YES']) ? 1 : 0;

                $mahasiswaInsertData[] = [
                    'event_id'    => $this->activeEventId,
                    'nim'         => $row['nim'],
                    'nama'        => $row['nama'],
                    'prodi'       => $row['prodi'] ?? null,
                    'kelompok_id' => $groupId,
                    'no_urut'     => $row['no'],
                    'is_vegan'    => $isVegan,
                    'user_id'     => $userId,
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ];
            }

            // === STEP 3: INSERT MAHASISWA ===
            if (!empty($mahasiswaInsertData)) {
                Mahasiswa::insertOrIgnore($mahasiswaInsertData);
            }
        } finally {
            // Tandai di cache bahwa proses untuk user ini sudah selesai.
            Cache::put('import_status_for_user_' . $this->userId, 'completed', now()->addMinutes(30));
        }
    }

    public function rules(): array
    {
        return [
            'nim' => 'required',
            'nama' => 'required',
            // Hapus validasi lain yg terlalu ketat untuk baris kosong
        ];
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}