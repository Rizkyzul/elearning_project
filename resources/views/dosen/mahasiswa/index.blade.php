<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Mahasiswa') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-4 p-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                    {{ session('error') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="mb-4 p-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                    <p class="font-semibold">Harap perbaiki error di bawah ini:</p>
                    <ul class="list-disc list-inside mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-4">Tambah Mahasiswa Manual</h3>
                    
                    <form action="{{ route('dosen.mahasiswa.store.manual') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="nama" :value="__('Nama Lengkap')" />
                                <x-text-input id="nama" class="block mt-1 w-full" type="text" name="nama" :value="old('nama')" required />
                            </div>
                            <div>
                                <x-input-label for="nim" :value="__('NIM (Nomor Induk Mahasiswa)')" />
                                <x-text-input id="nim" class="block mt-1 w-full" type="text" name="nim" :value="old('nim')" required />
                            </div>
                            <div>
                                <x-input-label for="email" :value="__('Email (untuk Login)')" />
                                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
                            </div>
                            <div>
                                <x-input-label for="prodi" :value="__('Program Studi')" />
                                <x-text-input id="prodi" class="block mt-1 w-full" type="text" name="prodi" :value="old('prodi', 'Teknik Informatika')" required />
                            </div>
                            <div>
                                <x-input-label for="angkatan" :value="__('Tahun Angkatan')" />
                                <x-text-input id="angkatan" class="block mt-1 w-full" type="number" name="angkatan" :value="old('angkatan', date('Y'))" required />
                            </div>
                            <div>
                                <x-input-label for="nama_kelas" :value="__('Nama Kelas (Otomatis dibuat jika baru)')" />
                                <x-text-input id="nama_kelas" class="block mt-1 w-full" type="text" name="nama_kelas" :value="old('nama_kelas')" placeholder="Contoh: TI-4A" required />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Simpan Mahasiswa Baru') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-4">Import Data Mahasiswa (Excel)</h3>
                    
                    <form action="{{ route('dosen.mahasiswa.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div>
                            <x-input-label for="file_mahasiswa" :value="__('File Excel (.xlsx, .xls)')" />
                            <x-text-input id="file_mahasiswa" class="block mt-1 w-full" type="file" name="file_mahasiswa" required />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Import') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-4">Petunjuk Format File Excel</h3>
                    <p class="mb-2">Pastikan file Excel Anda memiliki <strong>Header</strong> (baris pertama) dengan nama kolom <strong>persis</strong> sebagai berikut (huruf kecil semua):</p>
                    <ul class="list-disc list-inside text-sm">
                        <li><strong>nama</strong> (Contoh: Udin Saputra)</li>
                        <li><strong>email</strong> (Contoh: udin.saputra@example.com)</li>
                        <li><strong>nim</strong> (Contoh: 20221001)</li>
                        <li><strong>angkatan</strong> (Contoh: 2022)</li>
                        <li><strong>prodi</strong> (Contoh: Teknik Informatika)</li>
                        <li><strong>kelas</strong> (Contoh: TI-4A)</li>
                    </ul>
                </div>
            </div>

            <div class="mt-8">
                @livewire('dosen.mahasiswa-table')
            </div>

        </div>
    </div>
</x-app-layout>