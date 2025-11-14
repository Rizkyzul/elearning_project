<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale-1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        
        <div x-data="{ sidebarOpen: false }" class="flex h-screen bg-blue-100">

            <aside 
                class="fixed inset-y-0 left-0 z-30 flex-shrink-0 w-64 h-full bg-white shadow-md transform md:translate-x-0 md:static md:inset-0 transition-transform duration-200 ease-in-out"
                :class="{ 'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen }"
                aria-label="Sidebar">
                
                @include('layouts.sidebar')
            </aside>

            <div x-show="sidebarOpen" @click="sidebarOpen = false" 
                 class="fixed inset-0 z-20 bg-black opacity-50 md:hidden" 
                 style="display: none;">
            </div>
            
            <div class="flex flex-col flex-1 h-full overflow-hidden">
                
                <main class="flex-1 p-6 overflow-auto">
                    
                    @if (isset($header))
                        <header class="mb-6 flex items-center justify-between">
                            <div class="max-w-7xl">
                                {{ $header }}
                            </div>
                            
                            <button @click="sidebarOpen = !sidebarOpen" 
                                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 md:hidden">
                                <span class="sr-only">Buka sidebar</span>
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </button>
                        </header>
                    @endif

                    {{ $slot }}

                </main>
            </div>
        </div>

        @stack('scripts')
        @livewireScripts
    </body>
</html>