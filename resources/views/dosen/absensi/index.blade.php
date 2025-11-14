<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Absensi: {{ $matkul->nama_matkul }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <x-matkul-tabs :matkul="$matkul" />

            @livewire('dosen.absensi-manager', ['matkul' => $matkul])

            <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Histori Sesi Perkuliahan</h3>
                    @if ($sesiList->isEmpty())
                        <p>Belum ada sesi yang dibuat.</p>
                    @else
                        <ul class="divide-y divide-gray-200">
                            @foreach ($sesiList as $sesi)
                                <li>
                                    <a href="{{ route('dosen.absensi.show', [$matkul, $sesi]) }}" 
                                    class="block py-3 px-2 hover:bg-gray-50 rounded-md">
                                        <p class="font-medium">Pertemuan ke-{{ $sesi->pertemuan_ke }}</p>
                                        <small class="text-gray-600">
                                            Dibuat pada: {{ $sesi->created_at->format('d M Y, H:i') }}
                                        </small>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>