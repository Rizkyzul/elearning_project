<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Matkul;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | 1. BUAT DATA MASTER (KELAS)
        |--------------------------------------------------------------------------
        */

        // $prodiList = [
        //     'TI'  => 'Teknik Informatika',
        //     'RPL' => 'Rekayasa Perangkat Lunak',
        //     'SI'  => 'Sistem Informasi',
        //     'MI'  => 'Manajemen Informatika',
        //     'KA'  => 'Komputerisasi Akuntansi',
        // ];

        // $tahunSekarang = 2025;
        // $tahunAkhir = 2021;

        // foreach ($prodiList as $kode => $nama) {
        //     for ($tahun = $tahunSekarang; $tahun >= $tahunAkhir; $tahun--) {
        //         $tahunShort = substr($tahun, -2);

        //         // Buat kelas KIP C1-C5
        //         for ($i = 1; $i <= 5; $i++) {
        //             Kelas::factory()->create([
        //                 'nama_kelas' => "{$kode}-{$tahunShort}-KIP-C{$i}"
        //             ]);
        //         }

        //         // Buat kelas REGULAR C1-C5
        //         for ($i = 1; $i <= 5; $i++) {
        //             Kelas::factory()->create([
        //                 'nama_kelas' => "{$kode}-{$tahunShort}-REGULAR-C{$i}"
        //             ]);
        //         }
        //     }
        // }

        // $kelasDefault = Kelas::first();

        /*
        |--------------------------------------------------------------------------
        | 2. DATA MASTER (MATKUL)
        |--------------------------------------------------------------------------
        */
        $matkulList = [
            ['IF-001', 'Algoritma & Pemrograman'],
            ['IF-002', 'Pemrograman Web Lanjut'],
            ['IF-003', 'Basis Data'],
            ['IF-004', 'Jaringan Komputer'],
            ['IF-005', 'Sistem Operasi'],
        ];

        $matkulIds = [];
        foreach ($matkulList as [$kode, $nama]) {
            $matkul = Matkul::factory()->create([
                'kode_matkul' => $kode,
                'nama_matkul' => $nama
            ]);
            $matkulIds[] = $matkul->id;
        }

        /*
        |--------------------------------------------------------------------------
        | 3. AKUN DOSEN SPESIFIK
        |--------------------------------------------------------------------------
        */
        $dosenUser = User::factory()->dosen()->create([
            'name' => 'Dr. Budi Santoso, M.Kom.',
            'email' => 'dosen@example.com',
            'must_change_password' => false,
        ]);

        $dosenProfile = $dosenUser->dosenProfile;
        $dosenProfile->matkul()->attach($matkulIds);

        /*
        |--------------------------------------------------------------------------
        | 4. AKUN MAHASISWA SPESIFIK
        |--------------------------------------------------------------------------
        */
        // $mahasiswaUser = User::factory()->mahasiswa()->create([
        //     'name' => 'Udin Saputra',
        //     'email' => 'mahasiswa@example.com',
        //     'must_change_password' => true,
        // ]);

        // $mahasiswaProfile = $mahasiswaUser->mahasiswaProfile;
        // $mahasiswaProfile->update([
        //     'nim' => '20251001',
        //     'kelas_id' => $kelasDefault->id
        // ]);
        // $mahasiswaProfile->matkul()->attach($matkulIds);

        // /*
        // |--------------------------------------------------------------------------
        // | 5. 20 MAHASISWA ACAK
        // |--------------------------------------------------------------------------
        // */
        // $mahasiswaUsers = User::factory()->mahasiswa()->count(400)->create();
        // $allKelas = Kelas::all();

        // foreach ($mahasiswaUsers as $user) {
        //     $profile = $user->mahasiswaProfile;
        //     $kelasRandom = $allKelas->random();

        //     $profile->update(['kelas_id' => $kelasRandom->id]);
        //     $profile->matkul()->attach($matkulIds);
        // }
    }
}
