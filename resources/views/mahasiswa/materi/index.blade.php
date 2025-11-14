<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Materi: {{ $matkul->nama_matkul }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <x-matkul-tabs-mahasiswa :matkul="$matkul" />
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if ($materiList->isEmpty())
                        <p>Belum ada materi untuk mata kuliah ini.</p>
                    @else
                        <ul class="divide-y divide-gray-200">
                            @foreach ($materiList as $materi)
                                <li class="py-4 flex justify-between items-center">
                                    <div>
                                        <h4 class="text-lg font-medium">{{ $materi->judul }}</h4>
                                        <p class="text-sm text-gray-600">{{ $materi->deskripsi }}</p>
                                        <small class="text-xs text-gray-500">Di-upload: {{ $materi->created_at->format('d M Y, H:i') }}</small>
                                    </div>
                                    <div>
                                        <a href="{{ Storage::url(str_replace('public/', '', $materi->file_path)) }}" target="_blank" 
                                           class="px-3 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                                            Lihat Materi
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