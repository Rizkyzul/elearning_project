<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900">
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            NIM
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Nama Mahasiswa
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tugas
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            UTS
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            UAS
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($mahasiswaList as $mahasiswa)
                        <tr wire:key="{{ $mahasiswa->id }}">
                            <td class="px-6 py-4 whitespace-nowrap">{{ $mahasiswa->nim }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $mahasiswa->user->name }}</td>
                            
                            <td class="px-6 py-4">
                                <x-text-input type="number" min="0" max="100" 
                                      wire:model.live.debounce.500ms="nilaiData.{{ $mahasiswa->id }}.tugas" 
                                      class="w-24" />
                                
                                @error('nilaiData.' . $mahasiswa->id . '.tugas') 
                                    <span class="text-red-500 text-xs">{{ $message }}</span> 
                                @enderror
                            </td>
                            
                            <td class="px-6 py-4">
                                <x-text-input type="number" min="0" max="100" 
                                      wire:model.live.debounce.500ms="nilaiData.{{ $mahasiswa->id }}.uts" 
                                      class="w-24" />
                                @error('nilaiData.' . $mahasiswa->id . '.uts') 
                                    <span class="text-red-500 text-xs">{{ $message }}</span> 
                                @enderror
                            </td>
                            
                            <td class="px-6 py-4">
                                <x-text-input type="number" min="0" max="100" 
                                      wire:model.live.debounce.500ms="nilaiData.{{ $mahasiswa->id }}.uas" 
                                      class="w-24" />
                                @error('nilaiData.' . $mahasiswa->id . '.uas') 
                                    <span class="text-red-500 text-xs">{{ $message }}</span> 
                                @enderror
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</div>