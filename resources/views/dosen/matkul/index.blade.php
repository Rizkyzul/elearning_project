<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Mata Kuliah') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class... (kode notifikasi sukses Anda) ...</div>
            @endif
            @if (session('error'))
                <div class... (kode notifikasi error Anda) ...</div>
            @endif
            @if ($errors->any())
                <div class... (kode notifikasi validasi Anda) ...</div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-4">Tambah Mata Kuliah Baru</h3>
                    
                    <form action="{{ route('dosen.matkul.store') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="md:col-span-2">
                                <x-input-label for="nama_matkul" :value="__('Nama Mata Kuliah Baru')" />
                                <x-text-input id="nama_matkul" class="block mt-1 w-full" type="text" name="nama_matkul" :value="old('nama_matkul')" placeholder="Contoh: Kecerdasan Buatan" required />
                            </div>
                            <div>
                                <x-input-label for="kode_matkul" :value="__('Kode Mata Kuliah')" />
                                <x-text-input id="kode_matkul" class="block mt-1 w-full" type="text" name="kode_matkul" :value="old('kode_matkul')" placeholder="Contoh: IF-006" required />
                            </div>
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Tambahkan Mata Kuliah') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-4">Daftar Mata Kuliah di Sistem</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Mata Kuliah</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($semuaMatkul as $matkul)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $matkul->kode_matkul }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $matkul->nama_matkul }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                           <form action="{{ route('dosen.matkul.destroy', $matkul) }}" method="POST"
                                                onsubmit="confirmDelete(event, this)">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">
                                                    Hapus
                                                </button>
                                            </form>

                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-center text-gray-500">
                                            Belum ada mata kuliah di sistem.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
    {{-- ================= SWEETALERT NOTIFIKASI ================= --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if (session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        html: `{!! session('success') !!}`,
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'OK'
    });
</script>
@endif

@if (session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Terjadi Kesalahan',
        html: `{!! session('error') !!}`,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Tutup'
    });
</script>
@endif

@if ($errors->any())
<script>
    let list = `
        <ul class="text-left">
            @foreach ($errors->all() as $err)
                <li>• {!! $err !!}</li>
            @endforeach
        </ul>
    `;

    Swal.fire({
        icon: 'warning',
        title: 'Validasi Gagal',
        html: list,
        confirmButtonColor: '#f59e0b',
        confirmButtonText: 'Perbaiki'
    });
</script>
@endif



{{-- ================= SWEETALERT KONFIRMASI HAPUS ================= --}}
<script>
function confirmDelete(event, form) {
    event.preventDefault(); // stop submit dulu

    Swal.fire({
        title: 'Hapus Mata Kuliah?',
        html: `
            <b>Peringatan penting!</b><br>
            Menghapus mata kuliah ini akan menghapus:
            <ul style="text-align:left;margin-top:10px">
                <li>• Semua materi</li>
                <li>• Semua tugas</li>
                <li>• Semua nilai</li>
                <li>• Semua absensi</li>
            </ul>
            <br>Yakin ingin melanjutkan?
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Hapus',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
}
</script>

</x-app-layout>