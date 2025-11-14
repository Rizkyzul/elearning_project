@props(['matkul'])

<div class="mb-6">
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">

            <a href="{{ route('dosen.materi.index', $matkul) }}" 
               class="{{ request()->routeIs('dosen.materi.*') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} 
                      whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Materi
            </a>

            <a href="{{ route('dosen.tugas.index', $matkul) }}"
               class="{{ request()->routeIs('dosen.tugas.*') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} 
                      whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Tugas
            </a>

            <a href="{{ route('dosen.nilai.index', $matkul) }}"
            class="{{ request()->routeIs('dosen.nilai.*') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} 
                    whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Penilaian
            </a>

            <a href="{{ route('dosen.absensi.index', $matkul) }}"
            class="{{ request()->routeIs('dosen.absensi.*') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} 
                    whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Absensi
            </a>

        </nav>
    </div>
</div>