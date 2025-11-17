<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ganti Password Wajib') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(session('warning'))
                        <div class="mb-4 p-4 text-sm text-yellow-700 bg-yellow-100 rounded-lg" role="alert">
                            {{ session('warning') }}
                        </div>
                    @endif
                    <form method="POST" action="{{ route('password.change.update') }}">
                        @csrf
                        <div>
                            <x-input-label for="current_password" :value="__('Password Saat Ini')" />
                            <x-text-input id="current_password" class="block mt-1 w-full" type="password" name="current_password" required />
                            <x-input-error :messages="$errors->get('current_password')" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <x-input-label for="password" :value="__('Password Baru')" />
                            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password Baru')" />
                            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>
                        <h3 class="max-w-2xl mx-auto  text-sm text-red-600">
                            *Password baru tidak boleh sama dengan password lama.
                            <br>
                            Password harus mengandung huruf besar, huruf kecil, angka, dan simbol.
                        </h3>
                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Update Password') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
</x-app-layout>