@extends('layouts.sidebar')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
@php
    $candidateFilterKeys = ['gender', 'min_age', 'max_age', 'min_gpa', 'completion_status', 'religion', 'level'];
    $hasCandidateFilters = request()->hasAny($candidateFilterKeys);
@endphp
<div class="mx-auto max-w-7xl space-y-6 px-3 py-5 sm:px-6 sm:py-7 lg:px-8">
    <div class="mb-8">
        <div class="mb-6 flex flex-col gap-5 xl:flex-row xl:items-end xl:justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">Daftar Kandidat</h1>
                <p class="mt-2 max-w-xl text-sm leading-relaxed text-gray-600 sm:text-base">Cari kandidat, gunakan filter bila diperlukan, lalu buka detail profil dari daftar.</p>
            </div>
            <div class="grid grid-cols-2 xl:grid-cols-4 gap-3 sm:gap-4 w-full lg:w-auto">
                <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                    <p class="text-gray-500 text-sm">Total Kandidat</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalUser }}</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                    <p class="text-gray-500 text-sm">Laki-laki di halaman ini</p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ $users->filter(function($user) {
                            return optional($user->detail)->gender === 'male';
                        })->count() }}
                    </p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                    <p class="text-gray-500 text-sm">Perempuan di halaman ini</p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ $users->filter(function($user) {
                            return optional($user->detail)->gender === 'female';
                        })->count() }}
                    </p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                    <p class="text-gray-500 text-sm">Lengkap di halaman ini</p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ $users->filter(function($user) {
                            return $user->detail && $user->detail->no_id;
                        })->count() }}
                    </p>
                </div>
            </div>
        </div>
        <form method="GET" action="{{ route('dashboard.users') }}" id="filterForm">
            <input type="hidden" name="per_page" id="filterFormPerPage" value="{{ request('per_page', 10) }}">
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
                <div class="flex flex-col gap-3 lg:flex-row lg:items-end">
                    <div class="min-w-0 flex-1">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input 
                                type="search"
                                name="search"
                                value="{{ request('search') }}"
                                class="min-h-12 w-full rounded-xl border border-slate-200 py-3 pl-10 pr-4 text-sm focus:border-red-500 focus:outline-none focus:ring-4 focus:ring-red-100 transition-all"
                                placeholder="Cari kandidat berdasarkan nama, email, atau sekolah..."
                            >
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2 sm:flex sm:flex-wrap sm:items-center">
                        <button type="button" id="filterBtn" aria-expanded="{{ $hasCandidateFilters ? 'true' : 'false' }}" aria-controls="filterOptions" class="min-h-11 justify-center px-4 py-3 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-all flex items-center">
                            <i class="fas fa-filter mr-2" aria-hidden="true"></i> Filter detail
                            @if($hasCandidateFilters)<span class="ml-2 rounded-full bg-rose-100 px-2 py-0.5 text-xs font-semibold text-rose-800">Aktif</span>@endif
                        </button>
                        <button type="submit" class="min-h-11 justify-center px-4 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl hover:from-red-700 hover:to-red-800 transition-all flex items-center">
                            <i class="fas fa-search mr-2"></i> Cari
                        </button>
                        <a href="{{ route('dashboard.users') }}" class="col-span-2 min-h-11 justify-center px-4 py-3 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-all flex items-center sm:col-span-1">
                            <i class="fas fa-redo mr-2"></i> Reset
                        </a>
                    </div>
                </div>
                <div id="filterOptions" class="mt-4 border-t border-slate-200 pt-4 {{ $hasCandidateFilters ? '' : 'hidden' }}">
                    <div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div>
                                <label for="candidateGender" class="block text-gray-700 text-sm font-medium mb-2">Jenis kelamin</label>
                                <select id="candidateGender" name="gender" class="min-h-11 w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500">
                                    <option value="">Semua</option>
                                    <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-medium mb-2">Rentang umur</label>
                                <div class="flex items-center space-x-2">
                                    <input type="number" name="min_age" value="{{ request('min_age') }}" 
                                           aria-label="Umur minimum" placeholder="Min" class="min-h-11 w-full px-3 py-2 border border-gray-300 rounded-xl" min="0">
                                    <span class="text-gray-500">-</span>
                                    <input type="number" name="max_age" value="{{ request('max_age') }}" 
                                           aria-label="Umur maksimum" placeholder="Maks." class="min-h-11 w-full px-3 py-2 border border-gray-300 rounded-xl" min="0">
                                </div>
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-medium mb-2">IPK minimum</label>
                                <input type="number" name="min_gpa" value="{{ request('min_gpa') }}" 
                                       step="0.01" min="0" max="4" class="min-h-11 w-full px-4 py-2 border border-gray-300 rounded-xl"
                                       placeholder="0.00">
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-medium mb-2">Status Kelengkapan</label>
                                <select name="completion_status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500">
                                    <option value="">Semua</option>
                                    <option value="complete" {{ request('completion_status') == 'complete' ? 'selected' : '' }}>Sudah Lengkap</option>
                                    <option value="incomplete" {{ request('completion_status') == 'incomplete' ? 'selected' : '' }}>Belum Lengkap</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-medium mb-2">Agama</label>
                                <select name="religion" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500">
                                    <option value="">Semua</option>
                                    <option value="Islam" {{ request('religion') == 'Islam' ? 'selected' : '' }}>Islam</option>
                                    <option value="Kristen" {{ request('religion') == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                                    <option value="Katolik" {{ request('religion') == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                                    <option value="Hindu" {{ request('religion') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                    <option value="Buddha" {{ request('religion') == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                                    <option value="Konghucu" {{ request('religion') == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-medium mb-2">Level Pendidikan</label>
                                <select name="level" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500">
                                    <option value="">Semua</option>
                                    <option value="SMA" {{ request('level') == 'SMA' ? 'selected' : '' }}>SMA</option>
                                    <option value="SMK" {{ request('level') == 'SMK' ? 'selected' : '' }}>SMK</option>
                                    <option value="MA" {{ request('level') == 'MA' ? 'selected' : '' }}>MA</option>
                                    <option value="S1" {{ request('level') == 'S1' ? 'selected' : '' }}>S1</option>
                                    <option value="S2" {{ request('level') == 'S2' ? 'selected' : '' }}>S2</option>
                                    <option value="S3" {{ request('level') == 'S3' ? 'selected' : '' }}>S3</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-medium mb-2">Urutkan Berdasarkan</label>
                                <select name="sort_by" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500">
                                    <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Tanggal Bergabung</option>
                                    <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Nama</option>
                                    <option value="email" {{ request('sort_by') == 'email' ? 'selected' : '' }}>Email</option>
                                    <option value="school" {{ request('sort_by') == 'school' ? 'selected' : '' }}>Sekolah</option>
                                    <option value="age" {{ request('sort_by') == 'age' ? 'selected' : '' }}>Umur</option>
                                    <option value="gpa" {{ request('sort_by') == 'gpa' ? 'selected' : '' }}>IPK</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-medium mb-2">Urutan</label>
                                <select name="sort_order" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500">
                                    <option value="desc" {{ request('sort_order', 'desc') == 'desc' ? 'selected' : '' }}>Menurun</option>
                                    <option value="asc" {{ request('sort_order', 'desc') == 'asc' ? 'selected' : '' }}>Menaik</option>
                                </select>
                            </div>
                        </div>
                        <div class="flex justify-end mt-4 space-x-3">
                            <button type="button" onclick="resetFilters()" class="px-4 py-2 text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded-lg">
                                Reset Filter
                            </button>
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-all">
                                Terapkan Filter
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    @if(request()->hasAny(['search', 'gender', 'min_age', 'max_age', 'min_gpa', 'completion_status', 'religion', 'level']))
    <div class="mb-4">
        <div class="flex flex-wrap gap-2">
            @if(request('search'))
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-blue-100 text-blue-800">
                Pencarian: {{ request('search') }}
                <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="ml-2 text-blue-600 hover:text-blue-800">
                    <i class="fas fa-times"></i>
                </a>
            </span>
            @endif
            
            @if(request('gender'))
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-purple-100 text-purple-800">
                Gender: {{ request('gender') == 'male' ? 'Laki-laki' : 'Perempuan' }}
                <a href="{{ request()->fullUrlWithQuery(['gender' => null]) }}" class="ml-2 text-purple-600 hover:text-purple-800">
                    <i class="fas fa-times"></i>
                </a>
            </span>
            @endif
            
            @if(request('min_age') || request('max_age'))
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-green-100 text-green-800">
                Umur: 
                {{ request('min_age') ? request('min_age') . ' tahun' : '' }}
                {{ request('min_age') && request('max_age') ? ' - ' : '' }}
                {{ request('max_age') ? request('max_age') . ' tahun' : '' }}
                <a href="{{ request()->fullUrlWithQuery(['min_age' => null, 'max_age' => null]) }}" class="ml-2 text-green-600 hover:text-green-800">
                    <i class="fas fa-times"></i>
                </a>
            </span>
            @endif
            
            @if(request('min_gpa'))
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-yellow-100 text-yellow-800">
                Min. IPK: {{ request('min_gpa') }}
                <a href="{{ request()->fullUrlWithQuery(['min_gpa' => null]) }}" class="ml-2 text-yellow-600 hover:text-yellow-800">
                    <i class="fas fa-times"></i>
                </a>
            </span>
            @endif
            
            @if(request('completion_status'))
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-indigo-100 text-indigo-800">
                Status: {{ request('completion_status') == 'complete' ? 'Sudah Lengkap' : 'Belum Lengkap' }}
                <a href="{{ request()->fullUrlWithQuery(['completion_status' => null]) }}" class="ml-2 text-indigo-600 hover:text-indigo-800">
                    <i class="fas fa-times"></i>
                </a>
            </span>
            @endif
            
            @if(request('religion'))
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-pink-100 text-pink-800">
                Agama: {{ request('religion') }}
                <a href="{{ request()->fullUrlWithQuery(['religion' => null]) }}" class="ml-2 text-pink-600 hover:text-pink-800">
                    <i class="fas fa-times"></i>
                </a>
            </span>
            @endif
            
            @if(request('level'))
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-teal-100 text-teal-800">
                Level: {{ request('level') }}
                <a href="{{ request()->fullUrlWithQuery(['level' => null]) }}" class="ml-2 text-teal-600 hover:text-teal-800">
                    <i class="fas fa-times"></i>
                </a>
            </span>
            @endif
            
            @if(request()->hasAny(['search', 'gender', 'min_age', 'max_age', 'min_gpa', 'completion_status', 'religion', 'level']))
            <a href="{{ route('dashboard.users') }}" class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-gray-100 text-gray-800 hover:bg-gray-200">
                Hapus Semua Filter
            </a>
            @endif
        </div>
    </div>
    @endif
    <div class="bg-white rounded-2xl shadow-md overflow-hidden">
        <div class="flex flex-col gap-3 border-b px-4 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
            <h2 class="text-xl font-bold text-gray-900">Data Kandidat</h2>
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:gap-4">
                <p class="text-gray-500 text-sm">
                    Menampilkan {{ $users->firstItem() ?? 0 }}–{{ $users->lastItem() ?? 0 }} dari {{ number_format($users->total(), 0, ',', '.') }} kandidat
                </p>
                <div class="flex items-center space-x-2">
                    <label for="perPageSelect" class="text-gray-500 text-sm">Per halaman</label>
                    <select id="perPageSelect" class="min-h-10 px-3 py-1 border border-gray-300 rounded-lg text-sm focus:border-red-500 focus:outline-none focus:ring-2 focus:ring-red-100">
                        <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('per_page', 10) == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page', 10) == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('per_page', 10) == 100 ? 'selected' : '' }}>100</option>
                    </select>
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table id="candidateTable" class="min-w-[900px] w-full lg:min-w-full">
                <thead>
                    <tr class="bg-gray-50 border-b">
                        <th class="py-4 px-6 text-left text-gray-700 font-semibold">Kandidat</th>
                        <th class="py-4 px-6 text-left text-gray-700 font-semibold">Email & Kontak</th>
                        <th class="py-4 px-6 text-left text-gray-700 font-semibold whitespace-nowrap">Jenis Kelamin & Umur</th>
                        <th class="py-4 px-6 text-left text-gray-700 font-semibold">Pendidikan</th>
                        <th class="py-4 px-6 text-left text-gray-700 font-semibold">Status</th>
                        <th class="py-4 px-6 text-left text-gray-700 font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($users as $user)
                    @php
                        $detail = optional($user->detail);
                        $userName = $detail->name ?? $user->email;
                        $userGender = $detail->gender ?? null;
                        $userBirthDate = $detail->birth_date ?? null;
                        $userSchool = $detail->school ?? null;
                        $userGpa = $detail->gpa ?? null;
                        $userPhone = $detail->phone_number ?? null;
                        $userAddress = $detail->address ?? null;
                        $userReligion = $detail->religion ?? null;
                        $userCitizenship = $detail->citizenship ?? null;
                        $userLevel = $detail->level ?? null;
                        $userNoId = $detail->no_id ?? null;
                        $userProvince = $detail->province ?? null;
                        $userCity = $detail->city ?? null;
                        $userRegency = $detail->regency ?? null;
                        $userDistrict = $detail->district ?? null;

                        $userAge = '-';
                        $birthFormatted = '-';
                        if ($userBirthDate) {
                            try {
                                $userAge = \Carbon\Carbon::parse($userBirthDate)->age;
                                $birthFormatted = \Carbon\Carbon::parse($userBirthDate)->format('d/m/Y');
                            } catch (Exception $e) {
                                $userAge = '-';
                                $birthFormatted = '-';
                            }
                        }

                        $gpaDisplay = '-';
                        $gpaColorClass = 'text-gray-600 bg-gray-100';
                        if ($userGpa && is_numeric($userGpa)) {
                            $gpaFloat = (float) $userGpa;
                            $gpaDisplay = number_format($gpaFloat, 2);
                            $gpaColorClass = $gpaFloat >= 3.5 ? 'text-green-600 bg-green-100' : 
                                          ($gpaFloat >= 3.0 ? 'text-yellow-600 bg-yellow-100' : 
                                          ($gpaFloat >= 2.0 ? 'text-orange-600 bg-orange-100' : 'text-red-600 bg-red-100'));
                        } elseif ($userGpa) {
                            $gpaDisplay = $userGpa;
                        }

                        $isProfileComplete = $userNoId ? true : false;
                        $modalBirthDate = $userBirthDate ? \Carbon\Carbon::parse($userBirthDate)->format('d F Y') : '-';
                        $modalGpa = $userGpa ? (is_numeric($userGpa) ? number_format((float)$userGpa, 2) : $userGpa) : '-';
                        $fullAddress = '';
                        if ($userAddress) {
                            $fullAddress = $userAddress;
                            if ($userDistrict) $fullAddress .= ', ' . $userDistrict;
                            if ($userRegency) $fullAddress .= ', ' . $userRegency;
                            if ($userCity) $fullAddress .= ', ' . $userCity;
                            if ($userProvince) $fullAddress .= ', ' . $userProvince;
                        }
                    @endphp
                    
                    <tr class="hover:bg-gray-50 transition-colors cursor-pointer"
                        data-user-id="{{ $user->id }}"
                        data-name="{{ $userName }}"
                        data-email="{{ $user->email }}"
                        data-no-id="{{ $userNoId ?? '-' }}"
                        data-gender="{{ $userGender ? ($userGender === 'male' ? 'Laki-laki' : 'Perempuan') : '-' }}"
                        data-birth="{{ $modalBirthDate }}"
                        data-age="{{ $userAge }}"
                        data-created-at="{{ $user->created_at?->format('d F Y') ?? '-' }}"
                        data-updated-at="{{ $user->updated_at?->format('d F Y') ?? '-' }}"
                        data-religion="{{ $userReligion ?? '-' }}"
                        data-citizenship="{{ $userCitizenship ?? '-' }}"
                        data-school="{{ $userSchool ?? '-' }}"
                        data-level="{{ $userLevel ?? '-' }}"
                        data-gpa="{{ $modalGpa }}"
                        data-phone="{{ $userPhone ?? '-' }}"
                        data-address="{{ $fullAddress }}"
                        data-province="{{ $userProvince ?? '-' }}"
                        data-city="{{ $userCity ?? '-' }}"
                        data-regency="{{ $userRegency ?? '-' }}"
                        data-district="{{ $userDistrict ?? '-' }}"
                        data-fathers-name="{{ $detail->fathers_name ?? '-' }}"
                        data-fathers-occupation="{{ $detail->fathers_occupation ?? '-' }}"
                        data-mothers-name="{{ $detail->mothers_name ?? '-' }}"
                        data-mothers-occupation="{{ $detail->mothers_occupation ?? '-' }}"
                        data-image="{{ $detail->image ? asset(  $detail->image) : '' }}"
                    >
                        <td data-label="Kandidat" class="py-4 px-6">
                            <div class="flex items-center">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $userName }}</p>
                                    <p class="text-gray-500 text-sm">
                                        @if($userNoId)
                                            <i class="fas fa-id-card mr-1"></i> {{ $userNoId }}
                                        @else
                                            <i class="fas fa-clock mr-1"></i> Belum lengkap
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td data-label="Email & kontak" class="py-4 px-6">
                            <div class="text-gray-900">{{ $user->email }}</div>
                            @if($userPhone)
                            <div class="text-gray-500 text-sm">
                                <i class="fas fa-phone mr-1"></i> {{ $userPhone }}
                            </div>
                            @endif
                        </td>
                        <td data-label="Jenis kelamin & umur" class="py-4 px-6">
                            <div class="space-y-1">
                                @if($userGender)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $userGender === 'male' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                                        <i class="fas {{ $userGender === 'male' ? 'fa-mars' : 'fa-venus' }} mr-1"></i>
                                        {{ $userGender === 'male' ? 'Laki-laki' : 'Perempuan' }}
                                    </span>
                                @endif
                                @if($userAge !== '-')
                                <div class="text-gray-900 text-sm">
                                    <i class="fas fa-birthday-cake mr-1"></i> {{ $userAge }} tahun
                                </div>
                                @endif
                            </div>
                        </td>
                        <td data-label="Pendidikan" class="py-4 px-6">
                            <div class="space-y-1">
                                @if($userSchool)
                                <div class="text-gray-900 text-sm font-medium">{{ $userSchool }}</div>
                                @endif
                                @if($userLevel)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-gray-100 text-gray-800">
                                    {{ $userLevel }}
                                </span>
                                @endif
                                @if($gpaDisplay !== '-')
                                <div class="text-sm">
                                    IPK: <span class="font-medium {{ $gpaColorClass }} px-2 py-0.5 rounded">{{ $gpaDisplay }}</span>
                                </div>
                                @endif
                            </div>
                        </td>
                        <td data-label="Status profil" class="py-4 px-6">
                            @if($isProfileComplete)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    Lengkap
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    Belum Lengkap
                                </span>
                            @endif
                        </td>
                        <td data-label="Aksi" class="py-4 px-6">
                            <div class="flex flex-wrap items-center justify-end gap-2">
                                <button type="button"
                                    class="detail-btn inline-flex min-h-10 items-center gap-2 rounded-lg border border-slate-200 bg-white px-3 text-sm font-semibold text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-red-500"
                                    aria-label="Lihat detail {{ $userName }}">
                                    <i class="fas fa-eye text-slate-500" aria-hidden="true"></i><span>Detail</span>
                                </button>
                                <button type="button"
                                    class="delete-btn inline-flex min-h-10 items-center gap-2 rounded-lg border border-rose-200 bg-rose-50 px-3 text-sm font-semibold text-rose-700 hover:bg-rose-100 focus:outline-none focus:ring-2 focus:ring-red-500"
                                    data-user-id="{{ $user->id }}"
                                    data-no-modal="1" 
                                    aria-label="Hapus kandidat {{ $userName }}" title="Hapus kandidat ini">
                                    <i class="fas fa-trash" aria-hidden="true"></i><span>Hapus</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center">
                            <div class="w-24 h-24 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-6">
                                <i class="fas fa-users text-3xl text-gray-400"></i>
                            </div>
                            <h4 class="text-xl font-semibold text-gray-700 mb-2">Tidak Ada Data</h4>
                            <p class="text-gray-500 mb-6">
                                @if(request()->hasAny(['search', 'gender', 'min_age', 'max_age', 'min_gpa', 'completion_status', 'religion', 'level']))
                                    Tidak ada user yang sesuai dengan filter yang diterapkan.
                                    <a href="{{ route('dashboard.users') }}" class="text-red-600 hover:text-red-800 font-medium">Reset filter</a>
                                @else
                                    Belum ada data user yang tersedia.
                                @endif
                            </p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($users->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="text-sm text-gray-700 order-2 md:order-1">
                    Menampilkan 
                    <span class="font-medium">{{ $users->firstItem() }}</span>
                    sampai
                    <span class="font-medium">{{ $users->lastItem() }}</span>
                    dari
                    <span class="font-medium">{{ $users->total() }}</span>
                    hasil
                </div>
                
                <div class="pagination-container flex max-w-full items-center gap-1 overflow-x-auto order-1 md:order-2 sm:justify-end">
                    @if ($users->onFirstPage())
                        <span class="px-3 py-2 border border-gray-300 rounded-lg text-gray-400 cursor-not-allowed whitespace-nowrap">
                            <i class="fas fa-chevron-left"></i>
                        </span>
                    @else
                        <a href="{{ $users->previousPageUrl() }}" class="px-3 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 whitespace-nowrap">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    @endif
                    
                    @php
                        $currentPage = $users->currentPage();
                        $lastPage = $users->lastPage();
                        $showPages = 5; 
                        $startPage = max(1, $currentPage - floor($showPages / 2));
                        $endPage = min($lastPage, $startPage + $showPages - 1);
                        $startPage = max(1, $endPage - $showPages + 1);
                    @endphp
                    
                    @if($startPage > 1)
                        <a href="{{ $users->url(1) }}" class="px-3 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 whitespace-nowrap">1</a>
                        @if($startPage > 2)
                            <span class="px-2 py-2 text-gray-500">...</span>
                        @endif
                    @endif
                    
                    @for ($page = $startPage; $page <= $endPage; $page++)
                        @if ($page == $currentPage)
                            <span class="px-3 py-2 bg-red-600 text-white rounded-lg font-medium whitespace-nowrap">{{ $page }}</span>
                        @else
                            <a href="{{ $users->url($page) }}" class="px-3 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 whitespace-nowrap">{{ $page }}</a>
                        @endif
                    @endfor
                    
                    @if($endPage < $lastPage)
                        @if($endPage < $lastPage - 1)
                            <span class="px-2 py-2 text-gray-500">...</span>
                        @endif
                        <a href="{{ $users->url($lastPage) }}" class="px-3 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 whitespace-nowrap">{{ $lastPage }}</a>
                    @endif
                    
                    @if ($users->hasMorePages())
                        <a href="{{ $users->nextPageUrl() }}" class="px-3 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 whitespace-nowrap">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    @else
                        <span class="px-3 py-2 border border-gray-300 rounded-lg text-gray-400 cursor-not-allowed whitespace-nowrap">
                            <i class="fas fa-chevron-right"></i>
                        </span>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<div id="detailModal" role="dialog" aria-modal="true" aria-labelledby="detailModalTitle" class="hidden fixed inset-0 bg-slate-950/60 z-50 flex items-center justify-center p-2 backdrop-blur-sm sm:p-4">
    <div class="bg-white rounded-2xl w-full max-w-5xl max-h-[92vh] overflow-hidden shadow-2xl">
        <div class="flex items-center justify-between gap-3 border-b border-slate-200 px-4 py-4 sm:px-6">
            <div class="min-w-0">
                <h3 id="detailModalTitle" class="text-lg font-bold text-gray-900 sm:text-xl">Detail profil kandidat</h3>
                <p class="mt-1 text-xs text-slate-500 sm:text-sm">Informasi pribadi, pendidikan, alamat, dan keluarga.</p>
            </div>
            <button type="button" class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-xl text-gray-500 hover:bg-gray-100 hover:text-gray-800 focus:outline-none focus:ring-2 focus:ring-red-500" aria-label="Tutup detail kandidat" onclick="closeModal()">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <div class="overflow-y-auto p-4 sm:p-6" style="max-height: calc(92vh - 92px);">
            <div class="grid grid-cols-1 gap-5 lg:grid-cols-3 sm:gap-6">
                <div class="lg:col-span-1">
                    <div class="bg-gray-50 rounded-xl p-5 mb-6">
                        <div class="flex flex-col items-center">
                            <div class="mb-4 h-32 w-32 overflow-hidden rounded-2xl border-4 border-white shadow-lg sm:h-40 sm:w-40">
                                <img id="m_image" src="" alt="Foto kandidat" class="h-full w-full object-cover" onerror="this.onerror=null; this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTI4IiBoZWlnaHQ9IjEyOCIgdmlld0JveD0iMCAwIDEyOCAxMjgiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIxMjgiIGhlaWdodD0iMTI4IiByeD0iNjQiIGZpbGw9IiNERTI2MjYiLz4KPHRleHQgeD0iNjQiIHk9IjY0IiBmb250LWZhbWlseT0iQXJpYWwiIGZvbnQtc2l6ZT0iNDgiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIiBmaWxsPSJ3aGl0ZSI+TTwvdGV4dD4KPC9zdmc+'">
                            </div>
                            <h4 id="m_name" class="text-xl font-bold text-gray-900 text-center mb-1">-</h4>
                            <p id="m_email" class="text-gray-600 text-sm text-center mb-4">-</p>
                            <div class="flex space-x-2">
                                <span id="m_gender_badge" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-venus-mars mr-1"></i> <span id="m_gender">-</span>
                                </span>
                                <span id="m_age_badge" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-birthday-cake mr-1"></i> <span id="m_age">-</span> tahun
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <h4 class="font-semibold text-gray-900 mb-4 pb-2 border-b">Informasi Pribadi</h4>
                    <div class="space-y-3">
                        <div>
                            <p class="text-gray-500 text-sm">Nomor Identitas</p>
                            <p id="m_no_id" class="font-medium text-gray-900">-</p>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-gray-500 text-sm">Agama</p>
                                <p id="m_religion" class="font-medium text-gray-900">-</p>
                            </div>
                            <div>
                                <p class="text-gray-500 text-sm">Kewarganegaraan</p>
                                <p id="m_citizenship" class="font-medium text-gray-900">-</p>
                            </div>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm">Tanggal Lahir</p>
                            <p id="m_birth" class="font-medium text-gray-900">-</p>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm">Nomor Telepon</p>
                            <p id="m_phone" class="font-medium text-gray-900">-</p>
                        </div>
                    </div>
                </div>
                <div class="lg:col-span-1">
                    <h4 class="font-semibold text-gray-900 mb-4 pb-2 border-b">Informasi Pendidikan</h4>
                    <div class="space-y-3 mb-6">
                        <div>
                            <p class="text-gray-500 text-sm">Sekolah/Universitas</p>
                            <p id="m_school" class="font-medium text-gray-900">-</p>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-gray-500 text-sm">Jenjang</p>
                                <p id="m_level" class="font-medium text-gray-900">-</p>
                            </div>
                            <div>
                                <p class="text-gray-500 text-sm">IPK/Nilai Akhir</p>
                                <p id="m_gpa" class="font-medium text-gray-900">-</p>
                            </div>
                        </div>
                    </div>
                    
                    <h4 class="font-semibold text-gray-900 mb-4 pb-2 border-b">Informasi Alamat</h4>
                    <div class="space-y-3">
                        <div>
                            <p class="text-gray-500 text-sm">Alamat Lengkap</p>
                            <p id="m_address" class="font-medium text-gray-900 text-sm leading-relaxed">-</p>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-gray-500 text-sm">Provinsi</p>
                                <p id="m_province" class="font-medium text-gray-900">-</p>
                            </div>
                            <div>
                                <p class="text-gray-500 text-sm">Kota</p>
                                <p id="m_city" class="font-medium text-gray-900">-</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-gray-500 text-sm">Kabupaten</p>
                                <p id="m_regency" class="font-medium text-gray-900">-</p>
                            </div>
                            <div>
                                <p class="text-gray-500 text-sm">Kecamatan</p>
                                <p id="m_district" class="font-medium text-gray-900">-</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="lg:col-span-1">
                    <h4 class="font-semibold text-gray-900 mb-4 pb-2 border-b">Informasi Keluarga</h4>
                    <div class="space-y-4">
                        <div class="bg-blue-50 rounded-xl p-4">
                            <h5 class="font-medium text-blue-800 mb-2 flex items-center">
                                <i class="fas fa-male mr-2"></i> Informasi Ayah
                            </h5>
                            <div class="space-y-2">
                                <div>
                                    <p class="text-blue-600 text-xs">Nama Ayah</p>
                                    <p id="m_fathers_name" class="font-medium text-gray-900">-</p>
                                </div>
                                <div>
                                    <p class="text-blue-600 text-xs">Pekerjaan Ayah</p>
                                    <p id="m_fathers_occupation" class="font-medium text-gray-900">-</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-pink-50 rounded-xl p-4">
                            <h5 class="font-medium text-pink-800 mb-2 flex items-center">
                                <i class="fas fa-female mr-2"></i> Informasi Ibu
                            </h5>
                            <div class="space-y-2">
                                <div>
                                    <p class="text-pink-600 text-xs">Nama Ibu</p>
                                    <p id="m_mothers_name" class="font-medium text-gray-900">-</p>
                                </div>
                                <div>
                                    <p class="text-pink-600 text-xs">Pekerjaan Ibu</p>
                                    <p id="m_mothers_occupation" class="font-medium text-gray-900">-</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-4 mt-6">
                            <h5 class="font-medium text-gray-800 mb-2 flex items-center">
                                <i class="fas fa-calendar-alt mr-2"></i> Informasi Akun
                            </h5>
                            <div class="space-y-2">
                                <div>
                                    <p class="text-gray-600 text-xs">Email</p>
                                    <p id="m_account_email" class="font-medium text-gray-900">-</p>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-gray-600 text-xs">Tanggal Bergabung</p>
                                        <p id="m_created_at" class="font-medium text-gray-900">-</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-600 text-xs">Terakhir Diupdate</p>
                                        <p id="m_updated_at" class="font-medium text-gray-900">-</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-8 pt-6 border-t flex justify-end space-x-3">
                <button onclick="closeModal()" class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-all font-medium">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<div id="deleteConfirmModal" role="dialog" aria-modal="true" aria-labelledby="deleteConfirmTitle" class="hidden fixed inset-0 bg-slate-950/60 z-[60] flex items-end justify-center p-0 backdrop-blur-sm sm:items-center sm:p-4">
    <div class="bg-white rounded-t-3xl sm:rounded-2xl w-full max-w-md p-5 shadow-2xl sm:p-6">
        <h3 id="deleteConfirmTitle" class="text-lg font-bold text-gray-900">Hapus kandidat?</h3>
        <p class="mt-2 text-sm leading-relaxed text-gray-600">Anda akan menghapus akun kandidat <span id="deleteModalName" class="font-semibold text-slate-900"></span>. Data ini tidak dapat dipulihkan.</p>
        <div id="deleteError" role="alert" class="hidden mt-4 rounded-xl border border-red-200 bg-red-50 p-3 text-sm text-red-700"></div>
        <div class="mt-6 flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
            <button type="button" id="cancelDeleteBtn" class="min-h-11 w-full px-4 py-2 border border-slate-200 rounded-xl text-gray-700 hover:bg-gray-50 sm:w-auto">Kembali</button>
            <button type="button" id="confirmDeleteBtn" class="min-h-11 w-full px-4 py-2 bg-red-600 text-white rounded-xl font-semibold hover:bg-red-700 sm:w-auto">Ya, hapus kandidat</button>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const setText = (id, value) => {
        const el = document.getElementById(id);
        if (!el) return;
        el.innerText = (value !== undefined && value !== null && value !== '' && value !== '-') ? value : '-';
    };

    const setImage = (id, value) => {
        const el = document.getElementById(id);
        if (!el) return;
        if (value && value !== '' && value !== '-') {
            el.src = value;
            el.style.display = 'block';
        } else {
            el.src = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTI4IiBoZWlnaHQ9IjEyOCIgdmlld0JveD0iMCAwIDEyOCAxMjgiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIxMjgiIGhlaWdodD0iMTI4IiByeD0iNjQiIGZpbGw9IiNERTI2MjYiLz4KPHRleHQgeD0iNjQiIHk9IjY0IiBmb250LWZhbWlseT0iQXJpYWwiIGZvbnQtc2l6ZT0iNDgiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIiBmaWxsPSJ3aGl0ZSI+TTwvdGV4dD4KPC9zdmc+';
        }
    };

    const openModal = (modal) => {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    };

    const closeModalEl = (modal) => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = 'auto';
    };
    const detailModal = document.getElementById("detailModal");

    function showUserDetail(ds) {
        setImage("m_image", ds.image);
        setText("m_name", ds.name);
        setText("m_email", ds.email);
        setText("m_account_email", ds.email);
        setText("m_created_at", ds.createdAt);
        setText("m_updated_at", ds.updatedAt);
        setText("m_gender", ds.gender);
        setText("m_age", ds.age);
        setText("m_no_id", ds.noId);
        setText("m_birth", ds.birth);
        setText("m_religion", ds.religion);
        setText("m_citizenship", ds.citizenship);
        setText("m_phone", ds.phone);
        setText("m_school", ds.school);
        setText("m_level", ds.level);
        setText("m_gpa", ds.gpa);
        setText("m_address", ds.address);
        setText("m_province", ds.province);
        setText("m_city", ds.city);
        setText("m_regency", ds.regency);
        setText("m_district", ds.district);
        setText("m_fathers_name", ds.fathersName);
        setText("m_fathers_occupation", ds.fathersOccupation);
        setText("m_mothers_name", ds.mothersName);
        setText("m_mothers_occupation", ds.mothersOccupation);
        const genderBadge = document.getElementById("m_gender_badge");
        if (genderBadge) {
            if (ds.gender === 'Laki-laki') {
                genderBadge.className = "inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800";
            } else if (ds.gender === 'Perempuan') {
                genderBadge.className = "inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-pink-100 text-pink-800";
            }
        }

        openModal(detailModal);
    }

    document.querySelector('tbody')?.addEventListener('click', function (e) {
        const row = e.target.closest('tr[data-user-id]');
        if (!row) return;
        const detailTrigger = e.target.closest('.detail-btn');
        if (e.target.closest('.delete-btn')) return;
        if (e.target.closest('button, a') && !detailTrigger) return;

        showUserDetail({
            name: row.dataset.name,
            email: row.dataset.email,
            noId: row.dataset.noId,
            gender: row.dataset.gender,
            age: row.dataset.age,
            createdAt: row.dataset.createdAt,
            updatedAt: row.dataset.updatedAt,
            birth: row.dataset.birth,
            religion: row.dataset.religion,
            citizenship: row.dataset.citizenship,
            phone: row.dataset.phone,
            school: row.dataset.school,
            level: row.dataset.level,
            gpa: row.dataset.gpa,
            address: row.dataset.address,
            province: row.dataset.province,
            city: row.dataset.city,
            regency: row.dataset.regency,
            district: row.dataset.district,
            fathersName: row.dataset.fathersName,
            fathersOccupation: row.dataset.fathersOccupation,
            mothersName: row.dataset.mothersName,
            mothersOccupation: row.dataset.mothersOccupation,
            image: row.dataset.image
        });
    });
    window.closeModal = function () {
        closeModalEl(detailModal);
    };

    detailModal?.addEventListener('click', function (e) {
        if (e.target === detailModal) {
            closeModalEl(detailModal);
        }
    });
    const filterBtn = document.getElementById('filterBtn');
    const filterOptions = document.getElementById('filterOptions');

    filterBtn?.addEventListener('click', () => {
        filterOptions.classList.toggle('hidden');
        filterBtn.setAttribute('aria-expanded', String(!filterOptions.classList.contains('hidden')));
    });
    const perPageSelect = document.getElementById('perPageSelect');
    const filterForm = document.getElementById('filterForm');
    const filterFormPerPage = document.getElementById('filterFormPerPage');

    perPageSelect?.addEventListener('change', function () {
        filterFormPerPage.value = this.value;
        filterForm.submit();
    });
    window.resetFilters = function() {
        const url = new URL(window.location.href);
        const params = ['search', 'gender', 'min_age', 'max_age', 'min_gpa', 'completion_status', 'religion', 'level', 'sort_by', 'sort_order'];
        params.forEach(param => url.searchParams.delete(param));
        window.location.href = url.toString();
    };
    const deleteModal = document.getElementById('deleteConfirmModal');
    const deleteModalName = document.getElementById('deleteModalName');
    const deleteError = document.getElementById('deleteError');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    let deleteTargetId = null;
    let deleteTargetRow = null;

    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.stopPropagation();

            deleteTargetRow = this.closest('tr');
            deleteTargetId = this.dataset.userId || deleteTargetRow?.dataset.userId;

            deleteModalName.innerText = deleteTargetRow?.dataset.name || '-';
            deleteError.classList.add('hidden');

            openModal(deleteModal);
        });
    });

    cancelDeleteBtn?.addEventListener('click', () => {
        closeModalEl(deleteModal);
        deleteTargetId = null;
        deleteTargetRow = null;
    });

    confirmDeleteBtn?.addEventListener('click', async () => {
        if (!deleteTargetId) return;

        confirmDeleteBtn.disabled = true;
        confirmDeleteBtn.innerText = 'Menghapus...';

        try {
            const res = await fetch(`/dashboard/users/${deleteTargetId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                }
            });

            if (!res.ok) throw new Error('Gagal menghapus user');

            deleteTargetRow?.remove();
            closeModalEl(deleteModal);

        } catch (err) {
            deleteError.innerText = err.message;
            deleteError.classList.remove('hidden');
        } finally {
            confirmDeleteBtn.disabled = false;
            confirmDeleteBtn.innerText = 'Hapus';
        }
    });

    deleteModal?.addEventListener('click', function (e) {
        if (e.target === deleteModal) {
            closeModalEl(deleteModal);
        }
    });

});
</script>

<style>
    .user-row {
        transition: all 0.2s ease;
    }
    
    .user-row:hover {
        background-color: #f9fafb;
    }

    @media (max-width: 767px) {
        #candidateTable,
        #candidateTable tbody,
        #candidateTable tr,
        #candidateTable td {
            display: block;
            width: 100%;
        }

        #candidateTable {
            min-width: 0;
        }

        #candidateTable thead {
            display: none;
        }

        #candidateTable tbody tr[data-user-id] {
            margin: 0.75rem;
            width: calc(100% - 1.5rem);
            overflow: hidden;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            background: #fff;
            box-shadow: 0 1px 2px rgb(15 23 42 / 0.05);
        }

        #candidateTable tbody tr[data-user-id] td {
            display: grid;
            grid-template-columns: minmax(7rem, 0.38fr) minmax(0, 1fr);
            align-items: start;
            gap: 0.75rem;
            padding: 0.8rem 1rem;
            border-bottom: 1px solid #f1f5f9;
            overflow-wrap: anywhere;
        }

        #candidateTable tbody tr[data-user-id] td::before {
            content: attr(data-label);
            color: #64748b;
            font-size: 0.75rem;
            font-weight: 600;
            line-height: 1.25rem;
        }

        #candidateTable tbody tr[data-user-id] td:last-child {
            border-bottom: 0;
        }

        #candidateTable tbody tr[data-user-id] td[data-label="Aksi"] > div {
            justify-content: flex-end;
        }
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    #detailModal {
        animation: fadeIn 0.3s ease-out;
    }

    #detailModal > div > div {
        scrollbar-width: thin;
        scrollbar-color: #dc2626 #f1f1f1;
    }
    
    #detailModal > div > div::-webkit-scrollbar {
        width: 6px;
    }
    
    #detailModal > div > div::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    #detailModal > div > div::-webkit-scrollbar-thumb {
        background: #dc2626;
        border-radius: 10px;
    }
    
    @media (max-width: 640px) {
        .pagination-container {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
    }
</style>
@endsection
