<aside x-data="{ collapsed: false, mobileOpen: false }" 
       x-init="$watch('collapsed', value => $dispatch('sidebar-collapsed', value))"
       :class="collapsed ? 'w-20' : 'w-64'" 
       class="fixed inset-y-0 left-0 z-50 bg-white border-r border-gray-200 transition-all duration-300 ease-in-out lg:translate-x-0"
       :class="mobileOpen ? 'translate-x-0' : '-translate-x-full'"
       @open-sidebar.window="mobileOpen = true">

    <!-- Overlay for mobile -->
    <div x-show="mobileOpen" 
         @click="mobileOpen = false" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-600 bg-opacity-75 lg:hidden z-40"></div>

    <div class="relative flex flex-col h-full">
        <!-- Logo -->
        <div class="flex items-center justify-between h-16 px-4 border-b border-gray-200">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
                <x-application-logo class="h-8 w-auto fill-current text-gray-800" />
                <span x-show="!collapsed" class="text-xl font-semibold text-gray-800 transition-opacity duration-300 whitespace-nowrap">
                    {{ config('app.name', 'Terang') }}
                </span>
            </a>
            <!-- Close button for mobile -->
            <button @click="mobileOpen = false" class="lg:hidden p-1 rounded-md text-gray-400 hover:text-gray-500">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" 
               class="flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span x-show="!collapsed" class="ml-3 transition-opacity duration-300 whitespace-nowrap">Dashboard</span>
            </a>

            <!-- Dokumen Perencanaan -->
            <a href="{{ route('dokumen-perencanaans.index') }}" 
               class="flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('dokumen-perencanaans.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span x-show="!collapsed" class="ml-3 transition-opacity duration-300 whitespace-nowrap">Dokumen Perencanaan</span>
            </a>

            <!-- Usulan -->
            <a href="{{ route('usulans.index') }}" 
               class="flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('usulans.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span x-show="!collapsed" class="ml-3 transition-opacity duration-300 whitespace-nowrap">Usulan</span>
            </a>

            <!-- Perencanaan -->
            <a href="{{ route('perencanaans.index') }}" 
               class="flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('perencanaans.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                <span x-show="!collapsed" class="ml-3 transition-opacity duration-300 whitespace-nowrap">Perencanaan</span>
            </a>

            <!-- Daftar Kegiatan -->
            <a href="{{ route('daftar-kegiatans.index') }}" 
               class="flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('daftar-kegiatans.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span x-show="!collapsed" class="ml-3 transition-opacity duration-300 whitespace-nowrap">Daftar Kegiatan</span>
            </a>

            <!-- Usulan Pegawai -->
            <a href="{{ route('usulan-pegawais.index') }}" 
               class="flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('usulan-pegawais.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span x-show="!collapsed" class="ml-3 transition-opacity duration-300 whitespace-nowrap">Usulan Pegawai</span>
            </a>

            <!-- Persuratan -->
            <a href="{{ route('persuratans.index') }}" 
               class="flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('persuratans.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <span x-show="!collapsed" class="ml-3 transition-opacity duration-300 whitespace-nowrap">Persuratan</span>
            </a>

            <!-- Laporan Kegiatan -->
            <a href="{{ route('laporan-kegiatans.index') }}" 
               class="flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('laporan-kegiatans.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span x-show="!collapsed" class="ml-3 transition-opacity duration-300 whitespace-nowrap">Laporan Kegiatan</span>
            </a>

            <!-- Bukti Pengeluaran -->
            <a href="{{ route('bukti-pengeluarans.index') }}" 
               class="flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('bukti-pengeluarans.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" />
                </svg>
                <span x-show="!collapsed" class="ml-3 transition-opacity duration-300 whitespace-nowrap">Bukti Pengeluaran</span>
            </a>

            <!-- Usulan Pembayaran -->
            <a href="{{ route('usulan-pembayarans.index') }}" 
               class="flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('usulan-pembayarans.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span x-show="!collapsed" class="ml-3 transition-opacity duration-300 whitespace-nowrap">Usulan Pembayaran</span>
            </a>

            <!-- Keuangan -->
            <a href="{{ route('keuangan.pengajuan-pencairans.index') }}" 
               class="flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('keuangan.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span x-show="!collapsed" class="ml-3 transition-opacity duration-300 whitespace-nowrap">Keuangan</span>
            </a>

            <!-- Kepegawaian -->
            <a href="{{ route('kepegawaians.index') }}" 
               class="flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('kepegawaians.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span x-show="!collapsed" class="ml-3 transition-opacity duration-300 whitespace-nowrap">Kepegawaian</span>
            </a>

            <!-- Instansi -->
            <a href="{{ route('instansis.index') }}" 
               class="flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('instansis.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <span x-show="!collapsed" class="ml-3 transition-opacity duration-300 whitespace-nowrap">Instansi</span>
            </a>
        </nav>

        <!-- User Profile -->
        <div class="border-t border-gray-200 p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center">
                        <span class="text-white text-sm font-medium">{{ substr(Auth::user()->name, 0, 1) }}</span>
                    </div>
                </div>
                <div x-show="!collapsed" class="ml-3 flex-1 transition-opacity duration-300">
                    <p class="text-sm font-medium text-gray-700 whitespace-nowrap">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-500 whitespace-nowrap">{{ Auth::user()->email }}</p>
                </div>
            </div>

            <!-- Profile & Logout -->
            <div x-show="!collapsed" class="mt-3 space-y-1 transition-opacity duration-300">
                <a href="{{ route('profile.edit') }}" 
                   class="flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-600 hover:bg-gray-50 hover:text-gray-900">
                    <svg class="h-4 w-4 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Profile
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" 
                            class="w-full flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-600 hover:bg-gray-50 hover:text-gray-900">
                        <svg class="h-4 w-4 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Log Out
                    </button>
                </form>
            </div>
        </div>

        <!-- Collapse Toggle (Desktop Only) -->
        <button @click="collapsed = !collapsed" 
                class="hidden lg:flex absolute -right-3 top-20 items-center justify-center w-6 h-6 rounded-full bg-white border border-gray-200 shadow-sm hover:bg-gray-50">
            <svg class="h-4 w-4 text-gray-500 transition-transform duration-300" 
                 :class="collapsed ? 'rotate-180' : ''" 
                 fill="none" 
                 viewBox="0 0 24 24" 
                 stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </button>
    </div>
</aside>