<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tugas: {{ $matkul->nama_matkul }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <x-matkul-tabs-mahasiswa :matkul="$matkul" />

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if ($tugasList->isEmpty())
                        <p>Belum ada tugas untuk mata kuliah ini di kelas Anda.</p>
                    @else
                        <ul class="divide-y divide-gray-200">
                            @foreach ($tugasList as $tugas)
                                <li class="py-4 flex justify-between items-center">
                                    <div>
                                        <h4 class="text-lg font-medium">{{ $tugas->judul }}</h4>
                                        <p class="text-sm text-gray-600">{{ $tugas->deskripsi }}</p>
                                        <small class="text-xs text-red-600 font-semibold">Deadline: {{ \Carbon\Carbon::parse($tugas->deadline)->format('d M Y, H:i') }}</small>
                                    </div>
                                    <div>
                                        <a href="{{ route('mahasiswa.tugas.show', [$matkul, $tugas]) }}" 
                                           class="px-3 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                                            Lihat Detail & Submit
                                        </a>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>