<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Nilai Tugas: {{ $tugas->judul }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <a href="{{ route('dosen.tugas.show', [$matkul, $tugas]) }}" class="text-sm text-gray-600 hover:text-gray-900 mb-4 inline-block">
                &larr; Kembali ke Daftar Submission
            </a>

            @if (session('success'))
                <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-semibold">{{ $jawaban->mahasiswa->user->name }}</h3>
                        <p class="text-sm text-gray-600">{{ $jawaban->mahasiswa->nim }}</p>
                        <p class="text-sm text-gray-600 mt-1">Submitted: {{ $jawaban->submitted_at->format('d M Y, H:i') }}</p>
                    </div>

                    <a href="{{ Storage::url($jawaban->file_path) }}" target="_blank" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Lihat Jawaban
                    </a>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-xl font-semibold mb-4">Input Nilai & Feedback</h3>

                    <form method="POST" action="{{ route('dosen.tugas.grade.store', [$matkul, $tugas, $jawaban]) }}">
                        @csrf

                        <div>
                            <x-input-label for="nilai_tugas" :value="__('Nilai Tugas Individual (0-100)')" />
                            <x-text-input id="nilai_tugas" class="block mt-1 w-full md:w-1/2" 
                                        type="number" 
                                        name="nilai_tugas" 
                                        min="0" max="100"
                                        {{-- GANTI DARI $nilai->nilai_tugas MENJADI $jawaban->nilai_dosen --}}
                                        :value="old('nilai_tugas', $jawaban->nilai_dosen ?? '')" 
                                        required />
                            <x-input-error :messages="$errors->get('nilai_tugas')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="feedback" :value="__('Feedback / Catatan')" />
                            <textarea id="feedback" name="feedback" rows="4" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('feedback', $nilai->catatan ?? '') }}</textarea>
                            <x-input-error :messages="$errors->get('feedback')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button>
                                {{ __('Simpan Nilai') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>