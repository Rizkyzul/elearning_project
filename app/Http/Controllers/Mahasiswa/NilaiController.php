<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Nilai;
use Barryvdh\DomPDF\Facade\Pdf; // <-- 1. Import PDF

class NilaiController extends Controller
{
    public function index()
{
    // --- PERBAIKAN KRUSIAL DI SINI ---
    // Ganti Auth::id() dengan ID profil mahasiswa
    $mahasiswaProfile = Auth::user()->mahasiswaProfile;
    $mahasiswaId = $mahasiswaProfile->id; // <-- Ambil ID dari tabel 'mahasiswa'
    // ----------------------------------

    // Ambil SEMUA data nilai Mahasiswa yang sedang login
    $nilaiList = Nilai::where('mahasiswa_id', $mahasiswaId)
                      ->with('matkul')
                      ->get();

    return view('mahasiswa.nilai.index', [
        'nilaiList' => $nilaiList,
        'user' => Auth::user() 
    ]);
}
    /**
     * Generate dan stream PDF.
     */
    public function exportPdf()
    {
        $mahasiswa = Auth::user()->mahasiswaProfile;
        $mahasiswa->load('user'); // Ambil relasi user (untuk nama)

        $nilaiList = Nilai::where('mahasiswa_id', $mahasiswa->id)
                          ->with('matkul')
                          ->get();
        
        // 2. Siapkan data untuk view PDF
        $data = [
            'mahasiswa' => $mahasiswa,
            'nilaiList' => $nilaiList,
            'tanggalCetak' => now()->format('d M Y')
        ];
        
        // 3. Load view PDF dan kirimkan datanya
        $pdf = Pdf::loadView('mahasiswa.nilai.pdf', $data);

        // 4. Download (atau stream) PDF ke browser
        $namaFile = 'KHS_' . $mahasiswa->nim . '_' . now()->timestamp . '.pdf';
        return $pdf->download($namaFile);
    }
}