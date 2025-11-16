<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Mahasiswa') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- ================= FORM MANUAL ================= --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-4">Tambah Mahasiswa Manual</h3>

                    <form id="formManualCreate" action="{{ route('dosen.mahasiswa.store.manual') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <div>
                                <x-input-label for="nama" :value="'Nama Lengkap'" />
                                <x-text-input id="nama"
                                              class="block mt-1 w-full"
                                              type="text" name="nama"
                                              value="{{ old('nama') }}" required />
                            </div>

                            <div>
                                <x-input-label for="nim" :value="'NIM'" />
                                <x-text-input id="nim"
                                              class="block mt-1 w-full"
                                              type="text" name="nim"
                                              value="{{ old('nim') }}" required />
                            </div>

                            <div>
                                <x-input-label for="email" :value="'Email'" />
                                <x-text-input id="email"
                                              class="block mt-1 w-full"
                                              type="email" name="email"
                                              value="{{ old('email') }}" required />
                            </div>

                            <div>
                                <x-input-label for="prodi" :value="'Program Studi'" />
                                <x-text-input id="prodi"
                                              class="block mt-1 w-full"
                                              type="text" name="prodi"
                                              value="{{ old('prodi', 'Teknik Informatika') }}" required />
                            </div>

                            <div>
                                <x-input-label for="angkatan" :value="'Tahun Angkatan'" />
                                <x-text-input id="angkatan"
                                              class="block mt-1 w-full"
                                              type="number" name="angkatan"
                                              value="{{ old('angkatan', date('Y')) }}" required />
                            </div>

                            <div>
                                <x-input-label for="nama_kelas" :value="'Nama Kelas'" />
                                <x-text-input id="nama_kelas"
                                              class="block mt-1 w-full uppercase"
                                              type="text" name="nama_kelas"
                                              value="{{ old('nama_kelas') }}"
                                              placeholder="Contoh: TI-4A" required />
                            </div>

                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                Simpan Mahasiswa Baru
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- SWEETALERT ERROR MESSAGE --}}
            @if ($errors->any())
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Validasi Gagal!',
                        html: '{!! implode("<br>", $errors->all()) !!}',
                    });
                </script>
            @endif

      @if (session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        html: `{!! session('success') !!}`,
        confirmButtonColor: '#3085d6',
    });
</script>
@endif

@if (session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        html: `{!! session('error') !!}`,
        confirmButtonColor: '#d33',
    });
</script>
@endif


            {{-- ==================== SWEETALERT KONFIRMASI ==================== --}}
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

            <script>
                document.querySelector('#formManualCreate').addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Simpan data ini?',
                        text: "Pastikan semua data sudah benar.",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Simpan',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            e.target.submit();
                        }
                    })
                });
            </script>

        </div>
    </div>
</x-app-layout>
