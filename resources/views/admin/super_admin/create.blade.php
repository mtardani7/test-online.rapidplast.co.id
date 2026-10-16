@extends('layouts.sidebar')

@section('content')
<div class="mx-auto max-w-4xl space-y-6 px-3 py-5 sm:px-6 sm:py-7">
    <div class="mb-8">
        <a href="{{ route('admin-users.index') }}" 
           class="inline-flex items-center text-red-600 hover:text-red-700 mb-4">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
        </a>
        <h1 class="text-2xl font-bold text-gray-900 sm:text-3xl">
            <i class="fas fa-user-plus text-red-600 mr-3"></i>Tambah Admin Baru
        </h1>
        <p class="text-gray-600 mt-2">Buat akun administrator baru untuk sistem recruitment</p>
    </div>
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="bg-gradient-to-r from-red-600 to-red-700 px-4 py-4 sm:px-6">
            <h2 class="text-xl font-bold text-white">
                <i class="fas fa-user-cog mr-2"></i>Form Tambah Administrator
            </h2>
        </div>
        
        <div class="p-4 sm:p-6">
            <form action="{{ route('admin-users.store') }}" method="POST" id="adminForm">
                @csrf
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">
                        <i class="fas fa-info-circle text-red-600 mr-2"></i>Informasi Dasar
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Lengkap <span class="text-red-600">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   id="name"
                                   value="{{ old('name') }}"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition"
                                   placeholder="Masukkan nama lengkap">
                            @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Email <span class="text-red-600">*</span>
                            </label>
                            <input type="email" 
                                   name="email" 
                                   id="email"
                                   value="{{ old('email') }}"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition"
                                   placeholder="contoh@email.com">
                            @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">
                        <i class="fas fa-key text-red-600 mr-2"></i>Metode Password
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div class="relative">
                            <input type="radio" 
                                   name="password_mode" 
                                   id="manualMode" 
                                   value="manual" 
                                   class="hidden peer"
                                   onchange="togglePasswordMode('manual')"
                                   {{ old('password_mode', 'manual') == 'manual' ? 'checked' : '' }}>
                            <label for="manualMode" 
                                   class="block p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-red-300 peer-checked:border-red-500 peer-checked:bg-red-50 transition-all">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 w-10 h-10 bg-red-100 rounded-full flex items-center justify-center mr-4">
                                        <i class="fas fa-key text-red-600"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900">Password Manual</h4>
                                        <p class="text-sm text-gray-600">Masukkan password secara manual</p>
                                    </div>
                                </div>
                            </label>
                        </div>
                        <div class="relative">
                            <input type="radio" 
                                   name="password_mode" 
                                   id="generateMode" 
                                   value="generate" 
                                   class="hidden peer"
                                   onchange="togglePasswordMode('generate')"
                                   {{ old('password_mode') == 'generate' ? 'checked' : '' }}>
                            <label for="generateMode" 
                                   class="block p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-red-300 peer-checked:border-red-500 peer-checked:bg-red-50 transition-all">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 w-10 h-10 bg-red-100 rounded-full flex items-center justify-center mr-4">
                                        <i class="fas fa-random text-red-600"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900">Generate Otomatis</h4>
                                        <p class="text-sm text-gray-600">Sistem akan membuat password acak</p>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                    <div id="manualPasswordField" class="{{ old('password_mode', 'manual') == 'manual' ? '' : 'hidden' }}">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Password Manual <span class="text-red-600">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" 
                                   name="password" 
                                   id="manualPassword"
                                   value="{{ old('password') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition pr-12"
                                   placeholder="Minimal 8 karakter"
                                   autocomplete="new-password"
                                   {{ old('password_mode', 'manual') == 'manual' ? 'required' : '' }}>
                            <button type="button" 
                                    onclick="togglePasswordVisibility('manualPassword', 'manualPasswordIcon')"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                <i id="manualPasswordIcon" class="fas fa-eye"></i>
                            </button>
                        </div>
                        <p class="mt-1 text-sm text-gray-500">Password harus minimal 8 karakter</p>
                        @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div id="generatePasswordField" class="{{ old('password_mode') == 'generate' ? '' : 'hidden' }}">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Password yang Digenerate
                        </label>
                        <div id="generatedPasswordDisplay" class="mb-4">
                            <div class="password-display px-4 py-3 rounded-lg mb-3">
                                <div class="flex items-center justify-between">
                                    <code id="generatedPasswordText" class="text-lg font-mono text-gray-800"></code>
                                    <div class="flex items-center space-x-2">
                                        <button type="button" 
                                                onclick="togglePasswordVisibility('generatedPasswordHidden')"
                                                class="text-gray-600 hover:text-gray-800">
                                            <i id="generatedPasswordIcon" class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" 
                                                onclick="copyToClipboard(document.getElementById('generatedPasswordText').textContent, 'generatedPasswordDisplay')"
                                                class="text-gray-600 hover:text-gray-800">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="generated_password" id="generatedPasswordHidden">
                            
                            <button type="button" 
                                    onclick="generateNewPassword()"
                                    class="inline-flex items-center px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition">
                                <i class="fas fa-sync-alt mr-2"></i> Generate Password Baru
                            </button>
                        </div>
                        
                        <div class="mt-4">
                            <p class="text-sm font-medium text-gray-700 mb-2">Kekuatan Password:</p>
                            <div class="flex items-center space-x-2">
                                <div id="passwordStrengthBar" class="h-2 flex-1 bg-gray-200 rounded-full overflow-hidden">
                                    <div id="passwordStrengthFill" class="h-full bg-green-500 rounded-full w-0"></div>
                                </div>
                                <span id="passwordStrengthText" class="text-sm font-medium text-gray-600">-</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-between items-center pt-6 border-t">
                    <div>
                        <p class="text-sm text-gray-600">
                            <i class="fas fa-info-circle mr-1 text-red-600"></i>
                            Pastikan untuk menyimpan password yang dibuat
                        </p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('admin-users.index') }}" 
                           class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                            <i class="fas fa-times mr-2"></i> Batal
                        </a>
                        <button type="submit" 
                                class="px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white font-semibold rounded-lg shadow-md hover:from-red-700 hover:to-red-800 transition">
                            <i class="fas fa-save mr-2"></i> Simpan Admin
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function generatePassword(length = 12) {
    const uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    const lowercase = 'abcdefghijklmnopqrstuvwxyz';
    const numbers = '0123456789';
    const symbols = '!@#$%^&*';
    const allChars = uppercase + lowercase + numbers + symbols;
    let password = '';
    password += uppercase[Math.floor(Math.random() * uppercase.length)];
    password += lowercase[Math.floor(Math.random() * lowercase.length)];
    password += numbers[Math.floor(Math.random() * numbers.length)];
    password += symbols[Math.floor(Math.random() * symbols.length)];
    for (let i = 4; i < length; i++) {
        password += allChars[Math.floor(Math.random() * allChars.length)];
    }
    password = password.split('').sort(() => Math.random() - 0.5).join('');
    
    return password;
}

