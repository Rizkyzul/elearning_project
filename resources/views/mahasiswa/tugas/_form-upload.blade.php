<form action="{{ route('mahasiswa.tugas.submit', [$matkul, $tugas]) }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <div>
        <x-input-label for="file_jawaban" :value="__('Upload File (PDF, ZIP, DOC - Maks 10MB)')" />
        <x-text-input id="file_jawaban" class="block mt-1 w-full" type="file" name="file_jawaban" required />
        <x-input-error :messages="$errors->get('file_jawaban')" class="mt-2" />
    </div>

    <div class="flex items-center justify-end mt-4">
        <x-primary-button>
            {{ $jawaban ? 'Submit Ulang' : 'Submit Jawaban' }}
        </x-primary-button>
    </div>
</form>