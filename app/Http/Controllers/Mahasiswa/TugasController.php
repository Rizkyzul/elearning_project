<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Matkul;
use App\Models\Tugas;
use App\Models\JawabanTugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Storage;

class TugasController extends Controller
{
    /**
     * Tampilkan daftar tugas (PERBAIKAN: Filter 'whereHas')
     */
    public function index(Matkul $matkul)
    {
        // 1. Ambil kelas ID mahasiswa yang sedang login
        $mahasiswaKelasId = Auth::user()->mahasiswaProfile->kelas_id;

        // 2. Query: Ambil semua Tugas di Matkul ini YANG terhubung ke kelas Mahasiswa
        $tugasList = $matkul->tugas()
                            ->whereHas('kelas', function ($query) use ($mahasiswaKelasId) {
                                // WHERE tugas ini terhubung ke kelas ID si mahasiswa
                                $query->where('kelas.id', $mahasiswaKelasId);
                            })
                            ->orderBy('deadline', 'asc')
                            ->get();

        return view('mahasiswa.tugas.index', [
            'matkul' => $matkul,
            'tugasList' => $tugasList,
        ]);
    }

    /**
     * Tampilkan halaman detail tugas dan form submit.
     */
    public function show(Matkul $matkul, Tugas $tugas)
    {
        $jawaban = Auth::user()->mahasiswaProfile
                        ->jawabanTugas()
                        ->where('tugas_id', $tugas->id)
                        ->first();

        return view('mahasiswa.tugas.show', [
            'matkul' => $matkul,
            'tugas' => $tugas,
            'jawaban' => $jawaban
        ]);
    }

    /**
     * Proses submit/upload file jawaban.
     */
    public function submitStore(Request $request, Matkul $matkul, Tugas $tugas)
    {
        $request->validate([
            'file_jawaban' => 'required|file|mimes:pdf,zip,doc,docx|max:10240', // Maks 10MB
        ]);

        if (now() > $tugas->deadline) {
            return back()->with('error', 'Gagal, deadline sudah lewat.');
        }

        $mahasiswa = Auth::user()->mahasiswaProfile;

        $jawabanLama = JawabanTugas::where('tugas_id', $tugas->id)
                                  ->where('mahasiswa_id', $mahasiswa->id)
                                  ->first();
        
        if ($jawabanLama) {
            Storage::disk('public')->delete($jawabanLama->file_path);
        }

        $path = $request->file('file_jawaban')->store('jawaban_tugas/' . $tugas->id . '/' . $mahasiswa->id, 'public');

        JawabanTugas::updateOrCreate(
            [
                'tugas_id' => $tugas->id,
                'mahasiswa_id' => $mahasiswa->id,
            ],
            [
                'file_path' => $path,
                'submitted_at' => now()
            ]
        );

        return back()->with('success', 'Tugas berhasil di-submit!');
    }
}