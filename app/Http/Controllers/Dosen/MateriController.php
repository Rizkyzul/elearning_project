<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Materi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Matkul;
use App\Models\Kelas;

class MateriController extends Controller
{
    /**
     * Tampilkan daftar materi (Disempurnakan: Tampilkan relasi kelas)
     */
    public function index(Matkul $matkul)
    {
        // Ambil semua materi DAN relasi kelasnya (Eager Loading)
        $materiList = $matkul->materi()
                            ->with('kelas') // <-- PENTING
                            ->orderBy('created_at', 'desc')
                            ->get();

        return view('dosen.materi.index', [
            'matkul' => $matkul,
            'materiList' => $materiList,
        ]);
    }

    /**
     * Tampilkan form upload. (Sudah Benar)
     */
    public function create(Matkul $matkul)
    {
        $daftarKelas = Kelas::orderBy('nama_kelas')->get();

        return view('dosen.materi.create', [
            'matkul' => $matkul,
            'daftarKelas' => $daftarKelas
        ]);
    }

    /**
     * Simpan materi baru (PERBAIKAN: Menangani 'all_classes')
     */
    public function store(Request $request, Matkul $matkul)
    {
        // 1. Validasi
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'file_materi' => 'required|file|mimes:pdf,ppt,pptx,doc,docx,zip|max:20480',
            'kelas_ids' => 'array|nullable',
            'kelas_ids.*' => 'exists:kelas,id',
        ]);

        $kelasIds = []; // Array kosong by default

        // 2. === INI LOGIKA BARUNYA ===
        if ($request->has('all_classes') && $request->all_classes == '1') {
            // Dosen mencentang "SEMUA KELAS"
            // Ambil SEMUA ID kelas yang ada di database
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

        // 4. Simpan file
        $path = $request->file('file_materi')->store('materi', 'public');

        // 5. Buat record Materi
        $materi = $matkul->materi()->create([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'file_path' => $path,
        ]);
        
        // 6. Sync ke BANYAK kelas (baik semua, atau yang dipilih)
        $materi->kelas()->sync($kelasIds); 

        return redirect()->route('dosen.materi.index', $matkul)
                         ->with('success', 'Materi berhasil di-upload.');
    }

    /**
     * Hapus materi. (Sudah Benar)
     */
    public function destroy(Matkul $matkul, Materi $materi)
    {
        $path = str_replace('public/', '', $materi->file_path);

        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }

        $materi->delete();

        return redirect()->route('dosen.materi.index', $matkul)
                         ->with('success', 'Materi berhasil dihapus.');
    }
    
    // Method show, edit, update biarkan kosong
    public function show(Materi $materi){}
    public function edit(Materi $materi){}
    public function update(Request $request, Materi $materi){}
}