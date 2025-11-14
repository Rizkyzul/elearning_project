<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Materi: {{ $matkul->nama_matkul }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <x-matkul-tabs :matkul="$matkul" />

            <div class="mb-4">
                <a href="{{ route('dosen.materi.create', $matkul) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                    + Tambah Materi
                </a>
            </div>
            
            @if (session('success'))
                <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if ($materiList->isEmpty())
                        <p>Belum ada materi untuk mata kuliah ini.</p>
                    @else
                        <ul class="divide-y divide-gray-200">
                            @foreach ($materiList as $materi)
                                <li class="py-4 flex justify-between items-start">
                                    <div>
                                        <h4 class="text-lg font-medium">{{ $materi->judul }}</h4>
                                        <p class="text-sm text-gray-600">{{ $materi->deskripsi }}</p>
                                        <small class="text-xs text-gray-500">Di-upload: {{ $materi->created_at->format('d M Y, H:i') }}</small>
                                        
                                        <div class="mt-2 flex flex-wrap gap-2">
                                            @forelse($materi->kelas as $kelas)
                                                <span class="px-2 py-0.5 text-xs font-medium bg-indigo-100 text-indigo-800 rounded-full">
                                                    {{ $kelas->nama_kelas }}
                                                </span>
                                            @empty
                                                <span class="px-2 py-0.5 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">
                                                    (Belum ditugaskan ke kelas)
                                                </span>
                                            @endforelse
                                        </div>
                                        </div>

                                    <div class="flex flex-col items-end space-y-2 ml-4">
                                        <a href="{{ Storage::url(str_replace('public/', '', $materi->file_path)) }}" target="_blank" 
                                           class="px-3 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                                            Download
                                        </a>
                                        
                                        <form action="{{ route('dosen.materi.destroy', [$matkul, $materi]) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus materi ini?');">
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