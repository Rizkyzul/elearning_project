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
                             class="w-full rounded-xl border border-gray-200 shadow-inner bg-gray-50">
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
        </div>
    </div>

    @push('scripts')
    <script src="https://unpkg.com/html5-qrcode@2.0.9/dist/html5-qrcode.min.js"></script>

    <script>
        function onScanSuccess(decodedText, decodedResult) {
            document.getElementById('qr-code-result').value = decodedText;
            document.getElementById('scan-form').submit();
            html5QrcodeScanner.clear();
        }

        function onScanError(errorMessage) {
            // ignored
        }

        var html5QrcodeScanner = new Html5QrcodeScanner(
            "qr-reader",
            { fps: 10, qrbox: 260 }
        );

        html5QrcodeScanner.render(onScanSuccess, onScanError);
    </script>
    @endpush

</x-app-layout>
