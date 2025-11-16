<?php

namespace App\Livewire\Dosen;

use Livewire\Component;
use App\Models\Matkul;
use App\Models\Kelas;
use App\Models\Mahasiswa;
use App\Models\Nilai;
use Illuminate\Validation\Rule;

class Gradebook extends Component
{
    // Properti publik
    public Matkul $matkul;
    public Kelas $kelas; 
    public $mahasiswaList;
    public $nilaiData = [];

    /**
     * Method 'mount' berjalan saat komponen pertama kali di-load.
     */
    public function mount(Matkul $matkul, Kelas $kelas)
    {
        $this->matkul = $matkul;
        $this->kelas = $kelas; 
        
        // --- INI PERBAIKANNYA ---
        // Kita ambil mahasiswa HANYA DARI KELAS yang dipilih.
        $this->mahasiswaList = $this->kelas->mahasiswa()
                                     ->with('user')
                                     ->orderBy('nim')
                                     ->get();
        // -------------------------

        // Ambil nilai yang SUDAH ADA di database
        $nilaiSudahAda = Nilai::where('matkul_id', $this->matkul->id)
                              ->whereIn('mahasiswa_id', $this->mahasiswaList->pluck('id'))
                              ->get()
                              ->keyBy('mahasiswa_id');

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
     * Hook ini berjalan SETIAP KALI properti publik berubah
     */
    public function updatedNilaiData($value, $key)
    {
        [$mahasiswaId, $jenisNilai] = explode('.', $key);

        $validatedValue = trim($value) === '' ? null : (float) $value;
        if ($validatedValue !== null && ($validatedValue < 0 || $validatedValue > 100)) {
            $this->addError('nilaiData.' . $key, 'Nilai harus antara 0-100.');
            return;
        }

        $this->resetErrorBag('nilaiData.' . $key);

        $kolomDb = match ($jenisNilai) {
            'tugas' => 'nilai_tugas',
            'uts'   => 'nilai_uts',
            'uas'   => 'nilai_uas',
            default => $jenisNilai,
        };

        // Simpan ke database
        $nilai = Nilai::updateOrCreate(
            [
                'mahasiswa_id' => $mahasiswaId,
                'matkul_id'    => $this->matkul->id
            ],
            [
                $kolomDb => $validatedValue 
            ]
        );

        // --- Logika Perhitungan Nilai Akhir ---
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
            
            if ($nilaiAkhir >= 80) $grade = 'A';
            elseif ($nilaiAkhir >= 70) $grade = 'B';
            elseif ($nilaiAkhir >= 60) $grade = 'C';
            elseif ($nilaiAkhir >= 50) $grade = 'D';
            else $grade = 'E';
            
            $nilai->update(['nilai_akhir' => $nilaiAkhir, 'grade' => $grade]);
        } else {
            $nilai->update(['nilai_akhir' => null, 'grade' => null]);
        }
    }

    /**
     * Method 'render' menampilkan view.
     */
    public function render()
    {
        return view('livewire.dosen.gradebook');
    }
}