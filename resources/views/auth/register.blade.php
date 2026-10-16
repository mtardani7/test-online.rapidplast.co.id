@extends('layouts.app')

@section('title', 'Daftar | Tes Online RapidPlast')
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
                <h2 class="mt-7 text-2xl font-black tracking-tight text-slate-900 sm:text-3xl">Mulai dengan membuat <span class="text-rose-600">akunmu.</span></h2>
                <p class="mt-3 text-sm leading-6 text-slate-600">Daftar sekali untuk mengakses tes dan menyimpan progres pengerjaanmu.</p>
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
                        Buat <span class="text-rose-600">Akun</span>
                    </h2>
                    <p class="text-gray-600">Membuat akun untuk melanjutkan test assessment.</p>
                    <p class="mt-3 text-sm text-gray-600">
                        Ingin tahu alur tesnya?
                        <a href="{{ route('landing') }}" class="font-semibold text-rose-600 hover:text-rose-800">Baca panduan singkat</a>
                    </p>
                </div>

                <form method="post" action="{{ route('register') }}" id="registerForm">
                    @csrf

                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-semibold mb-2" for="name">
                            Nama Lengkap
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <input class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-transparent transition duration-200 @error('name') border-rose-500 @enderror"
                                   type="text"
                                   name="name"
                                   id="name"
                                   value="{{ old('name') }}"
                                   placeholder="Masukkan nama lengkap"
                                   required
                                   autocomplete="name"
                                   autofocus>
                        </div>
                        @error('name')
                        <div class="mt-2 flex items-center text-rose-600 text-sm">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

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
                            <input class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-transparent transition duration-200 @error('email') border-rose-500 @enderror"
                                   type="email"
                                   name="email"
                                   id="email"
                                   value="{{ old('email') }}"
                                   placeholder="Masukkan email, misal: you@example.com"
                                   required
                                   autocomplete="email">
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
                        <label class="block text-gray-700 text-sm font-semibold mb-2" for="password">
                            Password
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input class="w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-transparent transition duration-200 @error('password') border-rose-500 @enderror"
                                   type="password"
                                   name="password"
                                   id="password"
                                   placeholder="••••••••"
                                   required
                                   autocomplete="new-password">

                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <button type="button"
                                        id="togglePassword"
                                        class="text-gray-400 hover:text-rose-500 focus:outline-none focus:text-rose-500">
                                    <svg id="eyeIcon" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
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

                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-semibold mb-2" for="password-confirm">
                            Konfirmasi Password
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <input class="w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-transparent transition duration-200"
                                   type="password"
                                   name="password_confirmation"
                                   id="password-confirm"
                                   placeholder="••••••••"
                                   required
                                   autocomplete="new-password">

                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <button type="button"
                                        id="toggleConfirmPassword"
                                        class="text-gray-400 hover:text-rose-500 focus:outline-none focus:text-rose-500">
                                    <svg id="eyeIconConfirm" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="mb-6 bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <h4 class="text-sm font-medium text-gray-700 mb-3">Persyaratan Kata Sandi:</h4>
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <div id="lengthCheck" class="w-5 h-5 rounded-full border border-gray-300 mr-3 flex items-center justify-center">
                                    <svg class="w-3 h-3 text-white hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <span class="text-sm text-gray-600">Minimal 8 karakter</span>
                            </div>
                            <div class="flex items-center">
                                <div id="letterCheck" class="w-5 h-5 rounded-full border border-gray-300 mr-3 flex items-center justify-center">
                                    <svg class="w-3 h-3 text-white hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <span class="text-sm text-gray-600">Minimal satu huruf</span>
                            </div>
                            <div class="flex items-center">
                                <div id="numberCheck" class="w-5 h-5 rounded-full border border-gray-300 mr-3 flex items-center justify-center">
                                    <svg class="w-3 h-3 text-white hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <span class="text-sm text-gray-600">Minimal satu angka</span>
                            </div>
                            <div class="flex items-center">
                                <div id="matchCheck" class="w-5 h-5 rounded-full border border-gray-300 mr-3 flex items-center justify-center">
                                    <svg class="w-3 h-3 text-white hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <span class="text-sm text-gray-600">Kata sandi harus sama</span>
                            </div>
                        </div>

                        <div class="mt-4">
                            <div class="flex justify-between text-xs text-gray-500 mb-1">
                                <span>Kekuatan Kata Sandi:</span>
                                <span id="strengthText">Lemah</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div id="strengthBar" class="h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <button type="submit"
                                id="submitButton"
                                class="w-full bg-gradient-to-r from-rose-600 to-rose-700 hover:from-rose-700 hover:to-rose-800 text-white font-semibold py-3 px-4 rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                            <div class="flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                </svg>
                                <span id="buttonText">Membuat Akun</span>
                            </div>
                        </button>
                    </div>

                    <div class="mt-8 mb-6 relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-4 bg-white text-gray-500">Sudah Mempunyai Akun?</span>
                        </div>
                    </div>

                    <div class="text-center">
                        <a href="{{ route('login') }}"
                           class="inline-flex items-center text-rose-600 hover:text-rose-800 font-semibold transition duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                            </svg>
                            Masuk dengan akun terdaftar.
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');

    const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
    const confirmPasswordInput = document.getElementById('password-confirm');
    const eyeIconConfirm = document.getElementById('eyeIconConfirm');

    togglePassword.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);

        if (type === 'text') {
            eyeIcon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L6.59 6.59m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
            `;
        } else {
            eyeIcon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            `;
        }
    });

    toggleConfirmPassword.addEventListener('click', function() {
        const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        confirmPasswordInput.setAttribute('type', type);

        if (type === 'text') {
            eyeIconConfirm.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L6.59 6.59m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
            `;
        } else {
            eyeIconConfirm.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            `;
        }
    });

    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('password-confirm');
    const submitButton = document.getElementById('submitButton');
    const buttonText = document.getElementById('buttonText');

    const lengthCheck = document.getElementById('lengthCheck');
    const letterCheck = document.getElementById('letterCheck');
    const numberCheck = document.getElementById('numberCheck');
    const matchCheck = document.getElementById('matchCheck');
    const strengthBar = document.getElementById('strengthBar');
    const strengthText = document.getElementById('strengthText');

    function checkPasswordStrength() {
        const pass = password.value;
        const confirmPass = confirmPassword.value;

        let strength = 0;

        if (pass.length >= 8) {
            strength += 25;
            lengthCheck.classList.add('bg-green-500', 'border-green-500');
            lengthCheck.querySelector('svg').classList.remove('hidden');
        } else {
            lengthCheck.classList.remove('bg-green-500', 'border-green-500');
            lengthCheck.querySelector('svg').classList.add('hidden');
        }

        if (/[a-zA-Z]/.test(pass)) {
            strength += 25;
            letterCheck.classList.add('bg-green-500', 'border-green-500');
            letterCheck.querySelector('svg').classList.remove('hidden');
        } else {
            letterCheck.classList.remove('bg-green-500', 'border-green-500');
            letterCheck.querySelector('svg').classList.add('hidden');
        }

        if (/[0-9]/.test(pass)) {
            strength += 25;
            numberCheck.classList.add('bg-green-500', 'border-green-500');
            numberCheck.querySelector('svg').classList.remove('hidden');
        } else {
            numberCheck.classList.remove('bg-green-500', 'border-green-500');
            numberCheck.querySelector('svg').classList.add('hidden');
        }

        if (pass && confirmPass && pass === confirmPass) {
            strength += 25;
            matchCheck.classList.add('bg-green-500', 'border-green-500');
            matchCheck.querySelector('svg').classList.remove('hidden');
        } else {
            matchCheck.classList.remove('bg-green-500', 'border-green-500');
            matchCheck.querySelector('svg').classList.add('hidden');
        }

        strengthBar.style.width = `${strength}%`;

        if (strength <= 25) {
            strengthBar.className = 'h-2 rounded-full transition-all duration-300 bg-rose-500';
            strengthText.textContent = 'Lemah';
            strengthText.className = 'text-rose-600 font-medium';
        } else if (strength <= 50) {
            strengthBar.className = 'h-2 rounded-full transition-all duration-300 bg-orange-500';
            strengthText.textContent = 'Cukup';
            strengthText.className = 'text-orange-600 font-medium';
        } else if (strength <= 75) {
            strengthBar.className = 'h-2 rounded-full transition-all duration-300 bg-yellow-500';
            strengthText.textContent = 'Baik';
            strengthText.className = 'text-yellow-600 font-medium';
        } else {
            strengthBar.className = 'h-2 rounded-full transition-all duration-300 bg-green-500';
            strengthText.textContent = 'Kuat';
            strengthText.className = 'text-green-600 font-medium';
        }

        if (strength === 100) {
            submitButton.disabled = false;
        } else {
            submitButton.disabled = true;
        }
    }

    password.addEventListener('input', checkPasswordStrength);
    confirmPassword.addEventListener('input', checkPasswordStrength);

    checkPasswordStrength();

    const form = document.getElementById('registerForm');
    form.addEventListener('submit', function(e) {
        if (submitButton.disabled) {
            e.preventDefault();
            alert('Harap penuhi semua persyaratan kata sandi sebelum mendaftar.');
            return false;
        }

        submitButton.disabled = true;
        buttonText.innerHTML = `
            <svg class="w-5 h-5 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Membuat Akun...
        `;
    });

    const nameInput = document.getElementById('name');
    const emailInput = document.getElementById('email');

    nameInput.addEventListener('input', function() {
        if (this.classList.contains('border-rose-500')) {
            this.classList.remove('border-rose-500');
            const errorDiv = this.parentElement.nextElementSibling;
            if (errorDiv && errorDiv.classList.contains('text-rose-600')) {
                errorDiv.style.display = 'none';
            }
        }
    });

    emailInput.addEventListener('input', function() {
        if (this.classList.contains('border-rose-500')) {
            this.classList.remove('border-rose-500');
            const errorDiv = this.parentElement.nextElementSibling;
            if (errorDiv && errorDiv.classList.contains('text-rose-600')) {
                errorDiv.style.display = 'none';
            }
        }
    });
</script>
@endsection
