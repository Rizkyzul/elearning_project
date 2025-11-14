<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900">

        @if (session()->has('error'))
            <div class="mb-4 p-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <div x-data="{ 
                timerMasuk: null, 
                timerKeluar: null,
                sisaMasuk: '00:00', 
                sisaKeluar: '00:00',

                // ===== INI FUNGSI YANG HILANG (SUDAH DIPERBAIKI) =====
                setTimer(jenis, isoTime) {
                    if (!isoTime) return;
                    
                    let targetTime = new Date(isoTime).getTime();
                    
                    // Hapus interval lama jika ada
                    if (this[jenis === 'masuk' ? 'timerMasuk' : 'timerKeluar']) {
                        clearInterval(this[jenis === 'masuk' ? 'timerMasuk' : 'timerKeluar']);
                    }

                    let interval = setInterval(() => {
                        let now = new Date().getTime();
                        let distance = targetTime - now;
                        
                        if (distance < 0) {
                            clearInterval(interval);
                            this[jenis === 'masuk' ? 'sisaMasuk' : 'sisaKeluar'] = 'HANGUS';
                            // Otomatis refresh data dari server saat hangus
                            @this.call('loadSesiAktif');
                        } else {
                            let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                            let seconds = Math.floor((distance % (1000 * 60)) / 1000);
                            this[jenis === 'masuk' ? 'sisaMasuk' : 'sisaKeluar'] = ('0' + minutes).slice(-2) + ':' + ('0' + seconds).slice(-2);
                        }
                    }, 1000);

                    this[jenis === 'masuk' ? 'timerMasuk' : 'timerKeluar'] = interval;
                }
                // =======================================================
            }"
             x-init="
                setTimer('masuk', '{{ $timerMasuk }}');
                setTimer('keluar', '{{ $timerKeluar }}');
             "
             wire:key="{{ $sesiAktif ? $sesiAktif->id : 'sesi-kosong' }}"> @if (!$sesiAktif)
                
                <div wire:key="sesi-form-kosong">
                    <h3 class="text-lg font-semibold">Mulai Sesi Baru</h3>
                    <p class="mb-4">Klik tombol di bawah untuk memulai sesi absensi baru (pertemuan ke-{{ $matkul->sesiPerkuliahan()->count() + 1 }}).</p>
                    
                    <button wire:click="mulaiSesi" 
                            wire:loading.attr="disabled"
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        <span wire:loading.remove wire:target="mulaiSesi">Mulai Sesi Absensi</span>
                        <span wire:loading wire:target="mulaiSesi">Memulai...</span>
                    </button>
                </div>
            
            @else

                <div wire:key="sesi-form-aktif-{{ $sesiAktif->id }}">
                    <h3 class="text-lg font-semibold">Sesi Aktif (Pertemuan ke-{{ $sesiAktif->pertemuan_ke }})</h3>
                    
                    <div class="flex flex-wrap gap-6 mt-4">
                        
                        <div class="text-center">
                            <h4 class="font-medium">QR Code Absen MASUK</h4>
                            @if ($qrCodeMasuk)
                                <div class="p-4 border rounded-md">
                                    {!! $qrCodeMasuk !!}
                                </div>
                                <p class="text-xl font-bold text-red-600 mt-2" x-text="sisaMasuk"></p>
                            @else
                                <div class="p-4 border rounded-md bg-gray-100 flex items-center justify-center w-[282px] h-[282px]">
                                    <p class="text-gray-500">Absen Masuk Sudah Hangus</p>
                                </div>
                            @endif
                        </div>

                        <div class="text-center">
                            <h4 class="font-medium">QR Code Absen KELUAR</h4>
                            @if ($qrCodeKeluar)
                                <div class="p-4 border rounded-md">
                                    {!! $qrCodeKeluar !!}
                                </div>
                                <p class="text-xl font-bold text-red-600 mt-2" x-text="sisaKeluar"></p>
                            
                            @elseif (!$qrCodeMasuk) <button wire:click="bukaAbsenKeluar" wire:loading.attr="disabled"
                                        class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 h-[282px] w-[282px]">
                                    Buka Absen Keluar
                                </button>
                            
                            @else
                                <div class="p-4 border rounded-md bg-gray-100 flex items-center justify-center w-[282px] h-[282px]">
                                    <p class="text-gray-500">(Tunggu absen masuk selesai)</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="mt-6 border-t pt-4">
                        <button wire:click="tutupSesi" wire:loading.attr="disabled"
                                class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                            Tutup Sesi (Selesai Kuliah)
                        </button>
                    </div>
                </div> @endif

        </div>
    </div>
</div>