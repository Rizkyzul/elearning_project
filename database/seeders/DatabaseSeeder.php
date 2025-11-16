<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Matkul;
use App\Models\Dosen;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Anda bilang hanya butuh data Dosen, jadi
        // semua data Kelas, Matkul, dan Mahasiswa saya komentari.

        /*
        |--------------------------------------------------------------------------
        | 3. AKUN DOSEN SPESIFIK (SUPERADMIN)
        |--------------------------------------------------------------------------
        */
        $superadminUser = User::create([
            'name' => 'Dr. Budi Santoso, M.Kom.',
            'email' => 'superadmin@example.com',
            'role' => 'superadmin',
            'password' => Hash::make('password123'),
            'must_change_password' => false,
            'email_verified_at' => now(),
        ]);
        
        $superadminProfile = Dosen::create([
            'user_id' => $superadminUser->id,
            'nama' => $superadminUser->name, // <-- INI PERBAIKANNYA
            'nidn' => '00000001',
            'prodi' => 'Administrasi'
        ]);
        // $superadminProfile->matkul()->attach($matkulIds);

        /*
        |--------------------------------------------------------------------------
        | 4. BUAT DOSEN BIASA (Punya Profil Dosen)
        |--------------------------------------------------------------------------
        */
        $dosenUser = User::create([
            'name' => 'Susi Susanti, M.Pd.',
            'email' => 'dosen@example.com',
            'role' => 'dosen',
            'password' => Hash::make('password123'),
            'must_change_password' => false,
            'email_verified_at' => now(),
        ]);
        
        $dosenProfile = Dosen::create([
            'user_id' => $dosenUser->id,
            'nama' => $dosenUser->name, // <-- INI PERBAIKANNYA
            'nidn' => '00000002',
            'prodi' => 'Teknik Informatika'
        ]);
        // $dosenProfile->matkul()->attach($matkulIds);
    }
}