function generateNewPassword() {
    const password = generatePassword();
    const display = document.getElementById('generatedPasswordText');
    const hiddenInput = document.getElementById('generatedPasswordHidden');
    
    if (display && hiddenInput) {
        display.textContent = password;
        hiddenInput.value = password;
        checkPasswordStrength(password);
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true
        });
        Toast.fire({
            icon: 'success',
            title: 'Password baru telah digenerate!'
        });
    }
}

function togglePasswordMode(mode) {
    const manualField = document.getElementById('manualPasswordField');
    const generateField = document.getElementById('generatePasswordField');
    
    if (mode === 'manual') {
        manualField.classList.remove('hidden');
        generateField.classList.add('hidden');
        const manualPassword = document.getElementById('manualPassword');
        if (manualPassword) {
            manualPassword.setAttribute('required', 'required');
        }
    } else {
        manualField.classList.add('hidden');
        generateField.classList.remove('hidden');
        const manualPassword = document.getElementById('manualPassword');
        if (manualPassword) {
            manualPassword.removeAttribute('required');
        }
        if (!document.getElementById('generatedPasswordText').textContent) {
            generateNewPassword();
        }
    }
}

function togglePasswordVisibility(inputId, iconId = null) {
    const input = document.getElementById(inputId);
    
    if (input) {
        if (input.type === 'password') {
            input.type = 'text';
            if (iconId) {
                const icon = document.getElementById(iconId);
                if (icon) {
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                }
            }
        } else {
            input.type = 'password';
            if (iconId) {
                const icon = document.getElementById(iconId);
                if (icon) {
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            }
        }
    }
}

function checkPasswordStrength(password) {
    const bar = document.getElementById('passwordStrengthFill');
    const text = document.getElementById('passwordStrengthText');
    
    let strength = 0;
    
    if (password.length >= 8) strength += 25;
    if (password.length >= 12) strength += 25;
    
    if (/[A-Z]/.test(password)) strength += 20;
    if (/[0-9]/.test(password)) strength += 15;
    if (/[^A-Za-z0-9]/.test(password)) strength += 15;
    
    bar.style.width = `${strength}%`;
    
    if (strength >= 80) {
        bar.style.backgroundColor = '#10b981'; 
        text.textContent = 'Kuat';
        text.style.color = '#10b981';
    } else if (strength >= 60) {
        bar.style.backgroundColor = '#f59e0b'; 
        text.textContent = 'Cukup';
        text.style.color = '#f59e0b';
    } else if (strength >= 40) {
        bar.style.backgroundColor = '#f97316';
        text.textContent = 'Lemah';
        text.style.color = '#f97316';
    } else {
        bar.style.backgroundColor = '#ef4444';
        text.textContent = 'Sangat Lemah';
        text.style.color = '#ef4444';
    }
}

function copyToClipboard(text, elementId = null) {
    navigator.clipboard.writeText(text).then(() => {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true
        });
        Toast.fire({
            icon: 'success',
            title: 'Password disalin!'
        });
        
        if (elementId) {
            const element = document.getElementById(elementId);
            if (element) {
                element.classList.add('copy-success');
                setTimeout(() => {
                    element.classList.remove('copy-success');
                }, 500);
            }
        }
    }).catch(err => {
        Swal.fire('Error', 'Gagal menyalin password', 'error');
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const manualRadio = document.getElementById('manualMode');
    const generateRadio = document.getElementById('generateMode');
    
    if (manualRadio && manualRadio.checked) {
        togglePasswordMode('manual');
    } else if (generateRadio && generateRadio.checked) {
        togglePasswordMode('generate');
        @if(old('password_mode') == 'generate' && old('generated_password'))
            const display = document.getElementById('generatedPasswordText');
            const hiddenInput = document.getElementById('generatedPasswordHidden');
            if (display && hiddenInput) {
                display.textContent = '{{ old("generated_password") }}';
                hiddenInput.value = '{{ old("generated_password") }}';
                checkPasswordStrength('{{ old("generated_password") }}');
            }
        @else
            generateNewPassword();
        @endif
    }
    const manualPasswordInput = document.getElementById('manualPassword');
    if (manualPasswordInput) {
        manualPasswordInput.addEventListener('input', function() {
            checkPasswordStrength(this.value);
        });
    }
});

document.getElementById('adminForm').addEventListener('submit', function(e) {
    const passwordMode = document.querySelector('input[name="password_mode"]:checked');
    if (!passwordMode) {
        e.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Pilih Metode Password',
            text: 'Silakan pilih metode pembuatan password',
        });
        return false;
    }
    
    const mode = passwordMode.value;
    
    if (mode === 'manual') {
        const password = document.getElementById('manualPassword').value;
        if (!password || password.length < 8) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Password Invalid',
                text: 'Password minimal 8 karakter untuk mode manual',
            });
            return false;
        }
    } else if (mode === 'generate') {
        const generatedPassword = document.getElementById('generatedPasswordHidden').value;
        if (!generatedPassword || generatedPassword.length < 8) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Password Belum Digenerate',
                text: 'Klik tombol Generate Password terlebih dahulu',
            });
            return false;
        }
    }
    
    return true;
});
</script>

<style>
.password-display {
    background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
    border-left: 4px solid #dc2626;
    padding: 12px;
    border-radius: 8px;
}

.copy-success {
    animation: copyFlash 0.5s ease;
}

@keyframes copyFlash {
    0%, 100% { background-color: transparent; }
    50% { background-color: rgba(34, 197, 94, 0.2); }
}
</style>
@endsection
