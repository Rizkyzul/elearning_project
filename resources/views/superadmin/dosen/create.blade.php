<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Manajemen Akun Dosen
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            @if ($errors->any())
                <div class="mb-4 p-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-4">Buat Akun Dosen Baru</h3>
                    
                    <form id="createDosenForm" action="{{ route('superadmin.dosen.store') }}" method="POST">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="name" :value="__('Nama Lengkap (beserta gelar)')" />
                                <x-text-input id="name" class="block w-full mt-1" type="text" name="name" :value="old('name')" required />
                            </div>
                            <div>
                                <x-input-label for="email" :value="__('Email (untuk Login)')" />
                                <x-text-input id="email" class="block w-full mt-1" type="email" name="email" :value="old('email')" required />
                            </div>
                            <div>
                                <x-input-label for="nidn" :value="__('NIDN / NIP')" />
                                <x-text-input id="nidn" class="block w-full mt-1" type="text" name="nidn" :value="old('nidn')" required />
                            </div>
                            <div>
                                <x-input-label for="prodi" :value="__('Program Studi')" />
                                <x-text-input id="prodi" class="block w-full mt-1" type="text" name="prodi" :value="old('prodi', 'Teknik Informatika')" required />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button id="btnSubmit">
                                {{ __('Buat Akun Dosen') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-4">Daftar Akun Dosen Terdaftar</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left ...">Nama</th>
                                    <th class="px-6 py-3 text-left ...">Email</th>
                                    <th class="px-6 py-3 text-left ...">NIDN</th>
                                    <th class="px-6 py-3 text-left ...">Prodi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($dosenList as $dosen)
                                    <tr>
                                        <td class="px-6 py-4 ...">{{ $dosen->user->name }}</td>
                                        <td class="px-6 py-4 ...">{{ $dosen->user->email }}</td>
                                        <td class="px-6 py-4 ...">{{ $dosen->nidn }}</td>
                                        <td class="px-6 py-4 ...">{{ $dosen->prodi }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                            Belum ada Dosen terdaftar.
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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        // Pastikan script berjalan setelah halaman siap
        document.addEventListener('DOMContentLoaded', function () {
            
            // Tangkap tombol submit
            const btnSubmit = document.getElementById('btnSubmit');
            
            if(btnSubmit) { // Cek jika tombol ada
                btnSubmit.addEventListener('click', function (e) {
                    // Hentikan aksi default (submit)
                    e.preventDefault(); 
            
                    Swal.fire({
                        title: 'Buat Akun Dosen?',
                        text: "Pastikan data yang dimasukkan sudah benar.",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#2563eb', // Biru
                        cancelButtonColor: '#d33', // Merah
                        confirmButtonText: 'Ya, Simpan!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Jika dikonfirmasi, submit form-nya
                            document.getElementById('createDosenForm').submit();
                        }
                    });
                });
            }

            // 3. Script untuk Menampilkan Notifikasi 'success'
            @if (session('success'))
                Swal.fire({
                    title: 'Berhasil!',
                    html: `{!! session('success') !!}`,
                    icon: 'success',
                    confirmButtonColor: '#2563eb'
                });
            @endif

        });
    </script>
    </x-app-layout>