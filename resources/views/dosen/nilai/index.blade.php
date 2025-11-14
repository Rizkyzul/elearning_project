<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Penilaian: {{ $matkul->nama_matkul }}
        </h2>
    </x-slot>

    <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <x-matkul-tabs :matkul="$matkul" />

                <div class="mb-4 flex gap-4">
                    <a href="{{ route('dosen.nilai.export.excel', $matkul) }}" 
                    class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                        Export Excel
                    </a>
                    <a href="{{ route('dosen.nilai.export.pdf', $matkul) }}" 
                    target="_blank"
                    class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700">
                        Export PDF
                    </a>
                </div>
                @livewire('dosen.gradebook', ['matkul' => $matkul])
            </div>
        </div>
</x-app-layout>