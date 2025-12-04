<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\JawabanTugas;
use App\Models\Tugas;
use Illuminate\Http\Request;
use App\Models\Matkul;
use Illuminate\Support\Facades\DB;
use App\Models\Kelas;
use App\Models\Nilai;
use Illuminate\Support\Facades\Auth; // <-- Tambahkan ini

class TugasController extends Controller
{
    /**
     * Tampilkan daftar tugas (Disempurnakan: Tampilkan relasi kelas)
     */
    public function index(Matkul $matkul)
    {
        // Hitung total mahasiswa yang enroll (tidak spesifik kelas)
        $totalMahasiswa = $matkul->mahasiswa()->count();

        // Ambil tugas DAN relasi kelasnya (Eager Loading)
        $tugasList = $matkul->tugas()
                            ->with('kelas') 
                            ->withCount('jawaban')
                            ->orderBy('deadline', 'asc')
                            ->get();

        return view('dosen.tugas.index', [
            'matkul' => $matkul,
            'tugasList' => $tugasList,
            'totalMahasiswa' => $totalMahasiswa
        ]);
    }

    /**
     * Tampilkan form buat tugas. (Sudah Benar)
     */
    public function create(Matkul $matkul)
    {
        $daftarKelas = Kelas::orderBy('nama_kelas')->get();

        return view('dosen.tugas.create', [
            'matkul' => $matkul,
            'daftarKelas' => $daftarKelas
        ]);
    }
    
    /**
     * Simpan tugas baru (PERBAIKAN: Menangani 'all_classes')
     */
    public function store(Request $request, Matkul $matkul)
    {
        // 1. Validasi
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'deadline' => 'required|date',
            'kelas_ids' => 'array|nullable',
            'kelas_ids.*' => 'exists:kelas,id',
        ]);

        $kelasIds = []; // Array kosong by default

        // 2. === INI LOGIKA BARUNYA ===
        if ($request->has('all_classes') && $request->all_classes == '1') {
            // Dosen mencentang "SEMUA KELAS"
            $kelasIds = Kelas::pluck('id')->toArray();
        } else {
            // Dosen memilih kelas manual
            $kelasIds = $request->kelas_ids ?? [];
        }
        // ============================

        // 3. Validasi ulang (pastikan tidak kosong)
        if (empty($kelasIds)) {
             return back()->with('error', 'Anda harus memilih setidaknya satu kelas tujuan atau centang "SEMUA KELAS".')->withInput();
        }

        // 4. Buat record Tugas
        $tugas = $matkul->tugas()->create([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'deadline' => $request->deadline,
        ]);
        
        // 5. Sync ke BANYAK kelas (baik semua, atau yang dipilih)
        $tugas->kelas()->sync($kelasIds); 

        return redirect()->route('dosen.tugas.index', $matkul)
                         ->with('success', 'Tugas berhasil dibuat.');
    }

    /**
     * Tampilkan daftar jawaban tugas (submission) untuk dinilai.
     */
    public function showSubmissions(Matkul $matkul, Tugas $tugas) 
    {
        $jawabanList = $tugas->jawaban()
                             ->with('mahasiswa.user')
                             ->orderBy('submitted_at', 'desc')
                             ->get();

        return view('dosen.tugas.show', [
            'matkul' => $matkul,
            'tugas' => $tugas,
            'jawabanList' => $jawabanList
        ]);
    }

    /**
     * Tampilkan halaman Penilaian 1 Jawaban Tugas.
     */
    public function show(Matkul $matkul, Tugas $tugas, JawabanTugas $jawaban)
    {
        $nilai = Nilai::where('mahasiswa_id', $jawaban->mahasiswa_id)
                      ->where('matkul_id', $matkul->id)
                      ->first();

        return view('dosen.tugas.grade', [
            'matkul' => $matkul,
            'tugas' => $tugas,
            'jawaban' => $jawaban,
            'nilai' => $nilai
        ]);
    }

    /**
     * Simpan/Update nilai tugas individual dan hitung ulang nilai kumulatif (rata-rata).
     */
    public function gradeStore(Request $request, Matkul $matkul, Tugas $tugas, JawabanTugas $jawaban)
    {
        $request->validate([
            'nilai_tugas' => 'required|numeric|min:0|max:100',
            'feedback' => 'nullable|string'
        ]);

        $jawaban->nilai_dosen = $request->nilai_tugas;
        $jawaban->catatan_dosen = $request->feedback;
        $jawaban->save(); 

        // Hitung Nilai Tugas Kumulatif (Rata-rata)
        $allJawaban = JawabanTugas::where('mahasiswa_id', $jawaban->mahasiswa_id)
                                ->whereHas('tugas', fn($query) => $query->where('matkul_id', $matkul->id))
                                ->whereNotNull('nilai_dosen') 
                                ->pluck('nilai_dosen');

        $nilaiTugasKumulatif = $allJawaban->count() > 0 ? round($allJawaban->avg()) : 0;

        // Simpan/Update Nilai Kumulatif (ke tabel 'nilai')
        $nilai = Nilai::updateOrCreate(
            [
                'mahasiswa_id' => $jawaban->mahasiswa_id,
                'matkul_id' => $matkul->id,
            ],
            [
                'nilai_tugas' => $nilaiTugasKumulatif, 
                'catatan' => $request->feedback, 
            ]
        );
        
        // Logika Perhitungan Nilai Akhir dan Grade
        $W_TUGAS = 0.40; $W_UTS = 0.30; $W_UAS = 0.30;

        $isReadyToGrade = 
            !is_null($nilai->nilai_tugas) && 
            !is_null($nilai->nilai_uts) && 
            !is_null($nilai->nilai_uas);

        if ($isReadyToGrade) {
            $nilaiAkhir = round(
                ($nilai->nilai_tugas * $W_TUGAS) +
                ($nilai->nilai_uts * $W_UTS) +
                ($nilai->nilai_uas * $W_UAS)
            );
            $gradeHuruf = $this->calculateGrade($nilaiAkhir);
            
            $nilai->update(['nilai_akhir' => $nilaiAkhir, 'grade' => $gradeHuruf]);
        } else {
            $nilai->update(['nilai_akhir' => null, 'grade' => null]);
        }

        $namaMahasiswa = $jawaban->mahasiswa->user->name ?? 'Mahasiswa';
        return back()->with('success', 'Nilai individual ('.$jawaban->nilai_dosen.') dan Nilai Tugas Kumulatif Mata Kuliah ('.$nilaiTugasKumulatif.') berhasil disimpan untuk ' . $namaMahasiswa);
    }

    /**
     * Hapus tugas.
     */
    public function destroy(Matkul $matkul, Tugas $tugas)
    {
        try {
            $isDeleted = DB::table('tugas')->where('id', $tugas->id)->delete();

            if ($isDeleted) {
                return redirect()->route('dosen.tugas.index', $matkul)
                                 ->with('success', 'Tugas berhasil dihapus.');
            } else {
                return redirect()->route('dosen.tugas.index', $matkul)
                                 ->with('error', 'Gagal menghapus tugas. ID tidak ditemukan.');
            }
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('dosen.tugas.index', $matkul)
                             ->with('error', 'Error Database: ' . $e->getMessage());
        }
    }

    /**
     * Fungsi pembantu untuk menentukan Grade berdasarkan skor.
     */
    private function calculateGrade(int $score): string
    {
        if ($score >= 80) return 'A';
        if ($score >= 70) return 'B';
        if ($score >= 60) return 'C';
        if ($score >= 50) return 'D';
        return 'E';
    }
}