<div class="flex flex-col h-full p-5 bg-gradient-to-b from-blue-50 to-white shadow-lg rounded-2xl border border-blue-100">

    <h2 class="text-2xl font-extrabold text-blue-700 mb-6 flex items-center gap-3">
        <svg class="w-9 h-9 text-blue-600 drop-shadow-sm" fill="none" stroke-width="1.5" stroke="currentColor"
             viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18c-2.305 0-4.408.867-6 2.292m0-14.25v14.25">
            </path>
        </svg>
        <span class="tracking-tight">E-Learning
        </span>
    </h2>

        <nav class="flex-1 mt-4">
            <h3 class="mb-3 text-xs font-semibold tracking-wider text-gray-400 uppercase">Menu Utama</h3>

            @if(Auth::user()->role == 'superadmin')
            <a href="{{ route('superadmin.dosen.create') }}"
            class="flex items-center px-3 py-2.5 mb-2 text-gray-700 rounded-lg ... {{ request()->routeIs('superadmin.dosen.*') ? 'bg-blue-200 font-semibold text-blue-800' : '' }}">
                <span class="mr-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </span>
                <span>Akun Dosen (Admin)</span>
            </a>

            <hr class="my-4">
        @endif
        
        @if(Auth::user()->role == 'dosen')
            
            <a href="{{ route('dosen.dashboard') }}"
               class="flex items-center px-3 py-2.5 mb-2 text-gray-700 rounded-lg transition duration-200 ease-in-out hover:bg-blue-100 hover:text-blue-700 {{ request()->routeIs('dosen.dashboard') ? 'bg-blue-200 font-semibold text-blue-800' : '' }}">
                <span class="mr-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                </span>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('dosen.mahasiswa.index') }}"
               class="flex items-center px-3 py-2.5 mb-2 text-gray-700 rounded-lg transition duration-200 ease-in-out hover:bg-blue-100 hover:text-blue-700 {{ request()->routeIs('dosen.mahasiswa.*') ? 'bg-blue-200 font-semibold text-blue-800' : '' }}">
                <span class="mr-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </span>
                <span>Manajemen Mahasiswa</span>
            </a>
            <a href="{{ route('dosen.matkul.index') }}"
                class="flex items-center px-3 py-2.5 mb-2 text-gray-700 rounded-lg transition duration-200 ease-in-out hover:bg-blue-100 hover:text-blue-700 {{ request()->routeIs('dosen.matkul.*') ? 'bg-blue-200 font-semibold text-blue-800' : '' }}">
                    <span class="mr-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18c-2.305 0-4.408.867-6 2.292m0-14.25v14.25"></path></svg>
                    </span>
                    <span>Manajemen Mata Kuliah</span>
            </a>
         
        @elseif(Auth::user()->role == 'mahasiswa')
            
            <a href="{{ route('mahasiswa.dashboard') }}"
               class="flex items-center px-3 py-2.5 mb-2 text-gray-700 rounded-lg transition duration-200 ease-in-out hover:bg-blue-100 hover:text-blue-700 {{ request()->routeIs('mahasiswa.dashboard') ? 'bg-blue-200 font-semibold text-blue-800' : '' }}">
               <span class="mr-3">
                   <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
               </span>
               <span>Dashboard</span>
            </a>

            <a href="{{ route('mahasiswa.nilai.index') }}"
               class="flex items-center px-3 py-2.5 mb-2 text-gray-700 rounded-lg transition duration-200 ease-in-out hover:bg-blue-100 hover:text-blue-700 {{ request()->routeIs('mahasiswa.nilai.*') ? 'bg-blue-200 font-semibold text-blue-800' : '' }}">
               <span class="mr-3">
                   <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
               </span>
               <span>Lihat Nilai</span>
            </a>

            <a href="{{ route('mahasiswa.absensi.scan') }}"
               class="flex items-center px-3 py-2.5 mb-2 text-gray-700 rounded-lg transition duration-200 ease-in-out hover:bg-blue-100 hover:text-blue-700 {{ request()->routeIs('mahasiswa.absensi.*') ? 'bg-blue-200 font-semibold text-blue-800' : '' }}">
               <span class="mr-3">
                   <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m-2 0h6V12h-2v-1m-4 0H8v1H6v4h2v-4m0 0v-1h2v1m0 0h2m-2 0h-2m2 0v-1m0 4v-4m0 0h-2m2 0h2M4 8h2m14 0h2M4 12h2m14 0h2M4 16h2m14 0h2M4 20h16v-1a1 1 0 00-1-1H5a1 1 0 00-1 1v1zm0 0H3v-1a1 1 0 011-1h16a1 1 0 011 1v1h-1m-1-1H5v-1h14v1z"></path></svg>
               </span>
               <span>Absensi</span>
            </a>
        @endif
    </nav>

    <div class="mt-auto pt-5 border-t border-blue-100">
        <div class="px-2">
            <div class="font-semibold text-base text-blue-800">{{ Auth::user()->name }}</div>
            <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
        </div>

        <div class="mt-3 space-y-1">
            <x-responsive-nav-link :href="route('profile.edit')" class="hover:text-blue-600">
                {{ __('Profile') }}
            </x-responsive-nav-link>

           <form id="logout-form" method="POST" action="{{ route('logout') }}">
    @csrf
    <x-responsive-nav-link href="#" 
        onclick="confirmLogout(event)" 
        class="hover:text-red-600">
        {{ __('Log Out') }}
    </x-responsive-nav-link>
</form>

<script>
    function confirmLogout(event) {
        event.preventDefault(); // cegah langsung submit

        Swal.fire({
            title: 'Yakin ingin logout?',
            text: "Sesi kamu akan diakhiri.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#2563eb', // biru Tailwind (blue-600)
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, logout',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logout-form').submit();
            }
        });
    }
</script>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
