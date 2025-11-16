<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SesiPerkuliahan;
use App\Models\Absensi; // <-- 1. TAMBAHKAN IMPORT INI

class AbsensiController extends Controller
{
    /**
     * Tampilkan halaman scanner DAN histori absensi.
     */
    public function showScanner()
    {
        // 2. AMBIL ID MAHASISWA
        $mahasiswaId = Auth::user()->mahasiswaProfile->id;

        // 3. AMBIL DATA HISTORI
        // Ambil semua absensi milik mahasiswa ini,
        // urutkan dari yang terbaru, dan sertakan info Matkul & Sesi.
        $historiAbsen = Absensi::where('mahasiswa_id', $mahasiswaId)
                                ->with('sesiPerkuliahan.matkul') // Eager load
                                ->orderBy('created_at', 'desc')
                                ->paginate(10); // Buat pagination

        // 4. KIRIM DATA KE VIEW
        return view('mahasiswa.absensi.index', [
            'historiAbsen' => $historiAbsen
        ]);
    }

    /**
     * Proses hasil scan.
     * (Method ini sudah benar, tidak perlu diubah)
     */
    public function storeScan(Request $request)
    {
        $request->validate(['qr_code' => 'required|string']);
        
        $mahasiswa = Auth::user()->mahasiswaProfile;
        $kode = $request->qr_code;

        // --- ALUR ABSEN MASUK ---
        $sesi = SesiPerkuliahan::where('code_masuk', $kode)->first();
        
        if ($sesi) {
            $absensiSudahAda = Absensi::where('sesi_perkuliahan_id', $sesi->id)
                                      ->where('mahasiswa_id', $mahasiswa->id)
                                      ->first();
            
            if ($absensiSudahAda && $absensiSudahAda->scan_masuk) {
                return back()->with('error', 'Anda sudah melakukan absen MASUK untuk sesi ini.');
            }

            $status = ($sesi->expires_at_masuk > now()) ? 'hadir' : 'terlambat';

            Absensi::updateOrCreate(
                [ 'sesi_perkuliahan_id' => $sesi->id, 'mahasiswa_id' => $mahasiswa->id, ],
                [ 'scan_masuk' => now(), 'status' => $status, ]
            );

            return back()->with('success', 'Absen MASUK berhasil. Status: ' . $status);
        }

        // --- ALUR ABSEN KELUAR ---
        $sesi = SesiPerkuliahan::where('code_keluar', $kode)->first();

        if ($sesi) {
            if ($sesi->expires_at_keluar <= now()) {
                return back()->with('error', 'QR Code Absen KELUAR sudah hangus.');
            }

            $absensi = Absensi::where('sesi_perkuliahan_id', $sesi->id)
                              ->where('mahasiswa_id', $mahasiswa->id)
                              ->first();

            if (!$absensi) {
                Absensi::create([
                    'sesi_perkuliahan_id' => $sesi->id,
                    'mahasiswa_id' => $mahasiswa->id,
                    'scan_keluar' => now(),
                    'status' => 'keluar_tanpa_masuk',
                ]);
                return back()->with('warning', 'Absen KELUAR berhasil, tapi Anda tidak tercatat absen masuk.');
            }

            $absensi->update(['scan_keluar' => now()]);
            return back()->with('success', 'Absen KELUAR berhasil. Terima kasih.');
        }

        return back()->with('error', 'QR Code tidak valid atau sudah hangus.');
    }
}