@extends('layouts.sidebar')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8">
        <a href="{{ route('admin-users.index') }}" 
           class="inline-flex items-center text-red-600 hover:text-red-700 mb-4">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
        </a>
        <h1 class="text-3xl font-bold text-gray-900">
            <i class="fas fa-user-edit text-red-600 mr-3"></i>Edit Admin
        </h1>
        <p class="text-gray-600 mt-2">Perbarui data administrator {{ $admin_user->name }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-red-600 to-red-700">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-bold text-white">
                    <i class="fas fa-user-cog mr-2"></i>Form Edit Administrator
                </h2>
                <span class="bg-white/20 px-3 py-1 rounded-full text-sm font-medium text-white">
                    ID: {{ $admin_user->id }}
                </span>
            </div>
        </div>
        
        <div class="p-6">
            <form action="{{ route('admin-users.update', $admin_user->id) }}" method="POST" id="editForm">
                @csrf
                @method('PUT')
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
                                   value="{{ old('name', $admin_user->name) }}"
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
                                   value="{{ old('email', $admin_user->email) }}"
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
                    <div class="flex items-center justify-between mb-4 border-b pb-2">
                        <h3 class="text-lg font-semibold text-gray-900">
                            <i class="fas fa-key text-red-600 mr-2"></i>Perbarui Password (Opsional)
                        </h3>
                        <span class="text-sm text-yellow-600 bg-yellow-50 px-3 py-1 rounded-lg">
                            <i class="fas fa-exclamation-triangle mr-1"></i> Kosongkan jika tidak ingin mengubah
                        </span>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div class="relative">
                            <input type="radio" 
                                   name="password_mode" 
                                   id="manualMode" 
                                   value="manual" 
                                   class="hidden peer"
                                   onchange="togglePasswordMode('manual')">
                            <label for="manualMode" 
                                   class="block p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-red-300 peer-checked:border-red-500 peer-checked:bg-red-50 transition-all">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 w-10 h-10 bg-red-100 rounded-full flex items-center justify-center mr-4">
                                        <i class="fas fa-key text-red-600"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900">Password Manual</h4>
                                        <p class="text-sm text-gray-600">Masukkan password baru</p>
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
                                   onchange="togglePasswordMode('generate')">
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
                        <div class="relative md:col-span-2">
                            <input type="radio" 
                                   name="password_mode" 
                                   id="noChangeMode" 
                                   value="no_change" 
                                   class="hidden peer"
                                   onchange="togglePasswordMode('no_change')"
                                   checked>
                            <label for="noChangeMode" 
                                   class="block p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-red-300 peer-checked:border-red-500 peer-checked:bg-red-50 transition-all">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center mr-4">
                                        <i class="fas fa-ban text-gray-600"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900">Tidak Ubah Password</h4>
                                        <p class="text-sm text-gray-600">Biarkan password tetap seperti sekarang</p>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                    
                    <div id="manualPasswordField" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Password Baru <span class="text-red-600">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" 
                                   name="password" 
                                   id="manualPassword"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition pr-12"
                                   placeholder="Minimal 8 karakter"
                                   autocomplete="new-password">
                            <button type="button" 
                                    onclick="togglePasswordVisibility('manualPassword', 'manualPasswordIcon')"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                <i id="manualPasswordIcon" class="fas fa-eye"></i>
                            </button>
                        </div>
                        <p class="mt-1 text-sm text-gray-500">Minimal 8 karakter</p>
                        @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div id="generatePasswordField" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Password yang Digenerate <span class="text-red-600">*</span>
                        </label>
                        <div id="generatedPasswordDisplay" class="mb-4">
                            <div class="password-display px-4 py-3 rounded-lg mb-3">
                                <div class="flex items-center justify-between">
                                    <code id="generatedPasswordText" class="text-lg font-mono text-gray-800"></code>
                                    <div class="flex items-center space-x-2">
                                        <button type="button" 
                                                onclick="toggleGeneratedPasswordVisibility()"
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
                        
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mt-4">
                            <div class="flex items-start">
                                <i class="fas fa-info-circle text-blue-600 mt-1 mr-3"></i>
                                <div>
                                    <p class="text-sm text-blue-800">
                                        <strong>Catatan:</strong> Password baru akan langsung berlaku. 
                                        Pastikan untuk menyimpan password yang digenerate.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-between items-center pt-6 border-t">
                    <div>
                        <button type="button" 
                                onclick="showDeleteModal()"
                                class="inline-flex items-center px-4 py-2 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 transition">
                            <i class="fas fa-trash-alt mr-2"></i> Hapus Admin
                        </button>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('admin-users.index') }}" 
                           class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                            <i class="fas fa-times mr-2"></i> Batal
                        </a>
                        <button type="submit" 
                                class="px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white font-semibold rounded-lg shadow-md hover:from-red-700 hover:to-red-800 transition">
                            <i class="fas fa-save mr-2"></i> Perbarui Data
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full fade-in">
        <div class="modal-header-gradient rounded-t-xl px-6 py-4">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold text-white">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Konfirmasi Hapus Admin
                </h3>
                <button onclick="closeDeleteModal()" class="text-white hover:text-gray-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        
        <div class="p-6">
            <div class="mb-4">
                <div class="flex items-center space-x-4 mb-4">
                    <div class="flex-shrink-0 w-16 h-16 bg-gradient-to-r from-red-600 to-red-700 rounded-full flex items-center justify-center text-white font-bold text-2xl">
                        {{ strtoupper(substr($admin_user->name, 0, 1)) }}
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 text-lg">{{ $admin_user->name }}</h4>
                        <p class="text-gray-600">{{ $admin_user->email }}</p>
                        <p class="text-sm text-gray-500">Bergabung: {{ $admin_user->created_at->format('d M Y') }}</p>
                    </div>
                </div>
                
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-circle text-red-600 mt-1 mr-3"></i>
                        <div>
                            <p class="text-sm font-medium text-red-800 mb-1">Peringatan!</p>
                            <p class="text-sm text-red-700">
                                Tindakan ini akan menghapus semua data terkait admin ini secara permanen.
                                Admin tidak akan bisa mengakses sistem lagi.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-end space-x-3">
                <button onclick="closeDeleteModal()" 
                        class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                    <i class="fas fa-times mr-1"></i> Batal
                </button>
                <form action="{{ route('admin-users.destroy', $admin_user->id) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        <i class="fas fa-trash-alt mr-1"></i> Ya, Hapus
                    </button>
                </form>
            </div>
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
    }
}

