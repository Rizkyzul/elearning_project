<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tambah Tugas Baru ({{ $matkul->nama_matkul }})
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <x-matkul-tabs :matkul="$matkul" />

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form action="{{ route('dosen.tugas.store', $matkul) }}" method="POST">
                        @csrf

                        <div>
                            <x-input-label for="judul" :value="__('Judul Tugas')" />
                            <x-text-input id="judul" class="block mt-1 w-full" type="text" name="judul" :value="old('judul')" required autofocus />
                            <x-input-error :messages="$errors->get('judul')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="deskripsi" :value="__('Deskripsi Tugas')" />
                            <textarea id="deskripsi" name="deskripsi" rows="5" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>{{ old('deskripsi') }}</textarea>
                            <x-input-error :messages="$errors->get('deskripsi')" class="mt-2" />
                        </div>
                        <div x-data="{ isKelasOpen: false, filterKelas: '' }" class="mt-4">
                            
                            <x-input-label :value="__('Tampilkan untuk Kelas (Pilih satu atau lebih)')" />
                            <button type="button" @click="isKelasOpen = !isKelasOpen" 
                                    class="w-full text-left mt-1 px-3 py-2 bg-gray-100 border border-gray-300 rounded-md shadow-sm text-sm text-gray-700 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                
                                <span x-text="isKelasOpen ? '&#9660; Sembunyikan Pilihan Kelas' : '&#9658; Klik untuk Memilih Kelas'"></span>
                            </button>
                            
                            <div x-show="isKelasOpen" 
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0 transform scale-90"
                                 x-transition:enter-end="opacity-100 transform scale-100"
                                 x-transition:leave="transition ease-in duration-300"
                                 x-transition:leave-start="opacity-100 transform scale-100"
                                 x-transition:leave-end="opacity-0 transform scale-90"
                                 class="mt-2 space-y-2 border p-4 rounded-md bg-white shadow-inner origin-top">

                                <input type="text" x-model.debounce.300ms="filterKelas" 
                                       placeholder="Ketik untuk mencari kelas (misal: TI-23)" 
                                       class="block w-full mb-3 border-gray-300 rounded-md shadow-sm text-sm">
                                <div class="border-b pb-2 mb-2">
                                    <label for="all_classes" class="inline-flex items-center font-semibold text-gray-800">
                                        <input id="all_classes" name="all_classes" type="checkbox" 
                                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" 
                                               value="1" onclick="toggleClassCheckboxes(this)">
                                        <span class="ms-2 text-sm">SEMUA KELAS DI MATKUL INI</span>
                                    </label>
                                </div>
                                
                                <div id="kelasCheckboxes">
                                    @foreach($daftarKelas as $kelas)
                                        <div x-show="filterKelas === '' || '{{ strtolower($kelas->nama_kelas) }}'.includes(filterKelas.toLowerCase())"
                                             class="flex items-center">
                                            
                                            <input id="kelas_{{ $kelas->id }}" name="kelas_ids[]" type="checkbox" 
                                                   value="{{ $kelas->id }}" 
                                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                            <label for="kelas_{{ $kelas->id }}" class="ms-2 text-sm text-gray-900">{{ $kelas->nama_kelas }}</label>
                                        </div>
                                    @endforeach
                                </div>
                                
                                <x-input-error :messages="$errors->get('kelas_ids')" class="mt-2" />
                                <p class="text-xs text-gray-500 mt-1">Jika tidak ada yang dicentang, item tidak akan terlihat oleh mahasiswa manapun.</p>
                            </div>
                        </div>
                       
                        <script>
                            function toggleClassCheckboxes(checkbox) {
                                const container = document.getElementById('kelasCheckboxes');
                                const checkboxes = container.querySelectorAll('input[type="checkbox"]');
                                
                                checkboxes.forEach(cb => {
                                    cb.checked = checkbox.checked;
                                    cb.disabled = checkbox.checked;
                                });
                            }
                        </script>
                            <x-input-error :messages="$errors->get('kelas_id')" class="mt-2" />
                            <p class="text-xs text-gray-500 mt-1">Jika dikosongkan, tugas akan tampil untuk semua kelas.</p>
                        </div>
                     
                        
                        <div class="ml-4 mt-4">
                            <x-input-label for="deadline" :value="__('Deadline')" />
                            <x-text-input id="deadline" class="block mt-1" type="datetime-local" name="deadline" :value="old('deadline')" required />
                            <x-input-error :messages="$errors->get('deadline')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6 mb-6 mr-4">
                            <a href="{{ route('dosen.tugas.index', $matkul) }}" class="text-gray-600 hover:text-gray-900 mr-4">
                                Batal
                            </a>
                            <x-primary-button>
                                {{ __('Buat Tugas') }}
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>