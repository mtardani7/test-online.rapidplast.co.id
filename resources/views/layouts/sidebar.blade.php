<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/rapidplast.ico') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://unpkg.com/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        :root {
            --primary-red: #dc2626;
            --primary-red-dark: #b91c1c;
            --sidebar-width: 16rem;
            --sidebar-width-collapsed: 5rem;
        }
        
        * { font-family: 'Inter', sans-serif; }
        
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.2); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(255, 255, 255, 0.4); }
        
        .sidebar-transition { transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        
        .sidebar-item-group .active {
            background: linear-gradient(90deg, rgba(255,255,255,0.2) 0%, rgba(255,255,255,0.1) 100%);
            border-left: 4px solid #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            position: relative;
            font-weight: 600;
        }

        .sidebar-item-group .active::after {
            content: '';
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 70%;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 3px 0 0 3px;
        }

        .sidebar-item-group a:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateX(2px);
        }
        
        .sidebar-tooltip {
            position: absolute;
            left: 100%;
            top: 50%;
            transform: translateY(-50%);
            margin-left: 10px;
            padding: 6px 12px;
            background-color: #1f2937;
            color: white;
            font-size: 0.75rem;
            border-radius: 6px;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s;
            white-space: nowrap;
            z-index: 50;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .sidebar-collapsed .sidebar-item-group:hover .sidebar-tooltip {
            opacity: 1;
        }

        .sidebar-collapsed .hide-on-collapse {
            display: none;
            opacity: 0;
        }

        .sidebar-collapsed .center-on-collapse {
            justify-content: center;
            padding-left: 0;
            padding-right: 0;
        }
        .glass-header {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(8px);
            border-bottom: 1px solid #e5e7eb;
        }

        .breadcrumb-enter {
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-10px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 antialiased" x-data="{ sidebarOpen: false }">
    <div class="flex h-screen min-h-screen overflow-hidden">
        <div x-show="sidebarOpen" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"
             @click="sidebarOpen = false"></div>

        <aside id="sidebar" aria-label="Navigasi admin"
               class="fixed inset-y-0 left-0 flex h-full flex-col bg-gradient-to-b from-rose-800 via-rose-700 to-rose-950 text-white z-50
                      transform transition-all duration-300 ease-in-out shadow-2xl w-64 lg:relative"
               :class="{ '-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen, 'lg:translate-x-0': true }">
            <div class="flex items-center justify-between h-20 px-4 border-b border-white/10 shrink-0">
                <div class="flex items-center gap-3 overflow-hidden whitespace-nowrap hide-on-collapse transition-all duration-300">
                    <div class="w-11 h-11 bg-white rounded-xl flex items-center justify-center shadow-md shrink-0">
                        <img src="{{ asset('images/RAPIDPLAST.jpg') }}" class="rounded-lg w-9 h-9 object-contain" alt="Logo RapidPlast">
                    </div>
                    <div class="flex flex-col">
                        <h1 class="text-sm font-bold tracking-tight leading-tight">PT Rapid Plast</h1>
                        <span class="text-xs text-rose-100/80 font-medium">Panel Administrator</span>
                    </div>
                </div>
                <button id="sidebarToggle" class="hidden lg:flex items-center justify-center w-8 h-8 rounded-full bg-red-800/50 hover:bg-white/20 text-white transition-all shadow-sm">
                    <svg class="h-4 w-4 transition-transform duration-300" id="toggleIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 18-6-6 6-6" /></svg>
                </button>
                <button @click="sidebarOpen = false" class="lg:hidden text-white hover:text-red-200">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m6 18 12-12M6 6l12 12" /></svg>
                </button>
            </div>
            <nav class="flex-1 overflow-y-auto overflow-x-hidden py-5 px-3 space-y-1 custom-scrollbar">
                <div class="sidebar-item-group relative group">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center px-3 py-3 rounded-xl hover:bg-white/10 transition-all text-sm font-medium
                              {{ request()->routeIs('admin.dashboard') ? 'active bg-white/20 border-l-4 border-white shadow-lg' : '' }}">
                        <div class="w-6 h-6 flex items-center justify-center shrink-0">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3.75 13.5a8.25 8.25 0 1 1 16.5 0v4.125A1.875 1.875 0 0 1 18.375 19.5h-1.5A1.875 1.875 0 0 1 15 17.625v-1.5a1.875 1.875 0 0 1 1.875-1.875h3.24M3.885 14.25H7.125A1.875 1.875 0 0 1 9 16.125v1.5A1.875 1.875 0 0 1 7.125 19.5h-1.5a1.875 1.875 0 0 1-1.875-1.875V13.5m8.25-3.75 2.25-2.25" /></svg>
                        </div>
                        <span class="ml-3 hide-on-collapse whitespace-nowrap">Dashboard</span>
                    </a>
                    <div class="sidebar-tooltip">Dashboard</div>
                </div>
                
                <div class="mt-6 mb-2 px-3 hide-on-collapse">
                    <p class="text-[10px] uppercase tracking-wider text-red-200/70 font-bold">Tes & Hasil</p>
                </div>
                <div class="my-4 border-t border-white/10 hidden show-on-collapse"></div>
                
                <div class="sidebar-item-group relative group">
                    <a href="{{ route('admin.total-test.index') }}" 
                       class="flex items-center px-3 py-3 rounded-xl hover:bg-white/10 transition-all text-sm font-medium
                              {{ request()->routeIs('admin.total-test.*') ? 'active bg-white/20 border-l-4 border-white shadow-lg' : '' }}">
                        <div class="w-6 h-6 flex items-center justify-center shrink-0">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M11.25 3.75v8.25h8.25M19.5 12a8.25 8.25 0 1 1-8.25-8.25" /></svg>
                        </div>
                        <span class="ml-3 hide-on-collapse whitespace-nowrap">Hasil Test</span>
                    </a>
                    <div class="sidebar-tooltip">Hasil Test</div>
                </div>
                
                <div class="sidebar-item-group relative group">
                    <a href="{{ route('admin.attempts.index') }}" 
                       class="flex items-center px-3 py-3 rounded-xl hover:bg-white/10 transition-all text-sm font-medium
                              {{ request()->routeIs('admin.attempts.*') ? 'active bg-white/20 border-l-4 border-white shadow-lg' : '' }}">
                        <div class="w-6 h-6 flex items-center justify-center shrink-0">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3.75 12a8.25 8.25 0 1 0 2.42-5.83L3.75 8.25m0-4.5v4.5h4.5M12 7.5v4.875l3 1.875" /></svg>
                        </div>
                        <span class="ml-3 hide-on-collapse whitespace-nowrap">Reset Jawaban</span>
                    </a>
                    <div class="sidebar-tooltip">Reset Jawaban</div>
                </div>
                
                <div class="mt-6 mb-2 px-3 hide-on-collapse">
                    <p class="text-[10px] uppercase tracking-wider text-red-200/70 font-bold">Manajemen</p>
                </div>
                <div class="my-4 border-t border-white/10 hidden show-on-collapse"></div>

                @php
                    $totalUsers = \App\Models\User::whereDoesntHave('roles', function ($query) {
                        $query->where('name', 'admin');
                    })->count();
                @endphp
                
                <div class="sidebar-item-group relative group">
                    <a href="{{ route('dashboard.users') }}" 
                       class="flex items-center px-3 py-3 rounded-xl hover:bg-white/10 transition-all text-sm font-medium
                              {{ request()->routeIs('dashboard.users') ? 'active bg-white/20 border-l-4 border-white shadow-lg' : '' }}">
                        <div class="w-6 h-6 flex items-center justify-center shrink-0">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16.5 19.125h4.125v-1.5a3.375 3.375 0 0 0-5.063-2.925M16.5 19.125v-1.5c0-1.08-.35-2.08-.938-2.888M16.5 19.125h-9m0 0H3.375v-1.5a3.375 3.375 0 0 1 5.063-2.925M7.5 19.125v-1.5c0-1.08.35-2.08.938-2.888m0 0a5.25 5.25 0 0 1 7.124 0M15.75 7.125a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" /></svg>
                        </div>
                        <span class="ml-3 flex-1 hide-on-collapse whitespace-nowrap">Daftar Kandidat</span>
                        <span class="ml-auto bg-red-900/50 border border-red-400/30 text-white text-[10px] px-2 py-0.5 rounded-full hide-on-collapse">
                            {{ $totalUsers }}
                        </span>
                    </a>
                    <div class="sidebar-tooltip">Daftar Kandidat ({{ $totalUsers }})</div>
                </div>
                
                <div class="sidebar-item-group relative group">
                    <a href="{{ route('scoring.index') }}" 
                       class="flex items-center px-3 py-3 rounded-xl hover:bg-white/10 transition-all text-sm font-medium
                              {{ request()->routeIs('scoring.*') ? 'active bg-white/20 border-l-4 border-white shadow-lg' : '' }}">
                        <div class="w-6 h-6 flex items-center justify-center shrink-0">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.5 6.75h9m3 0h3m-15 10.5h3m3 0h12M13.5 4.5v4.5m-3 6v4.5" /><circle cx="15" cy="6.75" r="1.5" stroke-width="1.8" /><circle cx="9" cy="17.25" r="1.5" stroke-width="1.8" /></svg>
                        </div>
                        <span class="ml-3 hide-on-collapse whitespace-nowrap">Pengaturan Rumus</span>
                    </a>
                    <div class="sidebar-tooltip">Pengaturan Rumus</div>
                </div>

                @role('super_admin')
                <div class="mt-6 mb-2 px-3 hide-on-collapse">
                    <p class="text-[10px] uppercase tracking-wider text-red-200/70 font-bold">Super Admin</p>
                </div>
                <div class="sidebar-item-group relative group">
                    <a href="{{ route('admin-users.index') }}" 
                       class="flex items-center px-3 py-3 rounded-xl hover:bg-white/10 transition-all text-sm font-medium
                              {{ request()->routeIs('admin-users.*') ? 'active bg-white/20 border-l-4 border-white shadow-lg' : '' }}">
                        <div class="w-6 h-6 flex items-center justify-center shrink-0">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3.75 19.5 6v5.625c0 4.5-3.15 7.875-7.5 8.625-4.35-.75-7.5-4.125-7.5-8.625V6L12 3.75Z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 11.25a3 3 0 1 1 6 0v.75H9v-.75Zm-1.5 5.25v-2.25h9v2.25" /></svg>
                        </div>
                        <span class="ml-3 hide-on-collapse whitespace-nowrap">Manajemen Admin</span>
                    </a>
                    <div class="sidebar-tooltip">Manajemen Admin</div>
                </div>
                @endrole

            </nav>
            <div class="p-4 border-t border-white/10 shrink-0">
                <button id="sidebarLogoutBtn" class="sidebar-item-group relative group w-full flex items-center px-3 py-3 rounded-xl bg-white/10 hover:bg-white/15 border border-white/10 transition-all text-sm font-medium">
                    <div class="w-6 h-6 flex items-center justify-center shrink-0">
                        <svg class="h-5 w-5 text-rose-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15.75 8.25V5.625A1.875 1.875 0 0 0 13.875 3.75h-7.5A1.875 1.875 0 0 0 4.5 5.625v12.75a1.875 1.875 0 0 0 1.875 1.875h7.5a1.875 1.875 0 0 0 1.875-1.875V15.75M10.5 12h9m0 0-3-3m3 3-3 3" /></svg>
                    </div>
                    <span class="ml-3 hide-on-collapse whitespace-nowrap">Keluar</span>
                </button>
                <div class="hidden sidebar-collapsed-show absolute left-full top-[calc(100%-4rem)] ml-3 px-3 py-1.5 bg-gray-800 text-white text-xs rounded-md shadow-lg z-50 whitespace-nowrap">
                    Keluar
                </div>
            </div>
        </aside>
        
        <div class="flex min-w-0 flex-1 flex-col h-screen overflow-hidden bg-slate-100 transition-all duration-300">
            <header class="glass-header sticky top-0 z-30 h-16 shrink-0 sm:h-20">
                <div class="flex items-center justify-between gap-3 px-3 sm:px-5 lg:px-8 h-full">
                    <div class="flex min-w-0 items-center space-x-3 sm:space-x-4">
                        <button type="button" aria-label="Buka navigasi" @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2.5 rounded-xl text-slate-600 hover:bg-rose-50 hover:text-rose-700 focus:outline-none focus:ring-2 focus:ring-rose-300 transition-colors">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.5 6.75h15m-15 5.25h15m-15 5.25h15" /></svg>
                        </button>
                        
                        <div class="flex flex-col">
                            <h2 class="truncate text-base font-extrabold text-slate-900 sm:text-xl">
                                @yield('page-title', 'Dashboard')
                            </h2>
                            <div class="hidden md:flex items-center space-x-2 text-xs text-slate-500 mt-1 breadcrumb-enter">
                                <span class="cursor-default">Admin Panel</span>
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 18 6-6-6-6" /></svg>
                                <span class="text-rose-700 font-semibold">@yield('current-page', 'Dashboard')</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-2 sm:space-x-3 md:space-x-5">
                        <div class="h-8 w-px bg-gray-200 mx-2 hidden md:block"></div>
                        
                        <div class="relative" x-data="{ open: false }">
                            <button type="button" aria-label="Menu akun administrator" @click="open = !open" @click.outside="open = false" class="flex items-center space-x-2 sm:space-x-3 p-1 sm:pr-3 rounded-full border border-slate-200 hover:border-rose-200 hover:bg-white hover:shadow-sm transition-all bg-white">
                                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-rose-500 to-rose-700 flex items-center justify-center text-white font-bold text-sm shadow-inner">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <div class="hidden md:block text-left mr-2">
                                    <p class="text-sm font-semibold text-gray-700 leading-none">{{ Auth::user()->name }}</p>
                                    <p class="text-[10px] text-gray-400 mt-1 uppercase font-medium tracking-wide">Administrator</p>
                                </div>
                                <svg class="h-4 w-4 text-gray-400 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m6 9 6 6 6-6" /></svg>
                            </button>

                            <div x-show="open" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                                 x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                                 class="absolute right-0 mt-3 w-60 bg-white rounded-2xl shadow-xl border border-gray-100 py-2 z-50 origin-top-right overflow-hidden"
                                 style="display: none;">
                                
                                <div class="px-5 py-4 bg-gray-50/50 border-b border-gray-100">
                                    <p class="text-sm font-bold text-gray-800">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5 truncate">{{ Auth::user()->email }}</p>
                                </div>

                                <div class="py-2">
                                    @role('super_admin')
                                        <a href="{{ route('admin-users.index') }}" class="flex items-center px-5 py-2.5 text-sm text-gray-600 hover:bg-red-50 hover:text-red-600 transition-colors">
                                            <svg class="mr-2 h-5 w-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.5 6.75h9m3 0h3m-15 10.5h3m3 0h12M13.5 4.5v4.5m-3 6v4.5" /><circle cx="15" cy="6.75" r="1.5" stroke-width="1.8" /><circle cx="9" cy="17.25" r="1.5" stroke-width="1.8" /></svg>
                                            <span>Pengaturan</span>
                                        </a>
                                    @endrole
                                </div>

                                <div class="border-t border-gray-100 mt-1 pt-1">
                                    <button id="profileLogoutBtn" class="w-full text-left flex items-center px-5 py-3 text-sm text-red-600 hover:bg-red-50 transition-colors font-medium">
                                        <svg class="mr-2 h-5 w-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15.75 8.25V5.625A1.875 1.875 0 0 0 13.875 3.75h-7.5A1.875 1.875 0 0 0 4.5 5.625v12.75a1.875 1.875 0 0 0 1.875 1.875h7.5a1.875 1.875 0 0 0 1.875-1.875V15.75M10.5 12h9m0 0-3-3m3 3-3 3" /></svg>
                                        <span>Keluar</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 min-w-0 overflow-y-auto p-3 sm:p-5 lg:p-8 scroll-smooth" id="mainContent">
                <div class="max-w-7xl mx-auto animate-fadeIn">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
        @csrf
    </form>

    <div id="logoutModal" class="fixed inset-0 z-[60] flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
        <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="hideLogoutModal()"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm mx-4 p-6 transform scale-95 transition-transform duration-300">
            <div class="text-center">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4 animate-bounce-short">
                    <svg class="h-7 w-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v9m5.657-6.657a8 8 0 1 1-11.314 0" /></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900">Konfirmasi Keluar</h3>
                <p class="text-gray-500 mt-2 text-sm leading-relaxed">
                    Apakah Anda yakin ingin mengakhiri sesi ini? Anda harus login kembali untuk mengakses sistem.
                </p>
            </div>
            <div class="mt-8 flex gap-3">
                <button type="button" onclick="hideLogoutModal()" 
                        class="flex-1 px-4 py-2.5 rounded-xl border border-gray-300 text-gray-700 font-medium hover:bg-gray-50 hover:border-gray-400 transition-all">
                    Batal
                </button>
                <button type="button" onclick="confirmLogout()" 
                        class="flex-1 px-4 py-2.5 rounded-xl bg-gradient-to-r from-red-600 to-red-700 text-white font-medium hover:from-red-700 hover:to-red-800 shadow-md hover:shadow-lg transition-all">
                    Ya, Keluar
                </button>
            </div>
        </div>
    </div>
    
    <script>
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const toggleIcon = document.getElementById('toggleIcon');
        
        const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
        if (isCollapsed) {
            setSidebarCollapsed(true, false); 
        }

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', () => {
                const currentlyCollapsed = sidebar.classList.contains('w-20');
                setSidebarCollapsed(!currentlyCollapsed, true);
            });
        }

        function setSidebarCollapsed(collapse, animate) {
            if (collapse) {
                sidebar.classList.remove('w-64');
                sidebar.classList.add('w-20', 'sidebar-collapsed');
                if (toggleIcon) toggleIcon.classList.add('rotate-180');
                localStorage.setItem('sidebarCollapsed', 'true');
            } else {
                sidebar.classList.add('w-64');
                sidebar.classList.remove('w-20', 'sidebar-collapsed');
                if (toggleIcon) toggleIcon.classList.remove('rotate-180');
                localStorage.setItem('sidebarCollapsed', 'false');
            }
        }

        const logoutModal = document.getElementById('logoutModal');
        const modalContent = logoutModal ? logoutModal.querySelector('.relative') : null;
        const btns = [document.getElementById('sidebarLogoutBtn'), document.getElementById('profileLogoutBtn')];

        btns.forEach(btn => {
            if(btn) btn.addEventListener('click', showLogoutModal);
        });

        function showLogoutModal() {
            if (!logoutModal) return;
            logoutModal.classList.remove('hidden');
            setTimeout(() => {
                logoutModal.classList.remove('opacity-0');
                if (modalContent) {
                    modalContent.classList.remove('scale-95');
                    modalContent.classList.add('scale-100');
                }
            }, 10);
            document.body.style.overflow = 'hidden';
        }

        function hideLogoutModal() {
            if (!logoutModal) return;
            logoutModal.classList.add('opacity-0');
            if (modalContent) {
                modalContent.classList.remove('scale-100');
                modalContent.classList.add('scale-95');
            }
            setTimeout(() => {
                logoutModal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }, 300);
        }

        function confirmLogout() {
            document.getElementById('logout-form').submit();
        }

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && logoutModal && !logoutModal.classList.contains('hidden')) {
                hideLogoutModal();
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            const sidebarLinks = document.querySelectorAll('.sidebar-item-group a');
            
            sidebarLinks.forEach(link => {
                const href = link.getAttribute('href');
                if (href && currentPath.includes(href) && href !== '#' && href !== '') {
                    sidebarLinks.forEach(l => l.classList.remove('active', 'bg-white/20', 'border-l-4', 'border-white', 'shadow-lg'));
                    
                    link.classList.add('active', 'bg-white/20', 'border-l-4', 'border-white', 'shadow-lg');
                    
                }
            });
        });
    </script>
</body>
</html>
