<div>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <h3 class="text-lg font-medium mb-4">Daftar Mahasiswa Terdaftar</h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                
                <div>
                    <x-input-label for="search" :value="__('Cari Nama atau NIM')" />
                    <x-text-input id="search" class="block mt-1 w-full" type="text" 
                                  wire:model.live.debounce.300ms="search" 
                                  placeholder="Ketik untuk mencari..."/>
                </div>
                
                <div>
                    <x-input-label for="filterKelas" :value="__('Filter Kelas')" />
                    <select id="filterKelas" 
                            wire:model.live="filterKelas" 
                            class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="">Semua Kelas</option>
                        @foreach($semuaKelas as $kelas)
                            <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <x-input-label for="filterAngkatan" :value="__('Filter Angkatan')" />
                    <select id="filterAngkatan" 
                            wire:model.live="filterAngkatan" 
                            class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="">Semua Angkatan</option>
                        @foreach($semuaAngkatan as $angkatan)
                            <option value="{{ $angkatan->angkatan }}">{{ $angkatan->angkatan }}</option>
                        @endforeach
                    </select>
                </div>

            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">NIM</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Prodi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kelas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Angkatan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($mahasiswaList as $mahasiswa)
                            <tr wire:key="{{ $mahasiswa->id }}">
                                <td class="px-6 py-4 whitespace-nowrap">{{ $mahasiswa->nim }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $mahasiswa->user->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $mahasiswa->user->email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $mahasiswa->prodi }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $mahasiswa->kelas->nama_kelas ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $mahasiswa->angkatan }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    
                                    <form action="{{ route('dosen.mahasiswa.reset', $mahasiswa) }}" method="POST" 
                                          class="form-reset-password"> @csrf
                                        
                                        <button type="submit" 
                                                data-nama="{{ $mahasiswa->user->name }}"
                                                class="btn-reset-password text-yellow-600 hover:text-yellow-900">
                                            Reset Password
                                        </button>
                                    </form>
                                    </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                    Tidak ada data mahasiswa yang cocok.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4 ">
                {{ $mahasiswaList->links() }}
            </div>

        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        
        <script>
            // Pastikan script berjalan setelah halaman (termasuk Livewire) siap
            document.addEventListener('livewire:navigated', function () {
                
                // Cari SEMUA tombol reset
                const resetButtons = document.querySelectorAll('.btn-reset-password');
                
                resetButtons.forEach(button => {
                    button.addEventListener('click', function (e) {
                        // Hentikan aksi default (submit)
                        e.preventDefault(); 
                        
                        // Ambil form terdekat
                        const form = e.target.closest('form');
                        // Ambil nama dari data-nama
                        const nama = e.target.dataset.nama;

                        Swal.fire({
                            title: 'Reset Password?',
                            text: `Anda yakin ingin me-reset password untuk ${nama}?`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#eab308', // Yellow
                            cancelButtonColor: '#d33', // Merah
                            confirmButtonText: 'Ya, Reset!',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Jika dikonfirmasi, submit form-nya
                                form.submit();
                            }
                        });
                    });
                });

                // 3. Script untuk Menampilkan Notifikasi 'success'
                // (Ini akan mengambil notifikasi dari controller 'storeManual' atau 'store' juga)
                @if (session('success'))
                    Swal.fire({
                        title: 'Berhasil!',
                        text: '{{ session('success') }}',
                        icon: 'success',
                        confirmButtonColor: '#2563eb' // Biru
                    });
                @endif
                
            });
        </script>
    @endpush
    </div>