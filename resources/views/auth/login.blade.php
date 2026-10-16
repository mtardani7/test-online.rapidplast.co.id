@extends('layouts.app')
@section('title', 'Masuk | Tes Online RapidPlast')
@section('content')
<div class="relative isolate min-h-screen overflow-hidden bg-slate-50 px-4 py-8 sm:px-6 sm:py-12">
    <div class="pointer-events-none absolute -left-24 -top-24 -z-10 h-72 w-72 rounded-full bg-rose-200/60 blur-3xl"></div>
    <div class="pointer-events-none absolute -bottom-28 -right-20 -z-10 h-80 w-80 rounded-full bg-indigo-100/70 blur-3xl"></div>
    <div class="mx-auto flex w-full max-w-5xl flex-col items-stretch justify-center gap-5 md:min-h-[calc(100vh-6rem)] md:flex-row md:items-center md:gap-7">
        <div class="w-full md:w-2/5 max-w-lg">
            <div class="overflow-hidden rounded-3xl border border-rose-100 bg-white p-6 shadow-xl shadow-rose-950/5 sm:p-8 md:p-9">
                <div class="flex items-center gap-4">
                    <img src="{{ asset('images/RAPIDPLAST.jpg') }}" class="h-16 w-24 shrink-0 rounded-xl border border-slate-100 object-contain p-1 sm:h-20 sm:w-28" alt="Logo RapidPlast">
                    <div>
                        <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-rose-600">Tes Online</p>
                        <p class="mt-1 text-lg font-black leading-tight text-slate-900 sm:text-xl">PT Rapid Plast Indonesia</p>
                    </div>
                </div>
                <h2 class="mt-7 text-2xl font-black tracking-tight text-slate-900 sm:text-3xl">Satu langkah lebih dekat dengan <span class="text-rose-600">tujuanmu.</span></h2>
                <p class="mt-3 text-sm leading-6 text-slate-600">Gunakan akunmu untuk mengakses tes dan melihat proses pengerjaan dengan nyaman.</p>
                <div class="mt-6 space-y-3 text-slate-600">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mt-1">
                            <div class="w-5 h-5 bg-gradient-to-r from-rose-500 to-rose-600 rounded-full flex items-center justify-center">
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="ml-3 text-sm leading-6">We are commited to anticipate the changing needs of our customer and response creatively and competitively by providing total solutions, exceptional service and quality products.</p>
                    </div>
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mt-1">
                            <div class="w-5 h-5 bg-gradient-to-r from-rose-500 to-rose-600 rounded-full flex items-center justify-center">
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="ml-3 text-sm leading-6">We are recruited performance driven and develop them through training and rewarding them based on their achievements, innovation and professionalism.</p>
                    </div>
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mt-1">
                            <div class="w-5 h-5 bg-gradient-to-r from-rose-500 to-rose-600 rounded-full flex items-center justify-center">
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="ml-3 text-sm leading-6">Achieve profitability and create lasting value to our sharehoulders and employee.</p>
                    </div>
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mt-1">
                            <div class="w-5 h-5 bg-gradient-to-r from-rose-500 to-rose-600 rounded-full flex items-center justify-center">
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="ml-3 text-sm leading-6">We are committed to respecting the environment and to improving the communities that we operate in.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="w-full md:w-3/5 max-w-lg">
            <div class="rounded-3xl border border-slate-200/80 bg-white p-6 shadow-xl shadow-slate-900/5 sm:p-9 md:p-10">
                <div class="text-center md:text-left mb-8">
                    <h2 class="text-3xl font-black tracking-tight text-slate-900 sm:text-4xl mb-3">
                        Selamat <span class="text-rose-600">Datang!</span>
                    </h2>
                    <p class="text-gray-600">Masuk untuk melanjutkan test assessment.</p>
                </div>
                <form method="post" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-semibold mb-2" for="email">
                            Alamat Email
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                </svg>
                            </div>
                            <input class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-transparent transition duration-200 @error('email') border-rose-500 @enderror" type="email" name="email" id="email" value="{{ old('email') }}" placeholder="you@example.com" required autocomplete="email" autofocus>
                        </div>
                        @error('email')
                        <div class="mt-2 flex items-center text-rose-600 text-sm">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-2">
                            <label class="block text-gray-700 text-sm font-semibold" for="password">
                                Password
                            </label>
                            <a href="{{ route('password.request') }}" class="text-sm text-rose-600 hover:text-rose-800 font-medium transition duration-200">
                                Lupa password?
                            </a>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input class="w-full pl-10 pr-12 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-transparent transition duration-200 @error('password') border-rose-500 @enderror" type="password" name="password" id="password" placeholder="••••••••" required autocomplete="current-password">
                            <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center focus:outline-none" aria-label="Toggle password visibility">
                                <svg id="eyeIcon" class="h-5 w-5 text-gray-400 hover:text-gray-600 transition duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg id="eyeSlashIcon" class="h-5 w-5 text-gray-400 hover:text-gray-600 transition duration-200 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L6.59 6.59m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                        @error('password')
                        <div class="mt-2 flex items-center text-rose-600 text-sm">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="mb-6 flex items-center">
                        <input type="checkbox" name="remember" id="remember" class="h-4 w-4 text-rose-600 focus:ring-rose-500 border-gray-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-gray-700">
                            Ingat saya di perangkat ini
                        </label>
                    </div>
                    <button type="submit" class="w-full bg-gradient-to-r from-rose-600 to-rose-700 hover:from-rose-700 hover:to-rose-800 text-white font-semibold py-3 px-4 rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2">
                        <div class="flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                            </svg>
                            Masuk untuk Lanjutkan
                        </div>
                    </button>
                    <div class="mt-8 mb-6 relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                    <span class="px-4 bg-white text-gray-500">Belum punya akun?</span>
                        </div>
                    </div>
                    <div class="text-center">
                        <a href="{{ route('register') }}"
                            class="inline-flex items-center text-rose-600 hover:text-rose-800 font-semibold transition duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                            Membuat akun baru
                        </a>
                    </div>
                </form>
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <p class="text-xs text-gray-500 text-center">
                        Dengan masuk, Anda setuju dengan
                        <a href="#" class="text-rose-600 hover:text-rose-800">Syarat Layanan</a>
                        dan
                        <a href="#" class="text-rose-600 hover:text-rose-800">Kebijakan Privasi</a>
                    </p>
                    <p class="mt-4 text-center text-sm text-gray-600">
                        Baru pertama kali ikut tes?
                        <a href="{{ route('landing') }}" class="font-semibold text-rose-600 hover:text-rose-800">Lihat panduan cara pengerjaan</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');
    const eyeSlashIcon = document.getElementById('eyeSlashIcon');

    togglePassword.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        if (type === 'password') {
            eyeIcon.classList.remove('hidden');
            eyeSlashIcon.classList.add('hidden');
        } else {
            eyeIcon.classList.add('hidden');
            eyeSlashIcon.classList.remove('hidden');
        }
    });
    togglePassword.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            togglePassword.click();
        }
    });
});
</script>
@endsection
