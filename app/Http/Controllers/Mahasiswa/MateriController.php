<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Matkul; // <-- Tambahkan ini
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 

class MateriController extends Controller
{
public function index(Matkul $matkul)
    {
        // 1. Ambil kelas ID mahasiswa yang sedang login
        $mahasiswaKelasId = Auth::user()->mahasiswaProfile->kelas_id;

        // 2. Query: Ambil semua Materi di Matkul ini yang SANGKUTAN dengan kelas Mahasiswa
        $materiList = $matkul->materi()
                            ->whereHas('kelas', function ($query) use ($mahasiswaKelasId) {
                                // WHERE materi ini terhubung ke kelas ID si mahasiswa
                                $query->where('kelas.id', $mahasiswaKelasId);
                            })
                            ->orderBy('created_at', 'desc')
                            ->get();

        return view('mahasiswa.materi.index', [
            'matkul' => $matkul,
            'materiList' => $materiList,
        ]);
    }
}