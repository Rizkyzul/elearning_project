<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Penilaian: {{ $matkul->nama_matkul }} (Kelas: {{ $kelas->nama_kelas }})
        </h2>
    </x-slot>

    <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                
                <x-matkul-tabs :matkul="$matkul" />

                <div class="mb-4">
                    <a href="{{ route('dosen.nilai.index', $matkul) }}" class="text-sm text-gray-600 hover:text-gray-900">
                        &larr; Kembali ke Pilihan Kelas
                    </a>
                </div>

                <div class="mb-4 flex gap-4">
                    <a href="{{ route('dosen.nilai.export.excel', ['matkul' => $matkul, 'kelas' => $kelas]) }}" 
                       class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                        Export Excel (Kelas Ini)
                    </a>
                    <a href="{{ route('dosen.nilai.export.pdf', ['matkul' => $matkul, 'kelas' => $kelas]) }}" 
                       target="_blank"
                       class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700">
                        Export PDF (Kelas Ini)
                    </a>
                </div>
                
                @livewire('dosen.gradebook', ['matkul' => $matkul, 'kelas' => $kelas])

            </div>
        </div>
</x-app-layout>