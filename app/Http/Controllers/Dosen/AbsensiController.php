<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Matkul; 
use Illuminate\Http\Request;
use App\Models\SesiPerkuliahan;

class AbsensiController extends Controller
{
    /**
     * Tampilkan halaman utama manajemen absensi.
     */
    public function index(Matkul $matkul)
    {
        // Ambil histori sesi yang pernah dibuat
        $sesiList = $matkul->sesiPerkuliahan()
                            ->orderBy('pertemuan_ke', 'desc')
                            ->get();

        return view('dosen.absensi.index', [
            'matkul' => $matkul,
            'sesiList' => $sesiList
        ]);
    }
   public function show(Matkul $matkul, SesiPerkuliahan $sesi)
    {
      
        $semuaMahasiswa = $matkul->mahasiswa()->with('user')->orderBy('nim')->get();

        $dataAbsensi = Absensi::where('sesi_perkuliahan_id', $sesi->id)
                              ->get()
                              ->keyBy('mahasiswa_id');

        // 3. Gabungkan data
        $rekapAbsensi = $semuaMahasiswa->map(function ($mahasiswa) use ($dataAbsensi) {
            $absensi = $dataAbsensi->get($mahasiswa->id);

            $status = 'absen'; // Default jika tidak ada record
            $scan_masuk = null;
            $scan_keluar = null;

            if ($absensi) {
                // Mahasiswa ada di tabel absensi
                $scan_masuk = $absensi->scan_masuk;
                $scan_keluar = $absensi->scan_keluar;
                
                // --- INI LOGIKA BARU (PERBAIKAN) ---
                if ($scan_masuk && $scan_keluar) {
                    // 1. Scan Masuk DAN Keluar = HADIR / TERLAMBAT
                    $status = $absensi->status; // ('hadir' atau 'terlambat' dari scan masuk)
                
                } elseif ($scan_masuk && !$scan_keluar) {
                    // 2. Scan Masuk SAJA = MASUK SAJA
                    $status = 'masuk_saja'; 
                
                } elseif (!$scan_masuk && $scan_keluar) {
                    // 3. Scan Keluar SAJA = KELUAR TANPA MASUK
                    $status = 'keluar_tanpa_masuk';
                }
              
            }

            return [
                'mahasiswa' => $mahasiswa,
                'status' => $status,
                'scan_masuk' => $scan_masuk,
                'scan_keluar' => $scan_keluar,
            ];
        });

        return view('dosen.absensi.show', [
            'matkul' => $matkul,
            'sesi' => $sesi,
            'rekapAbsensi' => $rekapAbsensi
        ]);
    }
}