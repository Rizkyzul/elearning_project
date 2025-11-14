<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Rekap Absensi: {{ $matkul->nama_matkul }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <a href="{{ route('dosen.absensi.index', $matkul) }}" class="text-sm text-gray-600 hover:text-gray-900 mb-4 inline-block">
                &larr; Kembali ke Daftar Sesi
            </a>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold">Pertemuan ke-{{ $sesi->pertemuan_ke }}</h3>
                    <p class="text-sm text-gray-600">
                        Tanggal: {{ $sesi->created_at->format('d M Y') }}
                    </p>
                    <p class="text-sm text-gray-600">
                        Total Hadir: {{ $rekapAbsensi->where('status', 'hadir')->count() }} | 
                        Terlambat: {{ $rekapAbsensi->where('status', 'terlambat')->count() }} | 
                        Absen: {{ $rekapAbsensi->where('status', 'absen')->count() }}
                    </p>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-4">Daftar Kehadiran Mahasiswa</h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">NIM</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Waktu Scan Masuk</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Waktu Scan Keluar</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($rekapAbsensi as $rekap)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $rekap['mahasiswa']->nim }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $rekap['mahasiswa']->user->name }}</td>
                                     <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($rekap['status'] == 'hadir')
                                                <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Hadir</span>
                                            
                                            @elseif ($rekap['status'] == 'masuk_saja')
                                                <span class="px-2 py-1 text-xs font-semibold text-blue-800 bg-blue-100 rounded-full">Masuk Saja</span>
                                            @elseif ($rekap['status'] == 'terlambat')
                                                <span class="px-2 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full">Terlambat</span>
                                            @elseif ($rekap['status'] == 'keluar_tanpa_masuk')
                                                <span class="px-2 py-1 text-xs font-semibold text-purple-800 bg-purple-100 rounded-full">Keluar Saja</span>
                                            @else
                                                <span class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">Absen</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $rekap['scan_masuk'] ? $rekap['scan_masuk']->format('H:i:s') : '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $rekap['scan_keluar'] ? $rekap['scan_keluar']->format('H:i:s') : '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>