<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Kelas;
use App\Models\Matkul; // <-- 1. TAMBAHKAN INI
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\WithValidation;

class MahasiswaImport implements ToCollection, WithHeadingRow
{
    // Simpan semua ID matkul saat class diinisiasi
    private $allMatkulIds;

    public function __construct()
    {
        // 2. Ambil SEMUA ID mata kuliah SATU KALI saja
        $this->allMatkulIds = Matkul::pluck('id')->toArray();
    }

    /**
    * @param Collection $rows
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            if (empty($row['nim']) || empty($row['nama']) || empty($row['email']) || empty($row['kelas'])) {
                continue; 
            }

            $kelas = Kelas::firstOrCreate(
                ['nama_kelas' => $row['kelas']],
                ['nama_kelas' => $row['kelas']]
            );

            $user = User::firstOrCreate(
                ['email' => $row['email']],
                [
                    'name' => $row['nama'],
                    'role' => 'mahasiswa',
                    'password' => Hash::make('password123'),
                    'must_change_password' => true,
                ]
            );

            $mahasiswa = Mahasiswa::firstOrCreate(
                ['nim' => $row['nim']], 
                [
                    'user_id' => $user->id,
                    'nama' => $row['nama'],
                    'angkatan' => $row['angkatan'],
                    'prodi' => $row['prodi'],
                    'kelas_id' => $kelas->id,
                ]
            );

            // 3. === INI PERBAIKANNYA ===
            // Daftarkan (enroll) mahasiswa ini ke SEMUA mata kuliah
            // $mahasiswa->matkul()->sync($this->allMatkulIds);
            // ==========================
        }
    }
}