<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Submission Tugas: {{ $tugas->judul }}
        </h2>
    </x-slot>

    <a href="{{ route('dosen.tugas.index', $matkul) }}" class="text-sm text-gray-600 hover:text-gray-900 mb-4 inline-block">
        &larr; Kembali ke Daftar Tugas
    </a>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <h3 class="text-lg font-medium mb-4">Daftar Submission (Total: {{ $jawabanList->count() }})</h3>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">NIM</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Mahasiswa</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Waktu Submit</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($jawabanList as $jawaban)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $jawaban->mahasiswa->nim }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $jawaban->mahasiswa->user->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $jawaban->submitted_at->format('d M Y, H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('dosen.tugas.grade', [$matkul, $tugas, $jawaban]) }}" 
                                       class="text-green-600 hover:text-green-800 font-medium">
                                        Lihat & Nilai
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                    Belum ada mahasiswa yang submit tugas ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>