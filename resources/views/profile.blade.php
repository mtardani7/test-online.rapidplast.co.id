@extends('layouts.navbar')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-50/50 py-8 md:py-12">
    <div class="container mx-auto px-4 max-w-6xl">
        <div class="relative mb-12 md:mb-16">
            <div class="absolute inset-0 bg-gradient-to-r from-red-500/5 to-red-600/5 rounded-3xl blur-3xl"></div>
            <div class="relative text-center">
                <div class="inline-flex items-center justify-center w-24 h-24 md:w-28 md:h-28 bg-gradient-to-br from-red-500 to-red-600 rounded-full shadow-2xl shadow-red-200 mb-6 ring-4 ring-white ring-offset-4 ring-offset-gray-50">
                    <svg class="w-12 h-12 md:w-14 md:h-14 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-3 tracking-tight">
                    Profil <span class="bg-gradient-to-r from-red-600 to-red-700 bg-clip-text text-transparent">Pribadi</span>
                </h1>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto leading-relaxed">
                    Lengkapi data diri dengan benar untuk keperluan administrasi dan verifikasi.
                </p>
            </div>
        </div>

        @if(session('success'))
        <div class="mb-8 mx-auto max-w-2xl animate-fade-in">
            <div id="successAlert" class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 rounded-xl p-5 shadow-lg backdrop-blur-sm bg-white/50">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <p class="text-sm font-medium text-gray-900">{{ session('success') }}</p>
                        <p class="text-sm text-gray-600 mt-1">Informasi berhasil disimpan</p>
                    </div>
                    <button type="button" onclick="this.parentElement.parentElement.remove()" class="ml-4 text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        @endif

        @if($errors->any())
        <div class="mb-8 mx-auto max-w-2xl animate-fade-in">
            <div id="errorAlert" class="bg-gradient-to-r from-red-50 to-pink-50 border-l-4 border-red-500 rounded-xl p-5 shadow-lg backdrop-blur-sm bg-white/50">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-gradient-to-r from-red-500 to-pink-500 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <h3 class="text-sm font-semibold text-gray-900 mb-2">
                            Terjadi kesalahan saat menyimpan data:
                        </h3>
                        <div class="space-y-1">
                            @foreach ($errors->all() as $error)
                            <div class="flex items-center text-sm text-red-700">
                                <svg class="w-4 h-4 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                {{ $error }}
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <button type="button" onclick="this.parentElement.parentElement.remove()" class="ml-4 text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        @endif

        <div class="bg-white rounded-3xl shadow-2xl border border-gray-100 overflow-hidden backdrop-blur-sm bg-white/95">
            <div class="px-8 py-6 border-b border-gray-200/60 bg-gradient-to-r from-red-50/50 to-white">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-r from-red-500 to-red-600 rounded-xl flex items-center justify-center shadow-lg shadow-red-200">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-2xl font-bold text-gray-900">Informasi Pribadi</h2>
                            <p class="text-gray-600">Lengkapi data diri untuk melanjutkan proses seleksi.</p>
                        </div>
                    </div>
                    <div class="hidden md:block">
                        <div class="px-4 py-2 bg-gray-100 rounded-full">
                            <span class="text-sm font-medium text-gray-700">Kolom bertanda <span class="text-red-500">*</span> wajib diisi</span>
                        </div>
                    </div>
                </div>
            </div>
            <form action="{{ route('profile.save') }}" method="POST" enctype="multipart/form-data" class="p-8">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                    <div class="space-y-8">
                        <div class="bg-gradient-to-br from-gray-50 to-white rounded-2xl p-6 border border-gray-200/50">
                            <div class="flex items-center mb-6 pb-4 border-b border-gray-200/50">
                                <div class="w-10 h-10 bg-gradient-to-r from-gray-100 to-gray-50 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900">Detail Pribadi</h3>
                            </div>
                            <div class="space-y-5">
                                <div class="form-group">
                                    <label class="block text-sm font-semibold text-gray-800 mb-2 flex items-center">
                                        <span>Nama Lengkap</span>
                                        <span class="text-red-500 ml-1">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                        <input type="text" 
                                               name="name"
                                               value="{{ old('name', $profile->name ?? '') }}" 
                                               placeholder="Masukkan nama lengkap"
                                               class="pl-10 w-full px-4 py-3.5 bg-white border {{ $errors->has('name') ? 'border-red-500 ring-2 ring-red-200' : 'border-gray-300' }} rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-300 hover:border-gray-400">
                                    </div>
                                    @error('name')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="block text-sm font-semibold text-gray-800 mb-2 flex items-center">
                                        <span>Nomor Induk Kependudukan (NIK)</span>
                                        <span class="text-red-500 ml-1">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>
                                        <input type="text" 
                                               name="no_id"
                                               value="{{ old('no_id', $profile->no_id ?? '') }}" 
                                               placeholder="Masukkan nomor NIK"
                                               class="pl-10 w-full px-4 py-3.5 bg-white border {{ $errors->has('no_id') ? 'border-red-500 ring-2 ring-red-200' : 'border-gray-300' }} rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-300 hover:border-gray-400">
                                    </div>
                                    @error('no_id')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="block text-sm font-semibold text-gray-800 mb-3">
                                        Upload Kartu Tanda Penduduk (KTP)<span class="text-red-500 ml-1">*</span>
                                    </label>
                                    <div class="relative group">
                                        <div class="flex flex-col md:flex-row items-start md:items-center space-y-4 md:space-y-0 md:space-x-6">
                                            <div class="relative w-full md:w-40 h-48 md:h-32 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-300 group-hover:border-red-300 transition-all duration-300 overflow-hidden flex items-center justify-center cursor-pointer">
                                                @if(!empty($profile->image))
                                                    <img id="ktpPreview" src="{{ asset($profile->image) }}" alt="Preview KTP" class="object-cover w-full h-full">
                                                @else
                                                    <div id="ktpPlaceholder" class="text-center p-4">
                                                        <svg class="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                        <p class="text-xs text-gray-500">Unggah Foto KTP</p>
                                                    </div>
                                                    <img id="ktpPreview" class="hidden object-cover w-full h-full" alt="Preview KTP">
                                                @endif
                                            </div>
                                            <div class="flex-1">
                                                <div class="mb-4">
                                                    <label for="ktpUpload" class="cursor-pointer inline-flex items-center px-5 py-3 bg-gradient-to-r from-red-50 to-red-100 hover:from-red-100 hover:to-red-200 text-red-700 font-medium rounded-xl transition-all duration-300 transform hover:-translate-y-0.5 hover:shadow-md">
                                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                                        </svg>
                                                        Pilih File
                                                    </label>
                                                    <input id="ktpUpload" type="file" name="image" accept="image/*" class="hidden" required/>
                                                </div>
                                                <div class="mb-2">
                                                    <p class="text-sm font-medium text-gray-700 mb-1">File terpilih:</p>
                                                    <p id="fileName" class="text-sm text-gray-600">Belum ada file</p>
                                                </div>
                                                <p class="text-xs text-gray-500 mb-2 flex items-center">
                                                    <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    Format yang diizinkan: JPEG, JPG, PNG (Maks: 8MB)
                                                </p>
                                                @error('image')
                                                    <p class="mt-1 text-sm text-red-600 flex items-center">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        {{ $message }}
                                                    </p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div class="form-group">
                                        <label class="block text-sm font-semibold text-gray-800 mb-2 flex items-center">
                                            <span>Jenis Kelamin</span>
                                            <span class="text-red-500 ml-1">*</span>
                                        </label>
                                        <div class="relative">
                                            <select name="gender"
                                                    class="w-full px-4 py-3.5 bg-white border {{ $errors->has('gender') ? 'border-red-500 ring-2 ring-red-200' : 'border-gray-300' }} rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-300 hover:border-gray-400 appearance-none pr-10">
                                                <option value="">Pilih Jenis Kelamin</option>
                                                <option value="male" {{ old('gender', $profile->gender ?? '') == 'male' ? 'selected' : '' }}>Laki-laki</option>
                                                <option value="female" {{ old('gender', $profile->gender ?? '') == 'female' ? 'selected' : '' }}>Perempuan</option>
                                            </select>
                                        </div>
                                        @error('gender')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="block text-sm font-semibold text-gray-800 mb-2 flex items-center">
                                            <span>Agama</span>
                                            <span class="text-red-500 ml-1">*</span>
                                        </label>
                                        <div class="relative">
                                            <select name="religion"
                                                    class="w-full px-4 py-3.5 bg-white border {{ $errors->has('religion') ? 'border-red-500 ring-2 ring-red-200' : 'border-gray-300' }} rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-300 hover:border-gray-400 appearance-none pr-10">
                                                <option value="">Pilih Agama</option>
                                                @foreach(['Islam','Protestan','Katolik','Hindu','Buddha','Konghucu'] as $rel)
                                                    <option value="{{ $rel }}" {{ old('religion', $profile->religion ?? '') == $rel ? 'selected' : '' }}>{{ $rel }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('religion')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div class="form-group">
                                        <label class="block text-sm font-semibold text-gray-800 mb-2 flex items-center">
                                            <span>Kewarganegaraan</span>
                                            <span class="text-red-500 ml-1">*</span>
                                        </label>
                                        <div class="relative">
                                            <select name="citizenship"
                                                    class="w-full px-4 py-3.5 bg-white border {{ $errors->has('citizenship') ? 'border-red-500 ring-2 ring-red-200' : 'border-gray-300' }} rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-300 hover:border-gray-400 appearance-none pr-10" required>
                                                <option value="">Pilih Kewarganegaraan</option>
                                                <option value="WNI" {{ old('citizenship', $profile->citizenship ?? '') == 'WNI' ? 'selected' : '' }}>WNI (Warga Negara Indonesia)</option>
                                                <option value="WNA" {{ old('citizenship', $profile->citizenship ?? '') == 'WNA' ? 'selected' : '' }}>WNA (Warga Negara Asing)</option>
                                            </select>
                                        </div>
                                        @error('citizenship')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="block text-sm font-semibold text-gray-800 mb-2 flex items-center">
                                            <span>Tanggal Lahir</span>
                                            <span class="text-red-500 ml-1">*</span>
                                        </label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                            <input type="date" 
                                                   name="birth_date"
                                                   value="{{ old('birth_date', $profile->birth_date ?? '') }}" 
                                                   class="pl-10 w-full px-4 py-3.5 bg-white border {{ $errors->has('birth_date') ? 'border-red-500 ring-2 ring-red-200' : 'border-gray-300' }} rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-300 hover:border-gray-400">
                                        </div>
                                        @error('birth_date')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gradient-to-br from-gray-50 to-white rounded-2xl p-6 border border-gray-200/50">
                            <div class="flex items-center mb-6 pb-4 border-b border-gray-200/50">
                                <div class="w-10 h-10 bg-gradient-to-r from-blue-100 to-blue-50 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 14l9-5-9-5-9 5 9 5z" />
                                        <path d="M12 14v6" />
                                        <path d="M17 13l5 5M7 13l-5 5" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900">Informasi Pendidikan</h3>
                            </div>
                            
                            <div class="space-y-5">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div class="form-group">
                                        <label class="block text-sm font-semibold text-gray-800 mb-2 flex items-center">
                                            <span>Tingkat Pendidikan</span>
                                            <span class="text-red-500 ml-1">*</span>
                                        </label>
                                        <div class="relative">
                                            <select name="level"
                                                    class="w-full px-4 py-3.5 bg-white border {{ $errors->has('level') ? 'border-red-500 ring-2 ring-red-200' : 'border-gray-300' }} rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-300 hover:border-gray-400 appearance-none pr-10">
                                                <option value="">Pilih Tingkat Pendidikan</option>
                                                @foreach(['SD','SMP','SMA','D1','D2','D3','D4','S1','S2','S3'] as $level)
                                                    <option value="{{ $level }}" {{ old('level', $profile->level ?? '') == $level ? 'selected' : '' }}>{{ $level }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('level')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label class="block text-sm font-semibold text-gray-800 mb-2 flex items-center">
                                            <span>Sekolah / Universitas</span>
                                            <span class="text-red-500 ml-1">*</span>
                                        </label>
                                        <div class="relative">
                                            <input type="text" 
                                                   name="school"
                                                   value="{{ old('school', $profile->school ?? '') }}" 
                                                   placeholder="Masukkan nama sekolah/universitas"
                                                   class="w-full px-4 py-3.5 bg-white border {{ $errors->has('school') ? 'border-red-500 ring-2 ring-red-200' : 'border-gray-300' }} rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-300 hover:border-gray-400">
                                        </div>
                                        @error('school')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="block text-sm font-semibold text-gray-800 mb-2">
                                        IPK / Nilai Akhir
                                    </label>
                                    <div class="relative">
                                        <input type="text" 
                                               name="gpa"
                                               value="{{ old('gpa', $profile->gpa ?? '') }}" 
                                               placeholder="Contoh: 3.75"
                                               class="w-full px-4 py-3.5 bg-white border {{ $errors->has('gpa') ? 'border-red-500 ring-2 ring-red-200' : 'border-gray-300' }} rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-300 hover:border-gray-400">
                                    </div>
                                    @error('gpa')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-8">
                        <div class="bg-gradient-to-br from-gray-50 to-white rounded-2xl p-6 border border-gray-200/50">
                            <div class="flex items-center mb-6 pb-4 border-b border-gray-200/50">
                                <div class="w-10 h-10 bg-gradient-to-r from-red-100 to-red-50 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900">Informasi Keluarga</h3>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <!-- Nama Ayah -->
                                <div class="form-group">
                                    <label class="block text-sm font-semibold text-gray-800 mb-2 flex items-center">
                                        <span>Nama Ayah</span>
                                        <span class="text-red-500 ml-1">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="text" 
                                               name="fathers_name"
                                               value="{{ old('fathers_name', $profile->fathers_name ?? '') }}" 
                                               placeholder="Masukkan nama ayah"
                                               class="w-full px-4 py-3.5 bg-white border {{ $errors->has('fathers_name') ? 'border-red-500 ring-2 ring-red-200' : 'border-gray-300' }} rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-300 hover:border-gray-400">
                                    </div>
                                    @error('fathers_name')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Nama Ibu -->
                                <div class="form-group">
                                    <label class="block text-sm font-semibold text-gray-800 mb-2 flex items-center">
                                        <span>Nama Ibu</span>
                                        <span class="text-red-500 ml-1">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="text" 
                                               name="mothers_name"
                                               value="{{ old('mothers_name', $profile->mothers_name ?? '') }}" 
                                               placeholder="Masukkan nama ibu"
                                               class="w-full px-4 py-3.5 bg-white border {{ $errors->has('mothers_name') ? 'border-red-500 ring-2 ring-red-200' : 'border-gray-300' }} rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-300 hover:border-gray-400">
                                    </div>
                                    @error('mothers_name')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="block text-sm font-semibold text-gray-800 mb-2">
                                        Pekerjaan Ayah
                                    </label>
                                    <div class="relative">
                                        <input type="text" 
                                               name="fathers_occupation"
                                               value="{{ old('fathers_occupation', $profile->fathers_occupation ?? '') }}" 
                                               placeholder="Masukkan pekerjaan ayah"
                                               class="w-full px-4 py-3.5 bg-white border {{ $errors->has('fathers_occupation') ? 'border-red-500 ring-2 ring-red-200' : 'border-gray-300' }} rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-300 hover:border-gray-400">
                                    </div>
                                    @error('fathers_occupation')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="block text-sm font-semibold text-gray-800 mb-2">
                                        Pekerjaan Ibu
                                    </label>
                                    <div class="relative">
                                        <input type="text" 
                                               name="mothers_occupation"
                                               value="{{ old('mothers_occupation', $profile->mothers_occupation ?? '') }}" 
                                               placeholder="Masukkan pekerjaan ibu"
                                               class="w-full px-4 py-3.5 bg-white border {{ $errors->has('mothers_occupation') ? 'border-red-500 ring-2 ring-red-200' : 'border-gray-300' }} rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-300 hover:border-gray-400">
                                    </div>
                                    @error('mothers_occupation')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="bg-gradient-to-br from-gray-50 to-white rounded-2xl p-6 border border-gray-200/50">
                            <div class="flex items-center mb-6 pb-4 border-b border-gray-200/50">
                                <div class="w-10 h-10 bg-gradient-to-r from-green-100 to-green-50 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900">Informasi Kontak & Alamat</h3>
                            </div>
                            
                            <div class="space-y-5">
                                <div class="form-group">
                                    <label class="block text-sm font-semibold text-gray-800 mb-2 flex items-center">
                                        <span>Nomor Telepon/HP</span>
                                        <span class="text-red-500 ml-1">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="text" 
                                               name="phone_number"
                                               value="{{ old('phone_number', $profile->phone_number ?? '') }}" 
                                               placeholder="Masukkan nomor telepon/HP"
                                               class="w-full px-4 py-3.5 bg-white border {{ $errors->has('phone_number') ? 'border-red-500 ring-2 ring-red-200' : 'border-gray-300' }} rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-300 hover:border-gray-400">
                                    </div>
                                    @error('phone_number')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div class="form-group">
                                        <label class="block text-sm font-semibold text-gray-800 mb-2 flex items-center">
                                            <span>Provinsi</span>
                                            <span class="text-red-500 ml-1">*</span>
                                        </label>
                                        <div class="relative">
                                            <select id="provinceSelect" name="province"
                                                    class="w-full px-4 py-3.5 bg-white border {{ $errors->has('province') ? 'border-red-500 ring-2 ring-red-200' : 'border-gray-300' }} rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-300 hover:border-gray-400 appearance-none pr-10">
                                                <option value="">Memuat provinsi...</option>
                                            </select>
                                        </div>
                                        @error('province') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="block text-sm font-semibold text-gray-800 mb-2 flex items-center">
                                            <span>Kota/Kabupaten</span>
                                            <span class="text-red-500 ml-1">*</span>
                                        </label>
                                        <div class="relative">
                                            <select id="regencySelect" name="city"
                                                    class="w-full px-4 py-3.5 bg-white border {{ $errors->has('city') ? 'border-red-500 ring-2 ring-red-200' : 'border-gray-300' }} rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-300 hover:border-gray-400 appearance-none pr-10">
                                                <option value="">Pilih provinsi terlebih dahulu</option>
                                            </select>
                                        </div>
                                        @error('city') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="block text-sm font-semibold text-gray-800 mb-2 flex items-center">
                                            <span>Kecamatan</span>
                                            <span class="text-red-500 ml-1">*</span>
                                        </label>
                                        <div class="relative">
                                            <select id="districtSelect" name="regency"
                                                    class="w-full px-4 py-3.5 bg-white border {{ $errors->has('regency') ? 'border-red-500 ring-2 ring-red-200' : 'border-gray-300' }} rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-300 hover:border-gray-400 appearance-none pr-10">
                                                <option value="">Pilih kota/kabupaten terlebih dahulu</option>
                                            </select>
                                        </div>
                                        @error('regency') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="block text-sm font-semibold text-gray-800 mb-2 flex items-center">
                                            <span>Kelurahan/Desa</span>
                                            <span class="text-red-500 ml-1">*</span>
                                        </label>
                                        <div class="relative">
                                            <select id="villageSelect" name="district"
                                                    class="w-full px-4 py-3.5 bg-white border {{ $errors->has('district') ? 'border-red-500 ring-2 ring-red-200' : 'border-gray-300' }} rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-300 hover:border-gray-400 appearance-none pr-10">
                                                <option value="">Pilih kecamatan terlebih dahulu</option>
                                            </select>
                                        </div>
                                        @error('district') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="block text-sm font-semibold text-gray-800 mb-2 flex items-center">
                                        <span>Alamat Lengkap</span>
                                        <span class="text-red-500 ml-1">*</span>
                                    </label>
                                    <div class="relative">
                                        <textarea name="address" 
                                                  rows="3"
                                                  placeholder="Masukkan alamat lengkap (jalan, nomor rumah, RT/RW, dll)"
                                                  class="w-full px-4 py-3.5 bg-white border {{ $errors->has('address') ? 'border-red-500 ring-2 ring-red-200' : 'border-gray-300' }} rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-300 hover:border-gray-400 resize-none">{{ old('address', $profile->address ?? '') }}</textarea>
                                    </div>
                                    @error('address')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-12 pt-8 border-t border-gray-200/60">
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-6">
                        <div class="text-sm text-gray-500 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.282 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                            Kolom bertanda <span class="text-red-500 mx-1">*</span> wajib diisi
                        </div>
                        <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto">
                            <button type="reset" 
                                    class="px-8 py-3.5 border-2 border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 transition-all duration-300 transform hover:-translate-y-0.5">
                                <div class="flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Reset Semua
                                </div>
                            </button>
                            <button type="submit" 
                                    class="px-10 py-3.5 bg-gradient-to-r from-red-600 via-red-500 to-red-600 text-white font-bold rounded-xl hover:from-red-700 hover:via-red-600 hover:to-red-700 focus:outline-none focus:ring-4 focus:ring-red-500/30 focus:ring-offset-2 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5 hover:shadow-red-200">
                                <div class="flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Simpan & Lanjutkan
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .animate-fade-in {
        animation: fade-in 0.5s ease-out;
    }
    
    select {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%239ca3af' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 1rem center;
        background-repeat: no-repeat;
        background-size: 1.5em 1.5em;
        padding-right: 2.5rem;
    }
    
    select:focus {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%23ef4444' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
    }
    
    .form-group {
        position: relative;
    }
    
    input:focus, select:focus, textarea:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
    }

    select {
        scrollbar-width: thin;
        scrollbar-color: #e5e7eb #f9fafb;
    }
    
    select::-webkit-scrollbar {
        width: 8px;
    }
    
    select::-webkit-scrollbar-track {
        background: #f9fafb;
        border-radius: 4px;
    }
    
    select::-webkit-scrollbar-thumb {
        background-color: #e5e7eb;
        border-radius: 4px;
    }

    input[type="date"]::-webkit-calendar-picker-indicator {
        opacity: 0;
        cursor: pointer;
        position: absolute;
        right: 0;
        width: 100%;
        height: 100%;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .ktp-preview-enter {
        animation: fadeInUp 0.3s ease-out;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const successAlert = document.getElementById('successAlert');
    const errorAlert = document.getElementById('errorAlert');
    
    [successAlert, errorAlert].forEach(alert => {
        if (alert) {
            setTimeout(() => {
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-10px)';
                alert.style.transition = 'all 0.3s ease';
                setTimeout(() => alert.remove(), 300);
            }, alert === successAlert ? 5000 : 10000);

            const closeBtn = alert.querySelector('button');
            if (closeBtn) {
                closeBtn.addEventListener('click', () => {
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateY(-10px)';
                    alert.style.transition = 'all 0.3s ease';
                    setTimeout(() => alert.remove(), 300);
                });
            }
        }
    });
    
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('reset', function(e) {
            setTimeout(() => {
                document.querySelectorAll('.border-red-500, .ring-2, .ring-red-200').forEach(el => {
                    el.classList.remove('border-red-500', 'ring-2', 'ring-red-200');
                    el.classList.add('border-gray-300');
                });

                document.querySelectorAll('.text-red-600').forEach(el => {
                    if (el.tagName === 'P') el.remove();
                });
                
                ['provinceSelect', 'regencySelect', 'districtSelect', 'villageSelect'].forEach(id => {
                    const select = document.getElementById(id);
                    if (select) {
                        select.innerHTML = `<option value="">${id === 'provinceSelect' ? 'Memuat provinsi...' : `Pilih ${id.includes('province') ? 'provinsi' : id.includes('regency') ? 'kota/kabupaten' : id.includes('district') ? 'kecamatan' : 'kelurahan/desa'} terlebih dahulu`}</option>`;
                        select.value = '';
                    }
                });
                
                const ktpPreview = document.getElementById('ktpPreview');
                const ktpPlaceholder = document.getElementById('ktpPlaceholder');
                const fileName = document.getElementById('fileName');
                
                if (ktpPreview) {
                    ktpPreview.src = '';
                    ktpPreview.classList.add('hidden');
                }
                if (ktpPlaceholder) {
                    ktpPlaceholder.classList.remove('hidden');
                }
                if (fileName) {
                    fileName.textContent = 'Belum ada file';
                }

                const fileInput = document.getElementById('ktpUpload');
                if (fileInput) fileInput.value = '';
                
            }, 10);
        });
    }

    const phoneInput = document.querySelector('input[name="phone_number"]');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');

            if (value.startsWith('62')) {
                value = value.substring(0, 14);
            } else if (value.startsWith('0')) {
                value = value.substring(0, 13);
            } else {
                value = value.substring(0, 12);
            }
            
            e.target.value = value;

            const isValid = value.length >= 10;
            e.target.classList.toggle('border-green-500', isValid);
            e.target.classList.toggle('ring-2', isValid);
            e.target.classList.toggle('ring-green-200', isValid);
        });
    }

    const gpaInput = document.querySelector('input[name="gpa"]');
    if (gpaInput) {
        gpaInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/[^0-9.]/g, '');

            const parts = value.split('.');
            if (parts.length > 2) {
                value = parts[0] + '.' + parts.slice(1).join('');
            }

            if (value.length > 5) {
                value = value.substring(0, 5);
            }
            
            e.target.value = value;
        });
    }

    const birthDateInput = document.querySelector('input[name="birth_date"]');
    if (birthDateInput) {
        const today = new Date().toISOString().split('T')[0];
        birthDateInput.max = today;
        
        if (!birthDateInput.value) {
            birthDateInput.placeholder = 'Pilih tanggal lahir';
        }
    }

    const ktpUpload = document.getElementById('ktpUpload');
    const ktpPreview = document.getElementById('ktpPreview');
    const ktpPlaceholder = document.getElementById('ktpPlaceholder');
    const fileName = document.getElementById('fileName');
    const previewContainer = ktpPreview?.parentElement;
    
    if (ktpUpload && ktpPreview) {
        if (!ktpPreview.src && ktpPreview.classList.contains('hidden')) {
            ktpPreview.classList.add('hidden');
        }
        
        ktpUpload.addEventListener('change', function(e) {
            const file = e.target.files && e.target.files[0];
            if (!file) return;
            
            if (!file.type.startsWith('image/')) {
                alert('Silakan pilih file gambar (JPEG, JPG, PNG)');
                this.value = '';
                return;
            }
            
            if (file.size > 8 * 1024 * 1024) {
                alert('Ukuran file harus kurang dari 8MB');
                this.value = '';
                return;
            }
            
            if (fileName) {
                fileName.textContent = file.name;
                fileName.classList.add('text-green-600', 'font-medium');
                
                setTimeout(() => {
                    fileName.classList.remove('text-green-600', 'font-medium');
                }, 2000);
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                if (ktpPlaceholder) {
                    ktpPlaceholder.classList.add('hidden');
                }

                ktpPreview.src = e.target.result;
                ktpPreview.classList.remove('hidden');
                ktpPreview.classList.add('object-cover', 'ktp-preview-enter');

                if (previewContainer) {
                    previewContainer.classList.remove('border-gray-300', 'border-dashed');
                    previewContainer.classList.add('border-green-500', 'border-2', 'border-solid');

                    setTimeout(() => {
                        previewContainer.classList.remove('border-green-500', 'border-2');
                        previewContainer.classList.add('border-gray-300');
                    }, 2000);
                }
                
                setTimeout(() => {
                    ktpPreview.classList.remove('ktp-preview-enter');
                }, 300);
            };
            reader.onerror = function() {
                alert('Error membaca file. Silakan coba lagi.');
                ktpUpload.value = '';
                if (fileName) {
                    fileName.textContent = 'Belum ada file';
                }
            };
            reader.readAsDataURL(file);
        });

        if (previewContainer) {
            previewContainer.addEventListener('click', function(e) {
                if (e.target !== ktpPreview) {
                    ktpUpload.click();
                }
            });
        }

        ktpPreview.addEventListener('click', function(e) {
            e.stopPropagation();
            ktpUpload.click();
        });
    }

    const regionSelects = {
        province: document.getElementById('provinceSelect'),
        regency: document.getElementById('regencySelect'),
        district: document.getElementById('districtSelect'),
        village: document.getElementById('villageSelect')
    };

    const selectedProvinceInitial = {!! json_encode(old('province', $profile->province ?? '')) !!};
    const selectedCityInitial = {!! json_encode(old('city', $profile->city ?? '')) !!};
    const selectedRegencyInitial = {!! json_encode(old('regency', $profile->regency ?? '')) !!};
    const selectedDistrictInitial = {!! json_encode(old('district', $profile->district ?? '')) !!};

    function loadProvinces() {
        const select = regionSelects.province;
        if (!select) return;

        select.innerHTML = '<option value="">Memuat provinsi...</option>';
        select.disabled = true;
        
        fetch('/provinces')
            .then(res => {
                if (!res.ok) throw new Error('Gagal memuat provinsi');
                return res.json();
            })
            .then(data => {
                const items = Array.isArray(data) ? data : 
                    (data && Array.isArray(data.data) ? data.data : Object.values(data || {}));
                
                const options = ['<option value="">Pilih Provinsi</option>'];
                items.forEach(province => {
                    const code = province.code ?? province.id ?? '';
                    const name = province.name ?? province.province ?? province.provinsi ?? '';
                    const isSelected = String(name) === String(selectedProvinceInitial);
                    options.push(`<option value="${name}" data-code="${code}" ${isSelected ? 'selected' : ''}>${name}</option>`);
                });
                
                select.innerHTML = options.join('');
                select.disabled = false;

                if (selectedProvinceInitial) {
                    const selectedOption = select.querySelector(`option[value="${selectedProvinceInitial}"]`);
                    if (selectedOption) {
                        const code = selectedOption.dataset.code;
                        loadRegencies(code, selectedCityInitial);
                    }
                }
            })
            .catch(err => {
                select.innerHTML = '<option value="">Gagal memuat provinsi</option>';
                console.error('Error memuat provinsi:', err);
            });
    }

    function loadRegencies(provinceCode, preselectName = '') {
        const select = regionSelects.regency;
        if (!select || !provinceCode) return;
        
        select.innerHTML = '<option value="">Memuat kota/kabupaten...</option>';
        select.disabled = true;
        
        fetch(`/regencies/${encodeURIComponent(provinceCode)}`)
            .then(res => {
                if (!res.ok) throw new Error('Gagal memuat kota/kabupaten');
                return res.json();
            })
            .then(data => {
                const items = Array.isArray(data) ? data : 
                    (data && Array.isArray(data.data) ? data.data : Object.values(data || {}));
                
                const options = ['<option value="">Pilih Kota/Kabupaten</option>'];
                items.forEach(regency => {
                    const code = regency.code ?? regency.id ?? '';
                    const name = regency.name ?? regency.regency ?? '';
                    const isSelected = String(name) === String(preselectName);
                    options.push(`<option value="${name}" data-code="${code}" ${isSelected ? 'selected' : ''}>${name}</option>`);
                });
                
                select.innerHTML = options.join('');
                select.disabled = false;
 
                if (preselectName) {
                    const selectedOption = select.querySelector(`option[value="${preselectName}"]`);
                    if (selectedOption) {
                        const code = selectedOption.dataset.code;
                        loadDistricts(code, selectedRegencyInitial);
                    }
                }
            })
            .catch(err => {
                select.innerHTML = '<option value="">Gagal memuat kota/kabupaten</option>';
                console.error('Error memuat kota/kabupaten:', err);
            });
    }

    function loadDistricts(regencyCode, preselectName = '') {
        const select = regionSelects.district;
        if (!select || !regencyCode) return;
        
        select.innerHTML = '<option value="">Memuat kecamatan...</option>';
        select.disabled = true;
        
        fetch(`/districts/${encodeURIComponent(regencyCode)}`)
            .then(res => {
                if (!res.ok) throw new Error('Gagal memuat kecamatan');
                return res.json();
            })
            .then(data => {
                const items = Array.isArray(data) ? data : 
                    (data && Array.isArray(data.data) ? data.data : Object.values(data || {}));
                
                const options = ['<option value="">Pilih Kecamatan</option>'];
                items.forEach(district => {
                    const code = district.code ?? district.id ?? '';
                    const name = district.name ?? district.district ?? '';
                    const isSelected = String(name) === String(preselectName);
                    options.push(`<option value="${name}" data-code="${code}" ${isSelected ? 'selected' : ''}>${name}</option>`);
                });
                
                select.innerHTML = options.join('');
                select.disabled = false;

                if (preselectName) {
                    const selectedOption = select.querySelector(`option[value="${preselectName}"]`);
                    if (selectedOption) {
                        const code = selectedOption.dataset.code;
                        loadVillages(code, selectedDistrictInitial);
                    }
                }
            })
            .catch(err => {
                select.innerHTML = '<option value="">Gagal memuat kecamatan</option>';
                console.error('Error memuat kecamatan:', err);
            });
    }

    function loadVillages(districtCode, preselectName = '') {
        const select = regionSelects.village;
        if (!select || !districtCode) return;
        
        select.innerHTML = '<option value="">Memuat kelurahan/desa...</option>';
        select.disabled = true;
        
        fetch(`/villages/${encodeURIComponent(districtCode)}`)
            .then(res => {
                if (!res.ok) throw new Error('Gagal memuat kelurahan/desa');
                return res.json();
            })
            .then(data => {
                const items = Array.isArray(data) ? data : 
                    (data && Array.isArray(data.data) ? data.data : Object.values(data || {}));
                
                const options = ['<option value="">Pilih Kelurahan/Desa</option>'];
                items.forEach(village => {
                    const code = village.code ?? village.id ?? '';
                    const name = village.name ?? village.village ?? '';
                    const isSelected = String(name) === String(preselectName);
                    options.push(`<option value="${name}" data-code="${code}" ${isSelected ? 'selected' : ''}>${name}</option>`);
                });
                
                select.innerHTML = options.join('');
                select.disabled = false;
            })
            .catch(err => {
                select.innerHTML = '<option value="">Gagal memuat kelurahan/desa</option>';
                console.error('Error memuat kelurahan/desa:', err);
            });
    }

    if (regionSelects.province) {
        regionSelects.province.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const code = selectedOption?.dataset?.code;

            if (regionSelects.regency) {
                regionSelects.regency.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
                regionSelects.regency.disabled = true;
            }
            if (regionSelects.district) {
                regionSelects.district.innerHTML = '<option value="">Pilih Kecamatan</option>';
                regionSelects.district.disabled = true;
            }
            if (regionSelects.village) {
                regionSelects.village.innerHTML = '<option value="">Pilih Kelurahan/Desa</option>';
                regionSelects.village.disabled = true;
            }
            
            if (code) {
                loadRegencies(code);
            }
        });
    }
    
    if (regionSelects.regency) {
        regionSelects.regency.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const code = selectedOption?.dataset?.code;
            if (regionSelects.district) {
                regionSelects.district.innerHTML = '<option value="">Pilih Kecamatan</option>';
                regionSelects.district.disabled = true;
            }
            if (regionSelects.village) {
                regionSelects.village.innerHTML = '<option value="">Pilih Kelurahan/Desa</option>';
                regionSelects.village.disabled = true;
            }
            
            if (code) {
                loadDistricts(code);
            }
        });
    }
    
    if (regionSelects.district) {
        regionSelects.district.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const code = selectedOption?.dataset?.code;
            if (regionSelects.village) {
                regionSelects.village.innerHTML = '<option value="">Pilih Kelurahan/Desa</option>';
                regionSelects.village.disabled = true;
            }
            
            if (code) {
                loadVillages(code);
            }
        });
    }

    setTimeout(loadProvinces, 100);

    document.querySelectorAll('input[required], select[required], textarea[required]').forEach(input => {
        input.addEventListener('blur', function() {
            if (!this.value) {
                this.classList.add('border-red-500', 'ring-2', 'ring-red-200');
            } else {
                this.classList.remove('border-red-500', 'ring-2', 'ring-red-200');
                this.classList.add('border-green-500', 'ring-2', 'ring-green-200');

                setTimeout(() => {
                    this.classList.remove('border-green-500', 'ring-2', 'ring-green-200');
                }, 2000);
            }
        });
    });
});
</script>
@endsection