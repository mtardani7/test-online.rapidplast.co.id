@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="flex justify-center">
            <div class="w-16 h-16 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center shadow-lg">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                </svg>
            </div>
        </div>
        
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
            Reset Kata Sandi Anda
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600 max-w">
            Masukkan alamat email Anda dan kami akan mengirimkan tautan untuk mereset kata sandi Anda
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow-xl sm:rounded-2xl sm:px-10 border border-gray-100">
            @if (session('status'))
            <div class="mb-8 rounded-lg bg-gradient-to-r from-green-50 to-emerald-50 p-4 border border-green-200">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">
                            {{ session('status') }}
                        </p>
                    </div>
                </div>
            </div>
            @endif

            <div class="mb-8 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-4 border border-blue-200">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Cara Kerjanya</h3>
                        <div class="mt-1 text-sm text-blue-700">
                            <p>Kami akan mengirim tautan reset kata sandi ke email Anda. Klik tautan tersebut untuk membuat kata sandi baru.</p>
                        </div>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('password.email') }}" class="space-y-6" id="resetRequestForm">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                        Alamat Email
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                            </svg>
                        </div>
                        <input id="email" 
                               name="email" 
                               type="email" 
                               required
                               autocomplete="email"
                               value="{{ old('email') }}"
                               autofocus
                               class="appearance-none block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition duration-200 @error('email') border-red-500 @enderror"
                               placeholder="anda@contoh.com">
                    </div>
                    
                    @error('email')
                    <div class="mt-2 flex items-center text-red-600 text-sm">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div>
                    <button type="submit" 
                            id="submitButton"
                            class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-200 transform hover:-translate-y-0.5">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Kirim Tautan Reset Kata Sandi
                    </button>
                </div>
                <div class="text-center pt-6 border-t border-gray-200">
                    <p class="text-xs text-gray-500">
                        Periksa folder spam jika Anda tidak menerima email dalam beberapa menit.
                    </p>
                </div>
            </form>
            <div class="mt-8 text-center">
                <a href="{{ route('login') }}" 
                   class="inline-flex items-center text-sm font-medium text-red-600 hover:text-red-800 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Masuk
                </a>
            </div>
        </div>

        <div class="mt-8 bg-white rounded-xl shadow-sm p-4 border border-gray-200">
            <h3 class="text-sm font-medium text-gray-900 mb-3">Butuh Bantuan?</h3>
            <div class="space-y-3">
                <div class="flex items-start">
                    <svg class="w-4 h-4 text-red-500 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <p class="text-xs font-medium text-gray-800">Tidak melihat email?</p>
                        <p class="text-xs text-gray-600 mt-1">Tunggu beberapa menit dan periksa folder spam Anda.</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <svg class="w-4 h-4 text-red-500 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <p class="text-xs font-medium text-gray-800">Alamat email salah?</p>
                        <p class="text-xs text-gray-600 mt-1">Pastikan Anda memasukkan email yang terkait dengan akun Anda.</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <svg class="w-4 h-4 text-red-500 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <p class="text-xs font-medium text-gray-800">Masih mengalami masalah?</p>
                        <p class="text-xs text-gray-600 mt-1">
                            <a href="mailto:support@example.com" class="text-red-600 hover:text-red-800">Hubungi tim dukungan kami</a> untuk bantuan.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6 text-center">
            <p class="text-xs text-gray-500">
                Untuk alasan keamanan, tautan reset kata sandi kadaluarsa setelah 60 menit.
            </p>
        </div>
    </div>
</div>

<style>
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-slide-in {
        animation: slideIn 0.3s ease-out;
    }
</style>

<script>
    const form = document.getElementById('resetRequestForm');
    const submitButton = document.getElementById('submitButton');
    
    form.addEventListener('submit', function(e) {
        const emailInput = document.getElementById('email');
        const email = emailInput.value.trim();
        
        if (!email) {
            e.preventDefault();
            alert('Silakan masukkan alamat email Anda.');
            emailInput.focus();
            return false;
        }
        
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            e.preventDefault();
            alert('Silakan masukkan alamat email yang valid.');
            emailInput.focus();
            return false;
        }
        
        submitButton.disabled = true;
        submitButton.innerHTML = `
            <svg class="w-5 h-5 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Mengirim Tautan Reset...
        `;
        
        const successMessage = document.querySelector('.bg-gradient-to-r.from-green-50');
        if (successMessage) {
            successMessage.classList.add('animate-slide-in');
        }
    });
    
    @if($errors->has('email'))
        document.getElementById('email').focus();
    @endif

    const emailInput = document.getElementById('email');
    emailInput.addEventListener('input', function() {
        if (this.classList.contains('border-red-500')) {
            this.classList.remove('border-red-500');
            const errorDiv = this.parentElement.nextElementSibling;
            if (errorDiv && errorDiv.classList.contains('text-red-600')) {
                errorDiv.style.display = 'none';
            }
        }
    });
</script>
@endsection