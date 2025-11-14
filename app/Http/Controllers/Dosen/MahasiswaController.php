<?php

namespace App\Http\Controllers\Dosen;
use App\Models\Mahasiswa;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Imports\MahasiswaImport; 
use Maatwebsite\Excel\Facades\Excel; 
use Illuminate\Support\Facades\Hash;
use App\Models\Kelas;

use App\Models\User;


class MahasiswaController extends Controller
{
    /**
     * Menampilkan halaman form import.
     */
    
    public function index()
    {
        // Kita tidak perlu kirim data list lagi
        return view('dosen.mahasiswa.index');
    }
        
    /**
     * Memproses file Excel yang di-upload.
     */
    public function store(Request $request)
    {
        // 1. Validasi file
        $request->validate([
            'file_mahasiswa' => 'required|file|mimes:xlsx,xls'
        ]);

        try {
            // 2. Proses import
            Excel::import(new MahasiswaImport, $request->file('file_mahasiswa'));

            // 3. Redirect kembali dengan pesan sukses
            return back()->with('success', 'Data mahasiswa berhasil di-import.');

        } catch (\Exception $e) {
            // Tangkap error (misal: header tidak sesuai)
            return back()->with('error', 'Terjadi kesalahan saat meng-import data. Pastikan format file Anda benar. Error: ' . $e->getMessage());
        }
    }
    public function resetPassword(Mahasiswa $mahasiswa)
    {
        // Ambil akun User yang terhubung
        $user = $mahasiswa->user;

        // Reset password & set flag 'must_change_password'
        $user->update([
            'password' => Hash::make('password123'), // Set ke password default import
            'must_change_password' => true
        ]);

        return back()->with('success', 'Password untuk ' . $user->name . ' berhasil di-reset.');
    }
 public function storeManual(Request $request)
    {
        // ... (Validasi Anda sudah benar) ...
        $request->validate([
            'nama' => 'required|string|max:255',
            'nim' => 'required|string|unique:mahasiswa,nim',
            'email' => 'required|email|unique:users,email',
            'prodi' => 'required|string|max:255',
            'angkatan' => 'required|numeric|digits:4',
            'nama_kelas' => 'required|string|max:255',
        ]);

        try {
            // 1. Ambil SEMUA ID mata kuliah
            // $allMatkulIds = \App\Models\Matkul::pluck('id')->toArray();

            // 2. Dapatkan atau Buat Kelas Baru
            $kelas = Kelas::firstOrCreate(
                ['nama_kelas' => $request->nama_kelas],
                ['nama_kelas' => $request->nama_kelas]
            );

            // 3. Buat Akun User (Login)
            $user = User::firstOrCreate(
                ['email' => $request->email], 
                [
                    'name' => $request->nama,
                    'role' => 'mahasiswa',
                    'password' => Hash::make('password'),
                    'must_change_password' => true,
                ]
            );

            // 4. Buat Profil Mahasiswa (Biodata)
            $mahasiswa = Mahasiswa::firstOrCreate(
                ['nim' => $request->nim], 
                [
                    'user_id' => $user->id,
                    'nama' => $request->nama,
                    'angkatan' => $request->angkatan,
                    'prodi' => $request->prodi,
                    'kelas_id' => $kelas->id,
                ]
            );

            // 5. === INI PERBAIKANNYA ===
            // Daftarkan (enroll) mahasiswa ini ke SEMUA mata kuliah
            // $mahasiswa->matkul()->sync($allMatkulIds);
            // ==========================

            return back()->with('success', 'Mahasiswa "' . $request->nama . '" berhasil ditambahkan.');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}