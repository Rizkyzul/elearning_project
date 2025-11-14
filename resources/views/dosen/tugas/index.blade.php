<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tugas: {{ $matkul->nama_matkul }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <x-matkul-tabs :matkul="$matkul" />

            <div class="mb-4">
                <a href="{{ route('dosen.tugas.create', $matkul) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                    + Tambah Tugas
                </a>
            </div>

            @if (session('success'))
                <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
               <div class="p-6 text-gray-900">
                    
            
  @if ($tugasList->isEmpty())
                            <p>Belum ada tugas untuk mata kuliah ini.</p>
                        @else
                            <ul class="divide-y divide-gray-200">
                                @foreach ($tugasList as $tugas)
                                    <li class="py-4 flex justify-between items-start">
                                        
                                        <div class="flex-1">
                                            <h4 class="text-lg font-medium">{{ $tugas->judul }}</h4>
                                            <p class="text-sm text-gray-600">{{ $tugas->deskripsi }}</p>
                                            <small class="text-xs text-red-600 font-semibold">Deadline: {{ \Carbon\Carbon::parse($tugas->deadline)->format('d M Y, H:i') }}</small>
                                        </div>

                                        <div class="flex flex-col items-end space-y-2 ml-4">
                                            
                                            <div class="text-right">
                                                <p class="text-xs font-semibold text-blue-600">
                                                    Submitted: {{ $tugas->jawaban_count }} / {{ $totalMahasiswa }}
                                                </p>
                                                @if($tugas->jawaban_count > 0)
                                                   <a href="{{ route('dosen.tugas.show', [$matkul, $tugas]) }}" 
                                                        class="text-xs text-green-600 hover:text-green-800 font-semibold underline">
                                                        Lihat {{ $tugas->jawaban_count }} Jawaban
                                                    </a>
                                                @else
                                                    <p class="text-xs text-gray-500">Belum ada yang submit.</p>
                                                @endif
                                            </div>

                                            <form action="{{ route('dosen.tugas.destroy', [$matkul, $tugas]) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus tugas ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-3 py-1 bg-red-600 text-white text-xs rounded hover:bg-red-700">Hapus</button>
                                            </form>
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