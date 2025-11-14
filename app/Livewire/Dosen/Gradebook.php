<?php

namespace App\Livewire\Dosen;

use Livewire\Component;
use App\Models\Matkul;
use App\Models\Mahasiswa;
use App\Models\Nilai;
use Illuminate\Validation\Rule;

class Gradebook extends Component
{
    // Properti publik
    public Matkul $matkul;
    public $mahasiswaList;
    
    // Properti ini akan di-'bind' ke input di view
    // Format: $nilaiData[mahasiswa_id][jenis_nilai]
    public $nilaiData = [];

    /**
     * Method 'mount' berjalan saat komponen pertama kali di-load.
     * Mirip dengan __construct().
     */
    public function mount(Matkul $matkul)
    {
        $this->matkul = $matkul;
        
        // Ambil daftar mahasiswa yang terdaftar di matkul ini
        $this->mahasiswaList = $matkul->mahasiswa()
                                     ->with('user') // Ambil relasi 'user' untuk nama
                                     ->orderBy('nim')
                                     ->get();

        // Ambil nilai yang SUDAH ADA di database
        $nilaiSudahAda = Nilai::where('matkul_id', $this->matkul->id)
                              ->whereIn('mahasiswa_id', $this->mahasiswaList->pluck('id'))
                              ->get()
                              ->keyBy('mahasiswa_id'); // Jadikan ID mahasiswa sebagai key

        // Isi properti $nilaiData dengan nilai yang sudah ada
        foreach ($this->mahasiswaList as $mahasiswa) {
            $nilai = $nilaiSudahAda->get($mahasiswa->id);
            
            $this->nilaiData[$mahasiswa->id] = [
                'tugas' => $nilai->nilai_tugas ?? null,
                'uts' => $nilai->nilai_uts ?? null,
                'uas' => $nilai->nilai_uas ?? null,
            ];
        }
    }

    /**
     * Hook 'updated' ini berjalan SETIAP KALI properti publik berubah
     * (misal: saat Dosen mengetik di input)
     */
    /**
     * Hook ini berjalan SETIAP KALI properti publik berubah
     * (method SUDAH DIPERBAIKI)
     */
    public function updatedNilaiData($value, $key)
    {
        // $key akan berisi string seperti "12.uts" (mahasiswa_id.jenis_nilai)
        [$mahasiswaId, $jenisNilai] = explode('.', $key);

        // Validasi cepat
        $validatedValue = trim($value) === '' ? null : (float) $value;
        if ($validatedValue !== null && ($validatedValue < 0 || $validatedValue > 100)) {
            $this->addError('nilaiData.' . $key, 'Nilai harus antara 0-100.');
            return;
        }

        // Hapus error jika valid
        $this->resetErrorBag('nilaiData.' . $key);

        // ===== INI ADALAH PERBAIKAN PENTING =====
        // "Terjemahkan" key singkat (tugas, uts) ke nama kolom DB (nilai_tugas, nilai_uts)
        $kolomDb = match ($jenisNilai) {
            'tugas' => 'nilai_tugas',
            'uts'   => 'nilai_uts',
            'uas'   => 'nilai_uas',
            default => $jenisNilai, // Fallback (seharusnya tidak terjadi)
        };
        // =========================================

        // Gunakan $kolomDb (nama yang benar) untuk menyimpan
        Nilai::updateOrCreate(
            [
                'mahasiswa_id' => $mahasiswaId,
                'matkul_id'    => $this->matkul->id
            ],
            [
                $kolomDb => $validatedValue 
            ]
        );
    }
    /**
     * Method 'render' menampilkan view.
     */
    public function render()
    {
        return view('livewire.dosen.gradebook');
    }
}