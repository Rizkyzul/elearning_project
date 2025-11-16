<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 tracking-wide">
            {{ __('Scan QR Code Absensi') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">

            {{-- Alert --}}
            @if (session('success'))
                <div class="mb-4 flex items-center gap-3 p-4 text-green-800 bg-green-100 border border-green-200 rounded-xl shadow-sm">
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                <div class="mb-4 flex items-center gap-3 p-4 text-red-800 bg-red-100 border border-red-200 rounded-xl shadow-sm">
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
            @endif
            
            <div class="bg-white shadow-lg rounded-2xl border border-gray-100 overflow-hidden">
                <div class="p-6 text-gray-900">
                    <div class="mb-5">
                        <h3 class="text-lg font-semibold text-gray-700 mb-1">
                            Arahkan Kamera
                        </h3>
                        <p class="text-sm text-gray-500 leading-relaxed">
                            Silakan arahkan kamera perangkat Anda ke QR Code yang diberikan oleh dosen.
                        </p>
                    </div>

                    <div class="relative">
                        <div id="qr-reader" 
                             class="w-full rounded-xl border border-gray-200 shadow-inner bg-gray-90">
                        </div>
                    </div>

                    <form id="scan-form" 
                          action="{{ route('mahasiswa.absensi.store') }}" 
                          method="POST" 
                          class="hidden">
                        @csrf
                        <input type="hidden" id="qr-code-result" name="qr_code">
                    </form>
                </div>
            </div>


            <div class="mt-8">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">
                    Histori Absensi Anda
                </h3>
                
                <div class="bg-white shadow-lg rounded-2xl border border-gray-100 overflow-hidden">
                    <div class="p-6 text-gray-900">
                        @if ($historiAbsen->isEmpty())
                            <p class="text-center text-gray-500">Anda belum memiliki histori absensi.</p>
                        @else
                            <ul class="divide-y divide-gray-200">
                                @foreach ($historiAbsen as $absen)
                                    <li class="py-4 flex justify-between items-center">
                                        <div>
                                            <p class="font-semibold text-gray-800">
                                                {{ $absen->sesiPerkuliahan->matkul->nama_matkul ?? 'Mata Kuliah Dihapus' }}
                                            </p>
                                            <p class="text-sm text-gray-600">
                                                Pertemuan ke-{{ $absen->sesiPerkuliahan->pertemuan_ke }} 
                                                <span class="text-gray-400 mx-1">|</span>
                                                {{ $absen->created_at->format('d M Y') }}
                                            </p>
                                        </div>
                                        
                                        <div>
                                            @if ($absen->scan_masuk && $absen->scan_keluar)
                                                @if ($absen->status == 'terlambat')
                                                    <span class="px-2 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full">Terlambat</span>
                                                @else
                                                    <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Hadir</span>
                                                @endif
                                            @elseif ($absen->scan_masuk && !$absen->scan_keluar)
                                                <span class="px-2 py-1 text-xs font-semibold text-blue-800 bg-blue-100 rounded-full">Masuk Saja</span>
                                            @elseif (!$absen->scan_masuk && $absen->scan_keluar)
                                                <span class="px-2 py-1 text-xs font-semibold text-purple-800 bg-purple-100 rounded-full">Keluar Saja</span>
                                            @else
                                                <span class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">Absen</span>
                                            @endif
                                        </div>
                                        </li>
                                @endforeach
                            </ul>
                            
                            <div class="mt-6">
                                {{ $historiAbsen->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
        </div>
    </div>

    @push('scripts')
    <script src="https://unpkg.com/html5-qrcode@2.0.9/dist/html5-qrcode.min.js"></script>

    <script>
        function onScanSuccess(decodedText, decodedResult) {
            // Cek jika scanner masih aktif sebelum submit
            if (document.getElementById('scan-form')) {
                document.getElementById('qr-code-result').value = decodedText;
                document.getElementById('scan-form').submit();
                
                // Hentikan scanner
                html5QrcodeScanner.clear().catch(err => {
                    console.warn("Gagal membersihkan html5QrcodeScanner: ", err);
                });
            }
        }

        function onScanError(errorMessage) {
            // ignored
        }

        // Buat variabel scanner di scope yang lebih luas
        var html5QrcodeScanner;
        
        // Pastikan DOM sudah siap
        document.addEventListener('DOMContentLoaded', (event) => {
            if (document.getElementById('qr-reader')) {
                html5QrcodeScanner = new Html5QrcodeScanner(
                    "qr-reader",
                    { fps: 10, qrbox: 260 }
                );
                html5QrcodeScanner.render(onScanSuccess, onScanError);
            }
        });
    </script>
    @endpush

</x-app-layout>