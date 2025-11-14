<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Dosen') }}
        </h2>
    </x-slot>
    
    {{-- selamat datang --}}
           <div class="bg-gradient-to-r from-blue-500 to-blue-700 overflow-hidden shadow-sm sm:rounded-lg mb-4">
                <div class="p-6 text-white">
                    Selamat Datang, {{ auth()->user()->name }}
                </div>
            </div>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            

            <h3 class="text-lg font-semibold text-gray-800 mb-4">Pilih Mata Kuliah</h3>

            @if($mataKuliah->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        Anda belum terdaftar mengajar mata kuliah apapun.
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                    @foreach ($mataKuliah as $matkul)
                        <a href="{{ route('dosen.materi.index', $matkul) }}" class="block bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition duration-200">
                            <div class="p-6 text-gray-900">
                                <h4 class="font-semibold text-lg">{{ $matkul->nama_matkul }}</h4>
                                <p class="text-gray-600">{{ $matkul->kode_matkul }}</p>
                                <span class="mt-2 inline-block text-sm text-blue-600">Kelola Materi &raquo;</span>
                            </div>
                        </a>
                    @endforeach

                </div>
            @endif

        </div>
    </div>
</x-app-layout>