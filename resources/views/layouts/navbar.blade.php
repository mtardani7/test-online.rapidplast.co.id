<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/rapidplast.ico') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.cdnfonts.com/css/raleway-5" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        heading: ['Raleway', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#fff1f2',
                            100: '#ffe4e6',
                            500: '#f43f5e',
                            600: '#e11d48', 
                            700: '#be123c', 
                            800: '#9f1239', 
                        }
                    }
                }
            }
        }
    </script>
     <style>
        body {
            font-family: 'Inter', sans-serif;
            -webkit-font-smoothing: antialiased;
        }
        
        h1, h2, h3, .brand-font {
            font-family: 'Raleway', sans-serif;
        }

        .navbar-shrink {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .hamburger-line {
            transition: all 0.3s ease-in-out;
            transform-origin: center;
        }
        .hamburger-active .hamburger-line:nth-child(1) {
            transform: translateY(7px) rotate(45deg);
        }
        .hamburger-active .hamburger-line:nth-child(2) {
            opacity: 0;
            transform: translateX(-20px);
        }
        .hamburger-active .hamburger-line:nth-child(3) {
            transform: translateY(-7px) rotate(-45deg);
        }

        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .glass-nav {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.6);
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800">
    <div id="app">
        <div id="logoutModal" class="hidden fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full border border-slate-100">
                    <div class="bg-white px-6 pt-6 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-50 sm:mx-0 sm:h-10 sm:w-10 ring-4 ring-red-50/50">
                                <svg class="h-6 w-6 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-bold text-slate-900 brand-font" id="modal-title">
                                    Konfirmasi Keluar
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-slate-500">
                                        Apakah Anda yakin ingin mengakhiri sesi ini?
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50/50 px-6 py-4 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                        <button type="button" id="confirmLogout" 
                                class="w-full inline-flex justify-center items-center rounded-xl border border-transparent shadow-lg shadow-brand-500/30 px-4 py-2 bg-gradient-to-r from-brand-600 to-brand-700 text-base font-medium text-white hover:from-brand-700 hover:to-brand-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 sm:w-auto sm:text-sm transition-all duration-200 hover:-translate-y-0.5">
                            Ya, Keluar
                        </button>
                        <button type="button" id="cancelLogout" 
                                class="mt-3 w-full inline-flex justify-center items-center rounded-xl border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 sm:mt-0 sm:w-auto sm:text-sm transition-all duration-200">
                            Batal
                        </button>
                    </div>
                    <form id="logout-form-modal" action="{{ route('logout') }}" method="POST" class="hidden">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
        <nav id="mainNav" class="fixed top-0 left-0 right-0 z-50 glass-nav navbar-shrink">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-20">
                    <div class="flex-shrink-0 flex items-center gap-3 cursor-pointer" onclick="window.location='{{ route('user.tests') }}'">
                         <img src="{{ asset('images/logo3.png') }}" class="h-10 w-auto object-contain transition-transform hover:scale-105" alt="Logo">
                    </div>
                    <div class="hidden md:flex absolute left-1/2 transform -translate-x-1/2 flex-col items-center group">
                        <div class="flex items-center gap-2">
                            <span class="text-lg font-bold text-slate-800 tracking-wider brand-font uppercase group-hover:text-brand-700 transition-colors">Sistem</span>
                            <div class="h-4 w-[1px] bg-slate-300 rotate-12"></div>
                            <span class="text-lg font-bold text-brand-600 tracking-wider brand-font uppercase">Tes Online</span>
                        </div>
                        <div class="h-0.5 w-12 bg-gradient-to-r from-brand-500 to-brand-600 rounded-full mt-1 group-hover:w-24 transition-all duration-300"></div>
                    </div>
                    <div class="hidden md:flex items-center space-x-3">
                        @guest
                            @if (Route::has('login'))
                                <a href="{{ route('login') }}" 
                                   class="px-5 py-2.5 text-sm font-medium text-slate-600 hover:text-brand-600 transition-colors duration-200">
                                    {{ __('Masuk') }}
                                </a>
                            @endif
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" 
                                   class="px-6 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-brand-600 to-brand-700 rounded-full shadow-lg shadow-brand-500/30 hover:shadow-brand-500/50 hover:-translate-y-0.5 transition-all duration-300">
                                    {{ __('Daftar') }}
                                </a>
                            @endif
                        @else
                            <div class="relative group">
                                <button class="flex items-center space-x-3 pl-4 pr-3 py-2 text-slate-700 hover:bg-slate-50 rounded-full transition-all duration-200 border border-transparent hover:border-slate-200">
                                    <div class="flex flex-col items-end hidden lg:block">
                                        <span class="text-sm font-semibold text-slate-800">{{ Auth::user()->name }}</span>
                                        <span class="text-xs text-slate-500">Peserta</span>
                                    </div>
                                    <div class="h-10 w-10 bg-gradient-to-br from-brand-500 to-brand-600 rounded-full flex items-center justify-center text-white shadow-md ring-2 ring-white group-hover:ring-brand-100 transition-all">
                                        <span class="font-bold text-lg brand-font">
                                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                        </span>
                                    </div>
                                    <svg class="w-4 h-4 text-slate-400 transition-transform duration-300 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>

                                <div class="absolute right-0 mt-4 w-60 bg-white rounded-2xl shadow-xl border border-slate-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform origin-top-right translate-y-2 group-hover:translate-y-0 overflow-hidden">
                                    <div class="px-5 py-4 bg-slate-50 border-b border-slate-100 lg:hidden">
                                        <p class="text-sm font-bold text-slate-900">{{ Auth::user()->name }}</p>
                                        <p class="text-xs text-slate-500 truncate">{{ Auth::user()->email }}</p>
                                    </div>
                                    
                                    <div class="p-2">
                                        @if(Auth::user()->email_verified_at)
                                        <a href="{{ route('profile') }}" 
                                           class="flex items-center px-4 py-3 text-sm text-slate-600 rounded-xl hover:bg-red-50 hover:text-brand-600 transition-colors duration-200">
                                            <svg class="w-5 h-5 mr-3 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            {{ __('Profile Saya') }}
                                        </a>
                                        @endif
                                        
                                        <button type="button" 
                                                onclick="showLogoutModal()"
                                                class="flex items-center w-full text-left px-4 py-3 text-sm text-slate-600 rounded-xl hover:bg-red-50 hover:text-brand-600 transition-colors duration-200 group/logout">
                                            <svg class="w-5 h-5 mr-3 text-slate-400 group-hover/logout:text-brand-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                            </svg>
                                            {{ __('Keluar') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endguest
                    </div>

                    <button id="mobile-menu-button" class="md:hidden p-2 rounded-xl text-slate-600 hover:text-brand-600 hover:bg-red-50 transition-colors duration-200 focus:outline-none">
                        <div class="w-6 h-6 flex flex-col justify-center gap-[5px]">
                            <span class="hamburger-line block w-6 h-[2px] bg-current rounded-full"></span>
                            <span class="hamburger-line block w-6 h-[2px] bg-current rounded-full"></span>
                            <span class="hamburger-line block w-6 h-[2px] bg-current rounded-full"></span>
                        </div>
                    </button>
                </div>
            </div>

            <div id="mobile-menu" class="hidden md:hidden bg-white/95 backdrop-blur-md border-t border-slate-100 max-h-screen overflow-y-auto">
                <div class="px-4 py-2 space-y-1 pb-6">
                    <div class="py-4 text-center border-b border-slate-100 mb-2">
                        <span class="text-sm font-bold text-brand-600 tracking-wider brand-font uppercase block">Sistem Tes Online</span>
                    </div>

                    @guest
                        @if (Route::has('login'))
                            <a href="{{ route('login') }}" 
                               class="block px-4 py-3 text-base font-medium text-slate-600 hover:text-brand-600 hover:bg-red-50 rounded-xl transition-all duration-200">
                                {{ __('Masuk') }}
                            </a>
                        @endif
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" 
                               class="block mt-2 px-4 py-3 text-center text-base font-bold text-white bg-gradient-to-r from-brand-600 to-brand-700 rounded-xl shadow-md">
                                {{ __('Daftar Sekarang') }}
                            </a>
                        @endif
                    @else
                        <div class="px-4 py-4 bg-brand-50/50 rounded-2xl mb-4 border border-brand-100">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 bg-gradient-to-r from-brand-600 to-brand-700 rounded-full flex items-center justify-center shadow-md">
                                    <span class="text-white font-bold text-lg brand-font">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </span>
                                </div>
                                <div class="overflow-hidden">
                                    <p class="font-bold text-slate-800 truncate">{{ Auth::user()->name }}</p>
                                    <p class="text-sm text-slate-500 truncate">{{ Auth::user()->email }}</p>
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('profile') }}" 
                           class="flex items-center px-4 py-3 text-base font-medium text-slate-600 hover:text-brand-600 hover:bg-white border border-transparent hover:border-slate-100 hover:shadow-sm rounded-xl transition-all duration-200 mb-2">
                            <div class="p-2 bg-slate-100 rounded-lg mr-3 text-slate-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            {{ __('Profile Saya') }}
                        </a>
                        
                        <button type="button" 
                                onclick="showLogoutModal()"
                                class="w-full text-left flex items-center px-4 py-3 text-base font-medium text-red-600 hover:bg-red-50 rounded-xl transition-all duration-200">
                            <div class="p-2 bg-red-100 rounded-lg mr-3 text-red-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                            </div>
                            {{ __('Keluar') }}
                        </button>
                    @endguest
                </div>
            </div>
        </nav>

        <main class="pt-24 min-h-screen bg-slate-50">
            @yield('content')
        </main>
    </div>

    <script>
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        
        mobileMenuButton.addEventListener('click', function() {
            this.classList.toggle('hamburger-active');
            if (mobileMenu.classList.contains('hidden')) {
                mobileMenu.classList.remove('hidden');
                mobileMenu.animate([
                    { opacity: 0, transform: 'translateY(-10px)' },
                    { opacity: 1, transform: 'translateY(0)' }
                ], { duration: 200, fill: 'forwards' });
            } else {
                mobileMenu.classList.add('hidden');
            }
        });
        document.addEventListener('click', function(event) {
            const isClickInsideMenu = mobileMenu.contains(event.target);
            const isClickOnHamburger = mobileMenuButton.contains(event.target);
            
            if (!isClickInsideMenu && !isClickOnHamburger && !mobileMenu.classList.contains('hidden')) {
                mobileMenu.classList.add('hidden');
                mobileMenuButton.classList.remove('hamburger-active');
            }
        });

        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('mainNav');
            if (window.scrollY > 20) {
                navbar.classList.add('shadow-md');
            } else {
                navbar.classList.remove('shadow-md');
            }
        });
        function showLogoutModal() {
            const modal = document.getElementById('logoutModal');
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden'; 
        
            if(!mobileMenu.classList.contains('hidden')){
                mobileMenu.classList.add('hidden');
                mobileMenuButton.classList.remove('hamburger-active');
            }
        }

        function hideLogoutModal() {
            const modal = document.getElementById('logoutModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto'; 
        }

        function performLogout() {
            localStorage.clear();
            document.getElementById('logout-form-modal').submit();
        }
        document.getElementById('confirmLogout').addEventListener('click', performLogout);
        document.getElementById('cancelLogout').addEventListener('click', hideLogoutModal);
        
        document.getElementById('logoutModal').addEventListener('click', function(event) {
            if (event.target.closest('.inline-block') === null) {
                hideLogoutModal();
            }
        });

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                const modal = document.getElementById('logoutModal');
                if (!modal.classList.contains('hidden')) {
                    hideLogoutModal();
                }
            }
        });
    </script>
</body>
</html>