@props(['matkul'])

<div class="mb-6">
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">

            <a href="{{ route('mahasiswa.materi.index', $matkul) }}" 
               class="{{ request()->routeIs('mahasiswa.materi.*') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} 
                      whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Materi
            </a>

            <a href="{{ route('mahasiswa.tugas.index', $matkul) }}"
               class="{{ request()->routeIs('mahasiswa.tugas.*') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} 
                      whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Tugas
            </a>

            <a href="{{ route('mahasiswa.nilai.index', $matkul) }}" 
               class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300
                      whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Nilai Saya
            </a>

        </nav>
    </div>
</div>