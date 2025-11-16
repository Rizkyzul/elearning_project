<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Matkul;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MatkulController extends Controller
{
    /**
     * Tampilkan halaman manajemen (daftar + form tambah) mata kuliah.
     */
    public function index()
    {
        // Ambil semua matkul, urutkan
        $semuaMatkul = Matkul::orderBy('nama_matkul')->get();
        
        return view('dosen.matkul.index', [
            'semuaMatkul' => $semuaMatkul
        ]);
    }

    /**
     * Simpan mata kuliah baru dan daftarkan dosen ke matkul tsb.
     * (Method ini sudah Anda miliki dan sudah benar)
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_matkul' => 'required|string|max:255',
            'kode_matkul' => 'required|string|max:20|unique:matkul,kode_matkul',
        ], [
            'kode_matkul.unique' => 'Kode Mata Kuliah ini sudah ada.'
        ]);

        try {
            $matkul = Matkul::create([
                'nama_matkul' => $request->nama_matkul,
                'kode_matkul' => $request->kode_matkul,
            ]);

            $dosenProfile = Auth::user()->dosenProfile;
            $dosenProfile->matkul()->attach($matkul->id);

            return back()->with('success', 'Mata Kuliah "' . $matkul->nama_matkul . '" berhasil dibuat.');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Hapus mata kuliah dari storage.
     */
    public function destroy(Matkul $matkul)
    {
        try {
            // (Database kita sudah di-set 'onDelete('cascade')')
            // Menghapus matkul ini akan otomatis menghapus semua
            // materi, tugas, nilai, dan absensi yang terkait.
            $matkul->delete();
            
            return back()->with('success', 'Mata Kuliah "' . $matkul->nama_matkul . '" dan semua data terkait (materi, tugas, nilai, absensi) telah berhasil dihapus.');
        
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }
}