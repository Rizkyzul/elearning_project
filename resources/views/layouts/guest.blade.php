<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'E-Learning - ZUNA DEV') }}</title>
        <link rel="icon" type="image/png" href="{{ asset('ikmi.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body class="font-sans text-gray-900 antialiased h-screen overflow-hidden">

        <div class="relative min-h-screen flex flex-col items-center justify-center bg-cover bg-center"
             style="background-image: url('{{ asset('ikmicampus.jpg') }}');">
            
            <div class="absolute inset-0 bg-black/90"></div>

            {{-- Card Login --}}
            <div class="relative z-10 w-full sm:max-w-md mt-6 px-6 py-6 
                bg-white/65 backdrop-blur-xl 
                border border-white/30 
                shadow-2xl rounded-2xl">
                {{ $slot }}
            </div>

        </div>

    </body>
</html>
