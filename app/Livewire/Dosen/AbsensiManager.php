<?php

namespace App\Livewire\Dosen;

use Livewire\Component;
use App\Models\Matkul;
use App\Models\SesiPerkuliahan;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AbsensiManager extends Component
{
    public Matkul $matkul;
    
    public ?SesiPerkuliahan $sesiAktif = null;
    public ?string $qrCodeMasuk = null;
    public ?string $qrCodeKeluar = null;
    public ?string $timerMasuk = null;
    public ?string $timerKeluar = null;
    public int $pertemuanKe;

    /**
     * Mount berjalan saat load.
     */
    public function mount(Matkul $matkul)
    {
        $this->matkul = $matkul;
        $this->loadSesiAktif();
    }

    /**
     * Method helper untuk mencari sesi aktif.
     */
    public function loadSesiAktif()
    {
        $this->sesiAktif = SesiPerkuliahan::where('matkul_id', $this->matkul->id)
                            ->where('created_at', '>', now()->subHours(6))
                            ->whereNull('code_keluar')
                            ->orderBy('created_at', 'desc')
                            ->first();
        
        $this->pertemuanKe = $this->matkul->sesiPerkuliahan()
                                ->count() + 1;

        $this->generateQrCodes();
    }

    /**
     * Generate QR
     */
    public function generateQrCodes()
    {
        if ($this->sesiAktif) {
            // Generate QR Code Masuk
            if ($this->sesiAktif->code_masuk && $this->sesiAktif->expires_at_masuk > now()) {
                $this->qrCodeMasuk = QrCode::size(250)->generate($this->sesiAktif->code_masuk);
                $this->timerMasuk = $this->sesiAktif->expires_at_masuk->toIso8601String();
            } else { 
                $this->qrCodeMasuk = null; 
                $this->timerMasuk = null; 
            }
            
            // Generate QR Code Keluar (INI PERBAIKAN TYPO-NYA)
            if ($this->sesiAktif->code_keluar && $this->sesiAktif->expires_at_keluar > now()) {
                $this->qrCodeKeluar = QrCode::size(250)->generate($this->sesiAktif->code_keluar); // <-- SUDAH DIPERBAIKI
                $this->timerKeluar = $this->sesiAktif->expires_at_keluar->toIso8601String();
            } else { 
                $this->qrCodeKeluar = null; 
                $this->timerKeluar = null; 
            }
        }
    }

    /**
     * Aksi ini dipanggil saat Dosen klik tombol "MULAI SESI".
     */
    public function mulaiSesi()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->load('dosenProfile'); 
        $dosenProfile = $user->dosenProfile;

        if (!$dosenProfile) {
            session()->flash('error', 'FATAL: Profil Dosen tidak ditemukan.');
            return;
        }

        try {
            $this->sesiAktif = SesiPerkuliahan::create([
                'matkul_id' => $this->matkul->id,
                'dosen_id' => $dosenProfile->id,
                'pertemuan_ke' => $this->pertemuanKe,
                'code_masuk' => Str::random(10),
                'expires_at_masuk' => now()->addMinutes(15),
            ]);
            $this->generateQrCodes(); // <-- Perbaikan Typo
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal membuat sesi: ' . $e->getMessage());
        }
    }
    
    /**
     * Buka Absen Keluar
     */
    public function bukaAbsenKeluar() // <-- Perbaikan Typo
    {
        if ($this->sesiAktif) {
            $this->sesiAktif->update(['code_keluar' => Str::random(10), 'expires_at_keluar' => now()->addMinutes(15)]);
            $this->generateQrCodes();
        }
    }

    /**
     * Tutup Sesi
     */
    public function tutupSesi() // <-- Perbaikan Typo
    {
        if ($this->sesiAktif) {
            $this->sesiAktif->update(['expires_at_masuk' => now(), 'expires_at_keluar' => now()]); // <-- Perbaikan Typo
        }
        $this->sesiAktif = null; // <-- Perbaikan Typo
        $this->qrCodeMasuk = null; 
        $this->qrCodeKeluar = null;
        $this->timerMasuk = null; 
        $this->timerKeluar = null;

        return $this->redirect(route('dosen.absensi.index', $this->matkul));
    }

    /**
     * Render
     */
    public function render()
    {
        return view('livewire.dosen.absensi-manager');
    }
}