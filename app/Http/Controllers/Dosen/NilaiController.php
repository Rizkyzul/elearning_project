<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Matkul;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\NilaiPerMatkulExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class NilaiController extends Controller
{
    /**
     * Tampilkan halaman 'Pilih Kelas' untuk Penilaian.
     */
    public function index(Matkul $matkul)
    {
        // Ambil semua kelas yang terhubung dengan matkul ini
        // (melalui materi atau tugas)
        $kelasIds = $matkul->materi()->with('kelas')->get()->pluck('kelas.*.id')->flatten()
                    ->merge($matkul->tugas()->with('kelas')->get()->pluck('kelas.*.id')->flatten())
                    ->unique();
        
        $daftarKelas = Kelas::whereIn('id', $kelasIds)->orderBy('nama_kelas')->get();

        return view('dosen.nilai.index', [
            'matkul' => $matkul,
            'daftarKelas' => $daftarKelas
        ]);
    }

public function showKelas(Matkul $matkul, Kelas $kelas)
    {
        // --- STATISTIK BARU ---
        // Ambil semua nilai akhir untuk kelas & matkul ini
        $semuaNilaiAkhir = $matkul->nilai()
                                ->whereHas('mahasiswa', function ($q) use ($kelas) {
                                    $q->where('kelas_id', $kelas->id);
                                })
                                ->whereNotNull('nilai_akhir') // Hanya yg sudah di-grade
                                ->pluck('nilai_akhir');

        $statistik = [
            'rata_rata_kelas' => $semuaNilaiAkhir->count() > 0 ? round($semuaNilaiAkhir->avg(), 2) : 0,
            'sudah_dinilai' => $semuaNilaiAkhir->count(),
            'total_mahasiswa' => $kelas->mahasiswa()->count()
        ];
        // --- AKHIR STATISTIK ---
        
        return view('dosen.nilai.show-kelas', [
            'matkul' => $matkul,
            'kelas' => $kelas,
            'statistik' => $statistik // <-- Kirim data baru
        ]);
    }

    /**
     * Handle export Excel (filter per kelas).
     */
    public function exportExcel(Matkul $matkul, Kelas $kelas)
    {
        $namaFile = 'Nilai_' . $matkul->kode_matkul . '_' . $kelas->nama_kelas . '_' . now()->timestamp . '.xlsx';
        return Excel::download(new NilaiPerMatkulExport($matkul, $kelas), $namaFile);
    }

    /**
     * Handle export PDF (filter per kelas).
     */
    public function exportPdf(Matkul $matkul, Kelas $kelas)
    {
        $dosen = Auth::user()->dosenProfile;
        $dosen->load('user');

        $nilaiList = $matkul->nilai()
                           ->whereHas('mahasiswa', function ($q) use ($kelas) {
                               $q->where('kelas_id', $kelas->id);
                           })
                           ->with('mahasiswa.user')
                           ->get();
        
        $data = [
            'matkul' => $matkul, 'kelas' => $kelas,
            'dosen' => $dosen, 'nilaiList' => $nilaiList,
        ];
        
        $pdf = Pdf::loadView('dosen.nilai.pdf', $data);
        $namaFile = 'Nilai_' . $matkul->kode_matkul . '_' . $kelas->nama_kelas . '_' . now()->timestamp . '.pdf';
        return $pdf->download($namaFile);
    }
}