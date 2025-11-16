<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Penilaian: {{ $matkul->nama_matkul }}
        </h2>
    </x-slot>

    <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                
                <x-matkul-tabs :matkul="$matkul" />

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-4">Pilih Kelas untuk Dikelola</h3>
                        <p class="text-sm text-gray-600 mb-4">Silakan pilih kelas untuk melihat/menginput nilai Tugas, UTS, dan UAS.</p>
                        
                        @if($daftarKelas->isEmpty())
                            <p class="text-gray-500">Belum ada materi/tugas yang ditugaskan ke kelas manapun untuk mata kuliah ini.</p>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                @foreach($daftarKelas as $kelas)
                                    <a href="{{ route('dosen.nilai.showKelas', ['matkul' => $matkul, 'kelas' => $kelas]) }}" 
                                       class="block p-4 bg-indigo-50 border border-indigo-200 rounded-lg hover:bg-indigo-100 hover:shadow-md transition">
                                        <p class="font-semibold text-indigo-800">{{ $kelas->nama_kelas }}</p>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
</x-app-layout>