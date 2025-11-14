<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Rekap Nilai Anda
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mb-4">
                <a href="{{ route('mahasiswa.nilai.export.pdf') }}" target="_blank" 
                   class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700">
                    Download KHS (PDF)
                </a>
        </div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if ($nilaiList->isEmpty())
                        <div class="p-4 bg-yellow-100 rounded-lg border border-yellow-400">
                            <p class="font-semibold text-yellow-800">
                                Belum ada data nilai tersimpan di sistem.
                            </p>
                            <p class="text-sm text-yellow-700">Silakan hubungi Dosen Anda untuk proses penilaian.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mata Kuliah</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai Tugas (Kumulatif)</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai UTS</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai UAS</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai Akhir</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grade</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($nilaiList as $nilai)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $nilai->matkul->kode_matkul }} - {{ $nilai->matkul->nama_matkul }}
                                            </td>
                                            
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $nilai->nilai_tugas ?? '—' }} 
                                            </td>
                                            
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $nilai->nilai_uts ?? '—' }}
                                            </td>
                                            
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $nilai->nilai_uas ?? '—' }}
                                            </td>
                                            
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-indigo-600">
                                                {{  $nilaiAkhir = ($nilai->nilai_tugas * 0.2) + ($nilai->nilai_uts * 0.4) + ($nilai->nilai_uas * 0.4);
 }}
                                            </td>
                                            
                                            @php
                                                if ($nilaiAkhir >= 80) $grade = 'A';
                                                elseif ($nilaiAkhir >= 70) $grade = 'B';
                                                elseif ($nilaiAkhir >= 60) $grade = 'C';
                                                elseif ($nilaiAkhir >= 50) $grade = 'D';
                                                else $grade = 'E';
                                            @endphp

                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold {{ $grade === 'A' ? 'text-green-600' : 'text-gray-600' }}">
                                                {{ $grade }}
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>