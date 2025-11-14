<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SesiPerkuliahan;
use App\Models\Absensi;

class AbsensiController extends Controller
{
    /**
     * Tampilkan halaman scanner.
     */
    public function showScanner()
    {
        return view('mahasiswa.absensi.index');
    }

    /**
     * Proses hasil scan.
     */
    public function storeScan(Request $request)
    {
        $request->validate(['qr_code' => 'required|string']);
        
        $mahasiswa = Auth::user()->mahasiswaProfile;
        $kode = $request->qr_code;

        // --- ALUR ABSEN MASUK ---
        // 1. Cari sesi perkuliahan berdasarkan 'code_masuk'
        $sesi = SesiPerkuliahan::where('code_masuk', $kode)->first();
        
        if ($sesi) {
            // Cek apakah mahasiswa sudah absen masuk di sesi ini
            $absensiSudahAda = Absensi::where('sesi_perkuliahan_id', $sesi->id)
                                      ->where('mahasiswa_id', $mahasiswa->id)
                                      ->first();
            
            if ($absensiSudahAda && $absensiSudahAda->scan_masuk) {
                return back()->with('error', 'Anda sudah melakukan absen MASUK untuk sesi ini.');
            }

            // Tentukan status
            $status = ($sesi->expires_at_masuk > now()) ? 'hadir' : 'terlambat';

            // Catat absensi
            Absensi::updateOrCreate(
                [
                    'sesi_perkuliahan_id' => $sesi->id,
                    'mahasiswa_id' => $mahasiswa->id,
                ],
                [
                    'scan_masuk' => now(),
                    'status' => $status,
                ]
            );

            return back()->with('success', 'Absen MASUK berhasil. Status: ' . $status);
        }

        // --- ALUR ABSEN KELUAR ---
        // 2. Jika tidak ketemu di 'code_masuk', cari di 'code_keluar'
        $sesi = SesiPerkuliahan::where('code_keluar', $kode)->first();

        if ($sesi) {
            // Cek apakah sesi ini valid (masih berlaku)
            if ($sesi->expires_at_keluar <= now()) {
                return back()->with('error', 'QR Code Absen KELUAR sudah hangus.');
            }

            // Cari data absensi mahasiswa di sesi ini
            $absensi = Absensi::where('sesi_perkuliahan_id', $sesi->id)
                              ->where('mahasiswa_id', $mahasiswa->id)
                              ->first();

            if (!$absensi) {
                // Aneh: Dia scan keluar tapi belum pernah scan masuk
                Absensi::create([
                    'sesi_perkuliahan_id' => $sesi->id,
                    'mahasiswa_id' => $mahasiswa->id,
                    'scan_keluar' => now(),
                    'status' => 'keluar_tanpa_masuk', // Sesuai permintaan
                ]);
                return back()->with('warning', 'Absen KELUAR berhasil, tapi Anda tidak tercatat absen masuk.');
            }

            // Normal: Update scan_keluar
            $absensi->update(['scan_keluar' => now()]);
            return back()->with('success', 'Absen KELUAR berhasil. Terima kasih.');
        }

        // --- KODE TIDAK DITEMUKAN ---
        return back()->with('error', 'QR Code tidak valid atau sudah hangus.');
    }
}