<?php

namespace App\Http\Controllers\Dosen;
use App\Models\Mahasiswa;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Imports\MahasiswaImport;
use App\Models\Dosen;
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
    // VALIDASI DASAR (tidak memakai unique)
    $request->validate([
        'nama' => 'required|string|max:255',
        'nim' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'prodi' => 'required|string|max:255',
        'angkatan' => 'required|numeric|digits:4',
        'nama_kelas' => 'required|string|max:255',
    ]);

    // CEK DUPLIKAT NIM
    if (Mahasiswa::where('nim', $request->nim)->exists()) {
        return back()->with('error', 'NIM "' . $request->nim . '" sudah terdaftar.');
    }

    // CEK DUPLIKAT EMAIL
    if (User::where('email', $request->email)->exists()) {
        return back()->with('error', 'Email "' . $request->email . '" sudah digunakan.');
    }

    try {

        // Bersihkan nama kelas → uppercase (TI-4A)
        $kelasName = strtoupper($request->nama_kelas);

        // 1. KELAS
        $kelas = Kelas::firstOrCreate(
            ['nama_kelas' => $kelasName],
            ['nama_kelas' => $kelasName]
        );

        // 2. AKUN USER
        $user = User::create([
            'name' => $request->nama,
            'email' => $request->email,
            'role' => 'mahasiswa',
            'password' => Hash::make('password'),
            'must_change_password' => true,
        ]);

        // 3. PROFIL MAHASISWA
        Mahasiswa::create([
            'user_id' => $user->id,
            'nama' => $request->nama,
            'nim' => $request->nim,
            'prodi' => $request->prodi,
            'angkatan' => $request->angkatan,
            'kelas_id' => $kelas->id,
        ]);

        return back()->with('success', 'Mahasiswa "' . $request->nama . '" berhasil ditambahkan.');

    } catch (\Exception $e) {
        return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}


}