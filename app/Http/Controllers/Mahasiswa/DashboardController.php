<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- Tambahkan ini
use App\Models\Matkul;
use App\Models\Materi;
use App\Models\Tugas;

class DashboardController extends Controller
{
 public function index()
{
    // 1. Ambil profil mahasiswa dan ID kelasnya
    $mahasiswaProfile = Auth::user()->mahasiswaProfile->load('kelas');
    $kelasId = $mahasiswaProfile->kelas_id;

    // 2. Cari semua ID Mata Kuliah yang punya MATERI untuk kelas ini
    $matkulIdsFromMateri = Materi::whereHas('kelas', function ($q) use ($kelasId) {
                                $q->where('kelas.id', $kelasId);
                            })
                            ->distinct()
                            ->pluck('matkul_id'); // Ambil [1, 2]

    // 3. Cari semua ID Mata Kuliah yang punya TUGAS untuk kelas ini
    $matkulIdsFromTugas = Tugas::whereHas('kelas', function ($q) use ($kelasId) {
                                $q->where('kelas.id', $kelasId);
                            })
                            ->distinct()
                            ->pluck('matkul_id'); // Ambil [1, 3]

    // 4. Gabungkan kedua daftar ID dan buat unik
    // Hasil: [1, 2, 3]
    $allMatkulIds = $matkulIdsFromMateri->merge($matkulIdsFromTugas)->unique();

    // 5. Ambil Model Mata Kuliah berdasarkan ID unik tersebut
    $mataKuliah = Matkul::whereIn('id', $allMatkulIds)
                            ->orderBy('nama_matkul')
                            ->get();

    return view('mahasiswa.dashboard', [
        'mataKuliah' => $mataKuliah
        // 'mahasiswaProfile' sudah otomatis tersedia via Auth
    ]);
}
}