function togglePasswordMode(mode) {
    const manualField = document.getElementById('manualPasswordField');
    const generateField = document.getElementById('generatePasswordField');
    const manualPassword = document.getElementById('manualPassword');
    const generatedPassword = document.getElementById('generatedPasswordHidden');
    
    if (mode === 'manual') {
        manualField.classList.remove('hidden');
        generateField.classList.add('hidden');
        if (manualPassword) {
            manualPassword.required = true;
        }
        if (generatedPassword) {
            generatedPassword.required = false;
            generatedPassword.value = '';
        }
    } else if (mode === 'generate') {
        manualField.classList.add('hidden');
        generateField.classList.remove('hidden');
        if (manualPassword) {
            manualPassword.required = false;
            manualPassword.value = '';
        }
        if (generatedPassword) {
            generatedPassword.required = true;
        }
        if (!document.getElementById('generatedPasswordText').textContent) {
            generateNewPassword();
        }
    } else {
        manualField.classList.add('hidden');
        generateField.classList.add('hidden');
        if (manualPassword) {
            manualPassword.required = false;
            manualPassword.value = '';
        }
        if (generatedPassword) {
            generatedPassword.required = false;
            generatedPassword.value = '';
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

function toggleGeneratedPasswordVisibility() {
    const display = document.getElementById('generatedPasswordText');
    const icon = document.getElementById('generatedPasswordIcon');
    
    if (display && icon) {
        if (display.textContent.includes('*')) {
            const hiddenInput = document.getElementById('generatedPasswordHidden');
            display.textContent = hiddenInput.value;
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            display.textContent = '*'.repeat(12);
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
}

function showDeleteModal() {
    const modal = document.getElementById('deleteModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    modal.classList.remove('flex');
    modal.classList.add('hidden');
}

document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});

function copyToClipboard(text, elementId = null) {
    navigator.clipboard.writeText(text).then(() => {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Password disalin ke clipboard',
            timer: 2000,
            showConfirmButton: false,
            position: 'top-end'
        });
    }).catch(err => {
        Swal.fire('Error', 'Gagal menyalin password', 'error');
    });
}

document.getElementById('editForm').addEventListener('submit', function(e) {
    const passwordMode = document.querySelector('input[name="password_mode"]:checked');
    
    if (!passwordMode) {
        e.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Pilih Opsi Password',
            text: 'Silakan pilih opsi untuk password',
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

document.addEventListener('DOMContentLoaded', function() {
    const noChangeRadio = document.getElementById('noChangeMode');
    if (noChangeRadio) {
        noChangeRadio.checked = true;
        togglePasswordMode('no_change');
    }
    
    const display = document.getElementById('generatedPasswordText');
    if (display) {
        display.textContent = '*'.repeat(12);
    }
});
</script>

<style>
.modal-header-gradient {
    background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
}

.password-display {
    background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
    border-left: 4px solid #dc2626;
    padding: 12px;
    border-radius: 8px;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.fade-in {
    animation: fadeIn 0.3s ease-in-out;
}
</style>
@endsection