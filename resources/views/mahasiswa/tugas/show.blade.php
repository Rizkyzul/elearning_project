<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Submit Tugas: {{ $tugas->judul }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            
            <a href="{{ route('mahasiswa.tugas.index', $matkul) }}" class="text-sm text-gray-600 hover:text-gray-900 mb-4 inline-block">
                &larr; Kembali ke Daftar Tugas
            </a>

            @if (session('success'))
                <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-4 p-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold">{{ $tugas->judul }}</h3>
                    <p class="mt-2 text-sm text-gray-600">{!! nl2br(e($tugas->deskripsi)) !!}</p>
                    <p class="mt-4 text-sm font-medium text-red-600">
                        Deadline: {{ \Carbon\Carbon::parse($tugas->deadline)->format('d M Y, H:i') }}
                    </p>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-4">Status Jawaban Anda</h3>

                    @if ($jawaban)
                        @if (!is_null($jawaban->nilai_dosen))
                            <div class="p-4 bg-green-100 rounded-md border-2 border-green-400">
                                <h4 class="text-2xl font-bold text-green-800">
                                    NILAI TUGAS INI: {{ $jawaban->nilai_dosen }} / 100
                                </h4>
                                <p class="text-sm text-gray-700 mt-2">
                                    Tugas ini sudah selesai dinilai oleh dosen.
                                </p>
                            </div>
                        @else
                            <div class="p-4 bg-blue-100 rounded-md border-2 border-blue-400">
                                <p class="font-semibold text-blue-800">Jawaban Anda telah diterima.</p>
                                <p class="text-sm text-blue-700 mt-1">Menunggu penilaian Dosen.</p>
                            </div>
                        @endif

                        <div class="mt-4 text-sm text-gray-700">
                            <p>File Anda: 
                                <a href="{{ Storage::url(str_replace('public/', '', $jawaban->file_path)) }}" target="_blank" class="text-indigo-600 hover:underline font-medium">
                                    {{ basename($jawaban->file_path) }}
                                </a>
                            </p>
                            <p class="text-xs text-gray-500">
                                Waktu Submit: {{ $jawaban->submitted_at->format('d M Y, H:i') }}
                            </p>
                        </div>
                        
                        @if (now() <= $tugas->deadline)
                            <p class="text-sm text-gray-600 mt-4">Anda bisa submit ulang (file lama akan ditimpa):</p>
                            @include('mahasiswa.tugas._form-upload')
                        @endif

                    @elseif (now() > $tugas->deadline)
                        <div class="p-4 bg-red-100 rounded-md border-2 border-red-400">
                            <p class="font-semibold text-red-800">DEADLINE LEWAT.</p>
                            <p class="text-sm text-red-700 mt-1">Anda tidak submit tugas ini dan waktu sudah habis.</p>
                        </div>

                    @else
                        <p class="text-sm text-gray-600 mb-4">Anda belum submit tugas ini.</p>
                        @include('mahasiswa.tugas._form-upload')
                    @endif
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>