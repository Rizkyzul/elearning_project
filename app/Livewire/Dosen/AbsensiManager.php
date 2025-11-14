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
    
    // Properti untuk menyimpan sesi yang SEDANG AKTIF
    public ?SesiPerkuliahan $sesiAktif = null;
    public ?string $qrCodeMasuk = null;
    public ?string $qrCodeKeluar = null;
    public ?string $timerMasuk = null;
    public ?string $timerKeluar = null;

    /**
     * Mount berjalan saat load.
     * Kita cek apakah ada sesi yang belum ditutup.
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
        // Cari sesi yang masih valid (misal: dibuat dalam 6 jam terakhir)
        // dan belum ada code keluar
        $this->sesiAktif = SesiPerkuliahan::where('matkul_id', $this->matkul->id)
                            ->where('created_at', '>', now()->subHours(6))
                            ->whereNull('code_keluar') // Belum ditutup
                            ->orderBy('created_at', 'desc')
                            ->first();

        $this->generateQrCodes();
    }

    /**
     * Method helper untuk generate QR (jika sesi aktif ada)
     */
    public function generateQrCodes()
    {
        if ($this->sesiAktif) {
            // Generate QR Code Masuk
            if ($this->sesiAktif->code_masuk && $this->sesiAktif->expires_at_masuk > now()) {
                // Gunakan \SimpleSoftwareIO\QrCode\Facades\QrCode
                $this->qrCodeMasuk = QrCode::size(250)
                        ->generate($this->sesiAktif->code_masuk);
                $this->timerMasuk = $this->sesiAktif->expires_at_masuk->toIso8601String();
            } else {
                $this->qrCodeMasuk = null; // Hangus
                $this->timerMasuk = null;
            }

            // Generate QR Code Keluar (jika sudah ada)
            if ($this->sesiAktif->code_keluar && $this->sesiAktif->expires_at_keluar > now()) {
                $this->qrCodeKeluar = QrCode::size(250)
                        ->generate($this->sesiAktif->code_keluar);
                $this->timerKeluar = $this->sesiAktif->expires_at_keluar->toIso8601String();
            } else {
                $this->qrCodeKeluar = null; // Hangus
                $this->timerKeluar = null;
            }
        }
    }

    
    public function mulaiSesi()
    {
        /** @var \App\Models\User $user */ // <-- TAMBAHKAN BARIS INI
        $user = Auth::user();
        
        // 'Garis merah' di .load() akan hilang
        $user->load('dosenProfile'); 
        
        $dosenProfile = $user->dosenProfile;

        // JIKA PROFIL DOSEN TIDAK DITEMUKAN (BUG BESAR)
        if (!$dosenProfile) {
            session()->flash('error', 'FATAL: Profil Dosen tidak ditemukan untuk user ini. Hubungi Admin.');
            return;
        }

        // Hitung ini pertemuan ke berapa
        $pertemuanKe = $this->matkul->sesiPerkuliahan()->count() + 1;

        try {
            // Buat sesi baru di database
            $this->sesiAktif = SesiPerkuliahan::create([
                'matkul_id' => $this->matkul->id,
                'dosen_id' => $dosenProfile->id, // Gunakan variabel yang sudah aman
                'pertemuan_ke' => $pertemuanKe,
                'code_masuk' => Str::random(10), // Kode unik
                'expires_at_masuk' => now()->addMinutes(15), // Berlaku 15 menit
            ]);

            // Generate ulang QR Code
            $this->generateQrCodes();

        } catch (\Exception $e) {
            // Tangkap error apapun (misal: error database)
            session()->flash('error', 'Gagal membuat sesi: ' . $e->getMessage());
        }
    }
    /**
     * Aksi ini dipanggil saat Dosen klik tombol "BUKA ABSEN KELUAR".
     */
    public function bukaAbsenKeluar()
    {
        if ($this->sesiAktif) {
            $this->sesiAktif->update([
                'code_keluar' => Str::random(10),
                'expires_at_keluar' => now()->addMinutes(15),
            ]);

            // Generate ulang QR Code (sekarang QR Keluar akan muncul)
            $this->generateQrCodes();
        }
    }

    /**
     * Aksi ini dipanggil saat Dosen klik tombol "TUTUP SESI".
     */
    public function tutupSesi()
    {
        if ($this->sesiAktif) {
            // Kita set hangus semua
            $this->sesiAktif->update([
                'expires_at_masuk' => now(),
                'expires_at_keluar' => now(),
            ]);
        }
        
        // Kosongkan semua properti
        $this->sesiAktif = null;
        $this->qrCodeMasuk = null;
        $this->qrCodeKeluar = null;
        $this->timerMasuk = null;
        $this->timerKeluar = null;

        // Refresh halaman (via redirect) untuk update histori
        return $this->redirect(route('dosen.absensi.index', $this->matkul));
    }


    public function render()
    {
        return view('livewire.dosen.absensi-manager');
    }
}