<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Matkul;
use App\Models\Kelas;
use App\Models\SesiPerkuliahan;
use App\Models\Absensi;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    /**
     * Tampilkan halaman manajemen absensi (Livewire + Histori).
     */
    public function index(Matkul $matkul)
    {
        // Ambil histori sesi yang pernah dibuat HANYA untuk matkul ini
        $sesiList = $matkul->sesiPerkuliahan()
                            // HAPUS filter kelas_id
                            ->orderBy('pertemuan_ke', 'desc')
                            ->get();

        return view('dosen.absensi.index', [
            'matkul' => $matkul,
            'sesiList' => $sesiList
            // HAPUS 'kelas' dan 'kelasId'
        ]);
    }

    /**
     * Tampilkan rekap detail absensi per sesi (DENGAN FILTER KELAS).
     */
  public function show(Matkul $matkul, Kelas $kelas, SesiPerkuliahan $sesi)
    {
        // 1. Ambil SEMUA mahasiswa yang terdaftar di KELAS ini
        $mahasiswaDiKelas = $kelas->mahasiswa()
                                ->with('user')
                                ->orderBy('nim')
                                ->get()
                                ->keyBy('id'); // Key berdasarkan mahasiswa->id

        // 2. Ambil SEMUA data absensi (yang sudah scan) untuk SESI ini
        $dataAbsensi = Absensi::where('sesi_perkuliahan_id', $sesi->id)
                              ->with('mahasiswa.user') // Eager load mahasiswa
                              ->get()
                              ->keyBy('mahasiswa_id'); // Key berdasarkan mahasiswa_id
        
        // 3. Gabungkan kedua daftar
        // (Gabungkan ID dari mahasiswa di kelas + ID dari yang sudah absen)
        $allMahasiswaIds = $mahasiswaDiKelas->keys()->merge($dataAbsensi->keys())->unique();

        // 4. Buat rekap final
        $rekapAbsensi = $allMahasiswaIds->map(function ($mahasiswaId) use ($mahasiswaDiKelas, $dataAbsensi) {
            
            // Ambil data mahasiswa (dari daftar kelas, ATAU jika tidak ada, dari data absensi)
            $mahasiswa = $mahasiswaDiKelas->get($mahasiswaId) ?? $dataAbsensi->get($mahasiswaId)->mahasiswa;
            
            // Ambil data absensi (jika ada)
            $absensi = $dataAbsensi->get($mahasiswaId);

            $status = 'absen'; $scan_masuk = null; $scan_keluar = null;

            if ($absensi) {
                // Mahasiswa ada di tabel absensi
                $scan_masuk = $absensi->scan_masuk;
                $scan_keluar = $absensi->scan_keluar;
                
                if ($scan_masuk && $scan_keluar) $status = $absensi->status; // 'hadir' atau 'terlambat'
                elseif ($scan_masuk && !$scan_keluar) $status = 'masuk_saja'; 
                elseif (!$scan_masuk && $scan_keluar) $status = 'keluar_tanpa_masuk';
            }

            return [
                'mahasiswa' => $mahasiswa,
                'status' => $status,
                'scan_masuk' => $scan_masuk,
                'scan_keluar' => $scan_keluar,
            ];
        });

        // 5. Kirim data ke view
        return view('dosen.absensi.show', [
            'matkul' => $matkul,
            'sesi' => $sesi,
            'kelas' => $kelas,
            'rekapAbsensi' => $rekapAbsensi,
            'kelasId' => $kelas->id // Kirim ke view untuk tabs
        ]);
    }

    // HAPUS method showKelas() (sudah tidak dipakai)
}