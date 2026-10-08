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

        .sidebar-item-group .active i {
            color: #ffffff;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
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
<body class="bg-gray-50 text-gray-800 antialiased" x-data="{ sidebarOpen: false }">
    <div class="flex h-screen overflow-hidden">
        <div x-show="sidebarOpen" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"
             @click="sidebarOpen = false"></div>

        <aside id="sidebar" 
               class="fixed lg:relative flex flex-col h-full bg-gradient-to-b from-red-700 via-red-600 to-red-800 text-white z-50 
                      transform transition-all duration-300 ease-in-out shadow-2xl w-64"
               :class="{ '-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen, 'lg:translate-x-0': true }">
            <div class="flex items-center justify-between h-20 px-4 border-b border-red-500/30 shrink-0">
                <div class="flex items-center gap-3 overflow-hidden whitespace-nowrap hide-on-collapse transition-all duration-300">
                    <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center shadow-md shrink-0">
                        <img src="{{ asset('images/RAPIDPLAST.jpg') }}" class="rounded w-8 h-8 object-contain" alt="Logo">
                    </div>
                    <div class="flex flex-col">
                        <h1 class="text-sm font-bold tracking-tight leading-tight">PT Rapid Plast</h1>
                        <span class="text-xs text-red-200 font-medium">Indonesia</span>
                    </div>
                </div>
                <button id="sidebarToggle" class="hidden lg:flex items-center justify-center w-8 h-8 rounded-full bg-red-800/50 hover:bg-white/20 text-white transition-all shadow-sm">
                    <i class="fas fa-chevron-left text-xs transition-transform duration-300" id="toggleIcon"></i>
                </button>
                <button @click="sidebarOpen = false" class="lg:hidden text-white hover:text-red-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <nav class="flex-1 overflow-y-auto overflow-x-hidden py-6 px-3 space-y-1 custom-scrollbar">
                <div class="sidebar-item-group relative group">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center px-3 py-3 rounded-xl hover:bg-white/10 transition-all text-sm font-medium
                              {{ request()->routeIs('admin.dashboard') ? 'active bg-white/20 border-l-4 border-white shadow-lg' : '' }}">
                        <div class="w-6 h-6 flex items-center justify-center shrink-0">
                            <i class="fas fa-tachometer-alt text-lg"></i>
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
                            <i class="fas fa-chart-pie text-lg"></i>
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
                            <i class="fas fa-history text-lg"></i>
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
                            <i class="fas fa-users text-lg"></i>
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
                            <i class="fas fa-sliders-h text-lg"></i>
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
                            <i class="fas fa-user-shield text-lg"></i>
                        </div>
                        <span class="ml-3 hide-on-collapse whitespace-nowrap">Manajemen Admin</span>
                    </a>
                    <div class="sidebar-tooltip">Manajemen Admin</div>
                </div>
                @endrole

            </nav>
            <div class="p-4 border-t border-red-500/30 shrink-0">
                <button id="sidebarLogoutBtn" class="sidebar-item-group relative group w-full flex items-center px-3 py-3 rounded-xl bg-red-900/30 hover:bg-red-900/50 border border-red-500/20 transition-all text-sm font-medium">
                    <div class="w-6 h-6 flex items-center justify-center shrink-0">
                        <i class="fas fa-sign-out-alt text-lg text-red-200"></i>
                    </div>
                    <span class="ml-3 hide-on-collapse whitespace-nowrap">Keluar</span>
                </button>
                <div class="hidden sidebar-collapsed-show absolute left-full top-[calc(100%-4rem)] ml-3 px-3 py-1.5 bg-gray-800 text-white text-xs rounded-md shadow-lg z-50 whitespace-nowrap">
                    Keluar
                </div>
            </div>
        </aside>
        
        <div class="flex-1 flex flex-col h-screen overflow-hidden bg-gray-50 transition-all duration-300">
            <header class="glass-header sticky top-0 z-30 h-20">
                <div class="flex items-center justify-between px-6 h-full">
                    <div class="flex items-center space-x-4">
                        <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 rounded-lg text-gray-600 hover:bg-gray-100 hover:text-red-600 focus:outline-none transition-colors">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        
                        <div class="flex flex-col">
                            <h2 class="text-xl font-bold text-gray-800">
                                @yield('page-title', 'Dashboard')
                            </h2>
                            <div class="hidden md:flex items-center space-x-2 text-xs text-gray-500 mt-1 breadcrumb-enter">
                                <span class="hover:text-red-600 cursor-default">Admin Panel</span>
                                <i class="fas fa-chevron-right text-[10px]"></i>
                                <span class="text-red-600 font-medium">@yield('current-page', 'Current Page')</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-3 md:space-x-5">
                        <div class="h-8 w-px bg-gray-200 mx-2 hidden md:block"></div>
                        
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" @click.outside="open = false" class="flex items-center space-x-3 p-1.5 pr-3 rounded-full border border-gray-200 hover:border-red-200 hover:bg-white hover:shadow-sm transition-all bg-white">
                                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-red-500 to-pink-600 flex items-center justify-center text-white font-bold text-sm shadow-inner">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <div class="hidden md:block text-left mr-2">
                                    <p class="text-sm font-semibold text-gray-700 leading-none">{{ Auth::user()->name }}</p>
                                    <p class="text-[10px] text-gray-400 mt-1 uppercase font-medium tracking-wide">Administrator</p>
                                </div>
                                <i class="fas fa-chevron-down text-gray-300 text-xs transition-transform duration-200" :class="{'rotate-180': open}"></i>
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
                                            <i class="fas fa-cog w-5 opacity-70"></i>
                                            <span>Pengaturan</span>
                                        </a>
                                    @endrole
                                </div>

                                <div class="border-t border-gray-100 mt-1 pt-1">
                                    <button id="profileLogoutBtn" class="w-full text-left flex items-center px-5 py-3 text-sm text-red-600 hover:bg-red-50 transition-colors font-medium">
                                        <i class="fas fa-sign-out-alt w-5 opacity-70"></i>
                                        <span>Keluar</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-4 md:p-8 scroll-smooth" id="mainContent">
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
                    <i class="fas fa-power-off text-2xl text-red-600"></i>
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
                    
                    const icon = link.querySelector('i');
                    if (icon) {
                        icon.style.color = '#ffffff';
                        icon.style.textShadow = '0 2px 4px rgba(0,0,0,0.2)';
                    }
                }
            });
        });
    </script>
</body>
</html>