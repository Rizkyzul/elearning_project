<?php

namespace App\Livewire\Dosen;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Mahasiswa;
use App\Models\Kelas;

class MahasiswaTable extends Component
{
    use WithPagination;

    // Properti filter (ini sudah benar)
    public string $search = '';
    public string $filterKelas = '';
    public string $filterAngkatan = '';

    // Kita HAPUS method mount()

    /**
     * Hook ini berjalan setiap kali properti publik berubah.
     */
    public function updated()
    {
        // Reset pagination ke halaman 1 setiap kali filter berubah
        $this->resetPage();
    }

    /**
     * Render view (dan ambil data).
     */
    public function render()
    {
        // --- INI PERBAIKANNYA ---
        // 1. Ambil data dropdown SETIAP KALI render
        // Ini memastikan Kelas/Angkatan baru dari import akan muncul
        $semuaKelas = Kelas::orderBy('nama_kelas')->get();
        $semuaAngkatan = Mahasiswa::select('angkatan')
                                    ->distinct()
                                    ->orderBy('angkatan', 'desc')
                                    ->get();
        // -------------------------

        // 2. Mulai query
        $query = Mahasiswa::query()
                        ->with('user', 'kelas')
                        ->join('users', 'mahasiswa.user_id', '=', 'users.id')
                        ->select('mahasiswa.*');

        // Terapkan filter pencarian
        $query->when($this->search, function ($q) {
            $q->where('users.name', 'like', '%' . $this->search . '%')
              ->orWhere('mahasiswa.nim', 'like', '%' . $this->search . '%');
        });

        // Terapkan filter kelas
        $query->when($this->filterKelas, function ($q) {
            $q->where('mahasiswa.kelas_id', $this->filterKelas);
        });

        // Terapkan filter angkatan
        $query->when($this->filterAngkatan, function ($q) {
            $q->where('mahasiswa.angkatan', $this->filterAngkatan);
        });

        // Ambil data dengan pagination
        $mahasiswaList = $query->orderBy('mahasiswa.nim', 'asc')->paginate(25);

        // 3. Kirim SEMUA data (list dan dropdown) ke view
        return view('livewire.dosen.mahasiswa-table', [
            'mahasiswaList' => $mahasiswaList,
            'semuaKelas' => $semuaKelas,
            'semuaAngkatan' => $semuaAngkatan,
        ]);
    }
}