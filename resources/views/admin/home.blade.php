@extends('layouts.sidebar')
@section('page-title', 'Dashboard')
@section('current-page', 'Ringkasan')
@section('content')
<div class="space-y-5 sm:space-y-7">
    @if(session('success'))
        <div id="success-message" class="mb-6 p-4 rounded-xl bg-gradient-to-r from-green-50 to-green-100 border border-green-200 animate-fade-in">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
                <button type="button" onclick="this.parentElement.parentElement.remove()" class="ml-auto -mx-1.5 -my-1.5 text-green-600 hover:text-green-800">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    @endif
    @if(session('error'))
        <div id="error-message" class="mb-6 p-4 rounded-xl bg-gradient-to-r from-red-50 to-red-100 border border-red-200 animate-fade-in">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
                <button type="button" onclick="this.parentElement.parentElement.remove()" class="ml-auto -mx-1.5 -my-1.5 text-red-600 hover:text-red-800">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    @endif

    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-rose-700 via-rose-600 to-indigo-700 p-5 text-white shadow-lg shadow-rose-900/10 sm:p-7 lg:p-8">
        <div class="pointer-events-none absolute -right-12 -top-20 h-60 w-60 rounded-full border-[36px] border-white/10"></div>
        <div class="relative flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
            <div class="max-w-2xl">
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-rose-100">Panel administrator</p>
                <h1 class="mt-2 text-2xl font-black tracking-tight sm:text-3xl lg:text-4xl">Selamat datang, {{ Str::words(Auth::user()->name, 2, '') }}</h1>
                <p class="mt-2 text-sm leading-6 text-rose-50 sm:text-base">Kelola tes dan pantau ringkasan aktivitas kandidat dari satu tempat.</p>
            </div>
            <button class="inline-flex min-h-11 shrink-0 items-center justify-center rounded-xl bg-white px-4 py-2.5 text-sm font-bold text-rose-700 shadow-md transition hover:-translate-y-0.5 hover:bg-rose-50 focus:outline-none focus:ring-2 focus:ring-white/80" data-modal-toggle="createTestModal">
                <i class="fas fa-plus mr-2"></i> Tambah Tes
            </button>
        </div>
    </div>

    @php
        $testIcons = [
            'fa-clipboard-check', 'fa-brain', 'fa-chart-line', 'fa-lightbulb',
            'fa-calculator', 'fa-book-open', 'fa-puzzle-piece', 'fa-flask',
            'fa-code', 'fa-chart-bar', 'fa-question-circle', 'fa-tasks',
            'fa-graduation-cap', 'fa-clock', 'fa-star', 'fa-certificate'
        ];
        
        $testColors = [
            ['bg' => 'from-green-100 to-green-50', 'border' => 'border-green-200', 'text' => 'text-green-600'],
            ['bg' => 'from-blue-100 to-blue-50', 'border' => 'border-blue-200', 'text' => 'text-blue-600'],
            ['bg' => 'from-purple-100 to-purple-50', 'border' => 'border-purple-200', 'text' => 'text-purple-600'],
            ['bg' => 'from-amber-100 to-amber-50', 'border' => 'border-amber-200', 'text' => 'text-amber-600'],
            ['bg' => 'from-rose-100 to-rose-50', 'border' => 'border-rose-200', 'text' => 'text-rose-600'],
            ['bg' => 'from-indigo-100 to-indigo-50', 'border' => 'border-indigo-200', 'text' => 'text-indigo-600'],
            ['bg' => 'from-cyan-100 to-cyan-50', 'border' => 'border-cyan-200', 'text' => 'text-cyan-600'],
            ['bg' => 'from-emerald-100 to-emerald-50', 'border' => 'border-emerald-200', 'text' => 'text-emerald-600']
        ];
        
        function getTestStyle($testName, $testIcons, $testColors) {
            $hash = crc32($testName);
            $iconIndex = abs($hash) % count($testIcons);
            $colorIndex = abs($hash) % count($testColors);
            
            return [
                'icon' => $testIcons[$iconIndex],
                'bg' => $testColors[$colorIndex]['bg'],
                'border' => $testColors[$colorIndex]['border'],
                'text' => $testColors[$colorIndex]['text']
            ];
        }
    @endphp
    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-4 sm:gap-4 lg:gap-5">
        <div class="min-w-0 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:shadow-md sm:p-5">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border border-blue-100 bg-blue-50 sm:h-14 sm:w-14">
                    <i class="fas fa-users text-lg sm:text-xl md:text-2xl text-blue-600"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-2xl font-black tracking-tight text-slate-900 sm:text-3xl">{{ $totalUsers ?? '0' }}</p>
                    <p class="mt-0.5 truncate text-sm font-semibold text-slate-600">Total Kandidat</p>
                    <div class="mt-1 flex items-center gap-1.5">
                        <i class="fas {{ $growthIcon ?? 'fa-arrow-up' }} {{ $growthColor ?? 'text-green-600' }} text-xs"></i>
                        <span class="text-xs font-semibold {{ $growthColor ?? 'text-green-600' }}">{{ $growthPercentage ?? '0' }}%</span>
                        <span class="truncate text-xs text-slate-400">dari bulan lalu</span>
                    </div>
                </div>
            </div>
        </div>

        @foreach ($tests as $test)
            @php
                $style = getTestStyle($test->name, $testIcons, $testColors);
                $testAttemptCount = $testAttempts[$test->id] ?? 0;
            @endphp
            
            <div class="min-w-0 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:shadow-md sm:p-5">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br {{ $style['bg'] }} border {{ $style['border'] }} sm:h-14 sm:w-14">
                        <i class="fas {{ $style['icon'] }} text-lg sm:text-xl md:text-2xl {{ $style['text'] }}"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-2xl font-black tracking-tight text-slate-900 sm:text-3xl">{{ $testAttemptCount }}</p>
                        <p class="mt-0.5 truncate text-sm font-semibold text-slate-600" title="{{ $test->name }} Test">{{ Str::limit($test->name, 22) }} Test</p>
                        <p class="mt-1 truncate text-xs text-slate-400">Total sudah mengerjakan</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-col gap-3 border-b border-slate-100 px-4 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
            <div>
                <h2 class="text-lg font-extrabold text-slate-900 sm:text-xl">Daftar Tes</h2>
                <p class="mt-1 text-sm text-slate-500">Kelola tes yang tersedia di sistem.</p>
            </div>
        </div>
        
        @if(isset($tests) && count($tests) > 0)
        <div class="grid grid-cols-1 gap-3 p-3 sm:grid-cols-2 sm:gap-4 sm:p-5 xl:grid-cols-3">
            @foreach($tests as $test)
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white transition duration-200 hover:-translate-y-0.5 hover:border-rose-200 hover:shadow-lg hover:shadow-slate-900/5">
                <div class="border-b border-slate-100 px-4 py-3">
                    <div class="grid grid-cols-[auto_1fr_auto] items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-r from-red-500 to-red-600 rounded-xl flex items-center justify-center">
                            <i class="fas fa-clipboard-check text-white text-base"></i>
                        </div>
                        <div class="min-w-0 max-w-full overflow-hidden">
                            <h3
                                class="font-semibold text-gray-900 text-sm sm:text-base truncate block"
                                title="{{ $test->name ?? 'Nama Test' }}"
                            >
                                {{ $test->name ?? 'Nama Test' }}
                            </h3>
                        </div>
                        <div class="relative">
                            <button
                                class="w-8 h-8 flex items-center justify-center text-gray-500 hover:bg-gray-100 rounded-lg"
                                id="testMenu{{ $test->id }}"
                            >
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <div
                                id="testDropdown{{ $test->id }}"
                                class="hidden absolute right-0 mt-1 w-44 bg-white border rounded-lg shadow-lg z-50"
                            >
                                @if(isset($test->slug))
                                <a href="{{ url('/test/'.$test->slug) }}"
                                class="block px-4 py-2 text-sm hover:bg-red-50">
                                    Lihat Test
                                </a>
                                @endif

                                <a href="#"
                                data-modal-toggle="editTestModal{{ $test->id }}"
                                class="block px-4 py-2 text-sm hover:bg-red-50">
                                    Update
                                </a>

                                <a href="#"
                                data-modal-toggle="deleteTestModal{{ $test->id }}"
                                class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                    Hapus
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="p-4 sm:p-5">

                    <p class="text-gray-600 text-sm mb-4 line-clamp-2 break-words">
                        {{ $test->description ?? 'Tidak ada deskripsi' }}
                    </p>

                    <div class="flex items-center gap-3 text-sm">
                        @if($test->is_active)
                            <span class="flex items-center gap-1 px-3 py-1 bg-green-100 text-green-800 rounded-full">
                                <i class="fas fa-check-circle"></i> Aktif
                            </span>
                        @else
                            <span class="flex items-center gap-1 px-3 py-1 bg-gray-100 text-gray-800 rounded-full">
                                <i class="fas fa-times-circle"></i> Tidak Aktif
                            </span>
                        @endif

                        <span class="flex items-center gap-1 text-gray-500">
                            <i class="far fa-clock"></i>
                            {{ optional($test->created_at)->format('d M Y') ?? '-' }}
                        </span>
                    </div>
                </div>
                <div class="border-t border-slate-100 bg-slate-50/70 px-4 py-3 text-center">
                    @if(isset($test->slug))
                    <a href="{{ url('/test/'.$test->slug) }}"
                    class="inline-flex items-center gap-2 font-semibold text-rose-700 hover:text-rose-800">
                        Kelola Test
                        <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                    @else
                    <span class="text-gray-400 text-sm">Test tidak tersedia</span>
                    @endif
                </div>

            </div>
            @endforeach
        </div>
    @else
        <div class="p-6 text-center sm:p-8 md:p-12">
            <div class="w-16 h-16 sm:w-20 sm:h-20 md:w-24 md:h-24 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4 sm:mb-6">
                <i class="fas fa-clipboard-list text-xl sm:text-2xl md:text-3xl text-gray-400"></i>
            </div>
            <h3 class="text-lg sm:text-xl md:text-2xl font-semibold text-gray-700 mb-2 sm:mb-3">Belum ada test</h3>
            <p class="text-gray-500 text-sm sm:text-base mb-4 sm:mb-6 max-w-md mx-auto px-4">Mulai dengan membuat test pertama Anda</p>
            <button 
                class="bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white px-5 py-2.5 sm:px-6 sm:py-3 rounded-lg sm:rounded-xl font-semibold shadow-md hover:shadow-lg transition-all duration-200 active:scale-95 touch-manipulation flex items-center mx-auto text-sm sm:text-base"
                data-modal-toggle="createTestModal">
                <i class="fas fa-plus mr-1.5 sm:mr-2"></i> 
                <span>Buat Test Pertama</span>
            </button>
        </div>
    @endif
    </div>
</div>
<div id="createTestModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-md modal-animation">
        <form method="POST" action="{{ route('test.store') }}" id="createTestForm" novalidate>
            @csrf
            <div class="px-6 py-4 border-b flex items-center justify-between">
                <h3 class="text-xl font-bold text-gray-900">Tambah Test Baru</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors" data-modal-close="createTestModal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-6">
                @if(session('error_type') == 'create')
                    @if($errors->any())
                        <div class="mb-6 p-4 rounded-xl bg-gradient-to-r from-red-50 to-red-100 border border-red-200">
                            <div class="flex items-center mb-2">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-red-800">Terdapat kesalahan dalam pengisian form:</p>
                                </div>
                            </div>
                            <ul class="list-disc list-inside text-sm text-red-700 ml-8 mt-2 space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                @endif
                <div class="mb-5">
                    <label class="block text-gray-700 text-sm font-medium mb-2 required">
                        Nama Test
                    </label>
                    <input 
                        type="text" 
                        name="name" 
                        class="w-full px-4 py-3 border {{ session('error_type') == 'create' && $errors->has('name') ? 'border-red-500' : 'border-gray-300' }} rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all" 
                        required 
                        placeholder="Masukkan nama test"
                        value="{{ old('name', session('old_input.name') ?? '') }}"
                        maxlength="255"
                        minlength="3"
                        pattern="[a-zA-Z0-9\s\-]+"
                        title="Nama test hanya boleh mengandung huruf, angka, spasi, dan tanda hubung"
                    >
                    @if(session('error_type') == 'create' && $errors->has('name'))
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $errors->first('name') }}
                        </p>
                    @endif
                    <p class="mt-1 text-xs text-gray-500">
                        Minimal 3 karakter, maksimal 255 karakter. Tidak boleh mengandung simbol khusus.
                    </p>
                    <label class="block text-gray-700 text-sm font-medium mb-2 mt-4 required">Jenis Tes</label>
                    <select name="type" required class="w-full px-4 py-3 border {{ $errors->has('type') && session('error_type') == 'create' ? 'border-red-500' : 'border-gray-300' }} rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500">
                        <option value="" disabled {{ old('type', session('old_input.type') ?? '') == '' ? 'selected' : '' }}>Pilih jenis tes</option>
                        <option value="TPA" {{ old('type', session('old_input.type') ?? '') == 'TPA' ? 'selected' : '' }}>TPA</option>
                        <option value="SA" {{ old('type', session('old_input.type') ?? '') == 'SA' ? 'selected' : '' }}>Speed Accuracy (SA)</option>
                        <option value="OTHER" {{ old('type', session('old_input.type') ?? '') == 'OTHER' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                    @if(session('error_type') == 'create' && $errors->has('type'))
                        <p class="mt-2 text-sm text-red-600">{{ $errors->first('type') }}</p>
                    @endif
                </div>
                
                <div class="mb-5">
                    <label class="block text-gray-700 text-sm font-medium mb-2">
                        Deskripsi <span class="text-gray-400">(Opsional)</span>
                    </label>
                    <textarea 
                        name="description" 
                        rows="4" 
                        class="w-full px-4 py-3 border {{ session('error_type') == 'create' && $errors->has('description') ? 'border-red-500' : 'border-gray-300' }} rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all" 
                        placeholder="Tambahkan deskripsi test"
                        maxlength="1000"
                    >{{ old('description', session('old_input.description') ?? '') }}</textarea>
                    @if(session('error_type') == 'create' && $errors->has('description'))
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $errors->first('description') }}
                        </p>
                    @endif
                    <div class="flex justify-between items-center mt-1">
                        <p class="text-xs text-gray-500">
                            Maksimal 1000 karakter
                        </p>
                        <span class="text-xs text-gray-400 char-counter" data-target="description">0/1000</span>
                    </div>
                </div>
                <div class="mb-5">
                    <label class="block text-gray-700 text-sm font-medium mb-2">
                        Peraturan <span class="text-gray-400">(Opsional)</span>
                    </label>
                    <textarea 
                        name="rules" 
                        rows="4" 
                        class="w-full px-4 py-3 border {{ session('error_type') == 'create' && $errors->has('rules') ? 'border-red-500' : 'border-gray-300' }} rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all" 
                        placeholder="Tambahkan peraturan test"
                        maxlength="2000"
                    >{{ old('rules', session('old_input.rules') ?? '') }}</textarea>
                    @if(session('error_type') == 'create' && $errors->has('rules'))
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $errors->first('rules') }}
                        </p>
                    @endif
                    <div class="flex justify-between items-center mt-1">
                        <p class="text-xs text-gray-500">
                            Maksimal 2000 karakter
                        </p>
                        <span class="text-xs text-gray-400 char-counter" data-target="rules">0/2000</span>
                    </div>
                </div>
                <div class="mb-5">
                    <label class="flex items-center">
                        <input 
                            type="checkbox" 
                            name="is_active" 
                            value="1"
                            class="w-4 h-4 text-red-600 rounded border-gray-300 focus:ring-red-500 transition-colors" 
                            @if(old('is_active', session('old_input.is_active') ?? true)) checked @endif
                        >
                        <span class="ml-2 text-gray-700 text-sm">Aktifkan Test</span>
                    </label>
                    <p class="mt-1 text-xs text-gray-500">
                        Test yang tidak aktif tidak akan ditampilkan kepada pengguna
                    </p>
                </div>
            </div>
            <div class="px-6 py-4 border-t flex justify-end space-x-3">
                <button type="button" class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-all font-medium" data-modal-close="createTestModal">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl hover:from-red-700 hover:to-red-800 transition-all font-medium shadow-md disabled:opacity-50 disabled:cursor-not-allowed" id="createSubmitBtn">
                    <span class="flex items-center">
                        <i class="fas fa-save mr-2"></i>
                        <span>Simpan Test</span>
                        <span id="createLoading" class="hidden ml-2">
                            <i class="fas fa-spinner fa-spin"></i>
                        </span>
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>
@if(isset($tests) && count($tests) > 0)
@foreach($tests as $test)
<div id="editTestModal{{ $test->id ?? '0' }}" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-md modal-animation">
        <form method="POST" action="{{ route('test.update', $test->id ?? '0') }}" id="editTestForm{{ $test->id }}" novalidate>
            @csrf
            @method('PUT')
            <div class="px-6 py-4 border-b flex items-center justify-between">
                <h3 class="text-xl font-bold text-gray-900">Update Test</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors" data-modal-close="editTestModal{{ $test->id ?? '0' }}">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-6">
                @if(session('error_type') == 'edit_' . $test->id)
                    @if($errors->any())
                        <div class="mb-6 p-4 rounded-xl bg-gradient-to-r from-red-50 to-red-100 border border-red-200">
                            <div class="flex items-center mb-2">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-red-800">Terdapat kesalahan dalam pengisian form:</p>
                                </div>
                            </div>
                            <ul class="list-disc list-inside text-sm text-red-700 ml-8 mt-2 space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                @endif
                <div class="mb-5">
                    <label class="block text-gray-700 text-sm font-medium mb-2 required">
                        Nama Test
                    </label>
                    <input 
                        type="text" 
                        name="name" 
                        class="w-full px-4 py-3 border {{ session('error_type') == 'edit_' . $test->id && $errors->has('name') ? 'border-red-500' : 'border-gray-300' }} rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all" 
                        required 
                        value="{{ old('name', session('error_type') == 'edit_' . $test->id ? (session('old_input.name') ?? $test->name) : $test->name) }}"
                        maxlength="255"
                        minlength="3"
                        pattern="[a-zA-Z0-9\s\-]+"
                        title="Nama test hanya boleh mengandung huruf, angka, spasi, dan tanda hubung"
                    >
                    @if(session('error_type') == 'edit_' . $test->id && $errors->has('name'))
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $errors->first('name') }}
                        </p>
                    @endif
                    <p class="mt-1 text-xs text-gray-500">
                        Minimal 3 karakter, maksimal 255 karakter. Tidak boleh mengandung simbol khusus.
                    </p>
                    <label class="block text-gray-700 text-sm font-medium mb-2 mt-4 required">Jenis Tes</label>
                    <select name="type" required class="w-full px-4 py-3 border {{ session('error_type') == 'edit_' . $test->id && $errors->has('type') ? 'border-red-500' : 'border-gray-300' }} rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500">
                        <option value="TPA" {{ old('type', session('error_type') == 'edit_' . $test->id ? (session('old_input.type') ?? $test->type) : $test->type) == 'TPA' ? 'selected' : '' }}>TPA</option>
                        <option value="SA" {{ old('type', session('error_type') == 'edit_' . $test->id ? (session('old_input.type') ?? $test->type) : $test->type) == 'SA' ? 'selected' : '' }}>Speed Accuracy (SA)</option>
                        <option value="OTHER" {{ old('type', session('error_type') == 'edit_' . $test->id ? (session('old_input.type') ?? $test->type) : $test->type) == 'OTHER' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                    @if(session('error_type') == 'edit_' . $test->id && $errors->has('type'))
                        <p class="mt-2 text-sm text-red-600">{{ $errors->first('type') }}</p>
                    @endif
                </div>
                
                <div class="mb-5">
                    <label class="block text-gray-700 text-sm font-medium mb-2">
                        Deskripsi <span class="text-gray-400">(Opsional)</span>
                    </label>
                    <textarea 
                        name="description" 
                        rows="4" 
                        class="w-full px-4 py-3 border {{ session('error_type') == 'edit_' . $test->id && $errors->has('description') ? 'border-red-500' : 'border-gray-300' }} rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all"
                        maxlength="1000"
                    >{{ old('description', session('error_type') == 'edit_' . $test->id ? (session('old_input.description') ?? $test->description) : $test->description) }}</textarea>
                    @if(session('error_type') == 'edit_' . $test->id && $errors->has('description'))
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $errors->first('description') }}
                        </p>
                    @endif
                    <div class="flex justify-between items-center mt-1">
                        <p class="text-xs text-gray-500">
                            Maksimal 1000 karakter
                        </p>
                        <span class="text-xs text-gray-400 char-counter" data-target="description{{ $test->id }}">0/1000</span>
                    </div>
                </div>

                <div class="mb-5">
                    <label class="block text-gray-700 text-sm font-medium mb-2">
                        Peraturan <span class="text-gray-400">(Opsional)</span>
                    </label>
                    <textarea 
                        name="rules" 
                        rows="4" 
                        class="w-full px-4 py-3 border {{ session('error_type') == 'edit_' . $test->id && $errors->has('rules') ? 'border-red-500' : 'border-gray-300' }} rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all"
                        maxlength="2000"
                    >{{ old('rules', session('error_type') == 'edit_' . $test->id ? (session('old_input.rules') ?? $test->rules) : $test->rules) }}</textarea>
                    @if(session('error_type') == 'edit_' . $test->id && $errors->has('rules'))
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $errors->first('rules') }}
                        </p>
                    @endif
                    <div class="flex justify-between items-center mt-1">
                        <p class="text-xs text-gray-500">
                            Maksimal 2000 karakter
                        </p>
                        <span class="text-xs text-gray-400 char-counter" data-target="rules{{ $test->id }}">0/2000</span>
                    </div>
                </div>

                <div class="mb-5">
                    <label class="flex items-center">
                        <input 
                            type="checkbox" 
                            name="is_active" 
                            value="1"
                            class="w-4 h-4 text-red-600 rounded border-gray-300 focus:ring-red-500 transition-colors" 
                            @if(old('is_active', session('error_type') == 'edit_' . $test->id ? (session('old_input.is_active') ?? $test->is_active) : $test->is_active)) checked @endif
                        >
                        <span class="ml-2 text-gray-700 text-sm">Aktifkan Test</span>
                    </label>
                    <p class="mt-1 text-xs text-gray-500">
                        Test yang tidak aktif tidak akan ditampilkan kepada pengguna
                    </p>
                </div>
            </div>
            <div class="px-6 py-4 border-t flex justify-end space-x-3">
                <button type="button" class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-all font-medium" data-modal-close="editTestModal{{ $test->id ?? '0' }}">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl hover:from-red-700 hover:to-red-800 transition-all font-medium shadow-md disabled:opacity-50 disabled:cursor-not-allowed" id="editSubmitBtn{{ $test->id }}">
                    <span class="flex items-center">
                        <i class="fas fa-save mr-2"></i>
                        <span>Simpan Perubahan</span>
                        <span id="editLoading{{ $test->id }}" class="hidden ml-2">
                            <i class="fas fa-spinner fa-spin"></i>
                        </span>
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>
<div id="deleteTestModal{{ $test->id ?? '0' }}" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-md modal-animation">
        <form method="POST" action="{{ route('test.destroy', $test->id ?? '0') }}" id="deleteTestForm{{ $test->id }}">
            @csrf
            @method('DELETE')
            <div class="px-6 py-4 border-b flex items-center justify-between">
                <h3 class="text-xl font-bold text-gray-900">Hapus Test</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600" data-modal-close="deleteTestModal{{ $test->id ?? '0' }}">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-6">
                @if(session('error_type') == 'delete_' . $test->id)
                    <div class="mb-6 p-4 rounded-xl bg-gradient-to-r from-red-50 to-red-100 border border-red-200">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="flex items-center justify-center mb-6">
                    <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-3xl text-red-600"></i>
                    </div>
                </div>
                <h4 class="text-lg font-semibold text-gray-900 text-center mb-2">Anda yakin ingin menghapus?</h4>
                <p class="text-gray-600 text-center mb-2">
                    Test <span class="font-bold">{{ $test->name ?? 'Test ini' }}</span> akan dihapus permanen.
                </p>
                <div class="mt-4 p-4 bg-red-50 rounded-lg border border-red-200">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h5 class="text-sm font-medium text-red-800">Peringatan!</h5>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="list-disc list-inside space-y-1">
                                    <li>Tindakan ini tidak dapat dibatalkan</li>
                                    <li>Semua data terkait test akan dihapus</li>
                                    <li>Histori attempt dari test ini akan hilang</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Ketik "<span class="font-bold text-red-600">{{ $test->name }}</span>" untuk konfirmasi
                    </label>
                    <input 
                        type="text" 
                        id="deleteConfirm{{ $test->id }}" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all"
                        placeholder="Ketik nama test untuk konfirmasi"
                        onkeyup="toggleDeleteButton({{ $test->id }}, '{{ $test->name }}')"
                    >
                    <p class="mt-2 text-sm text-gray-500">
                        Tindakan ini untuk mencegah penghapusan yang tidak disengaja
                    </p>
                </div>
            </div>
            <div class="px-6 py-4 border-t flex justify-end space-x-3">
                <button type="button" class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-all font-medium" data-modal-close="deleteTestModal{{ $test->id ?? '0' }}">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-all font-medium shadow-md disabled:opacity-50 disabled:cursor-not-allowed" id="deleteSubmitBtn{{ $test->id }}" disabled>
                    <span class="flex items-center">
                        <i class="fas fa-trash mr-2"></i>
                        <span>Ya, Hapus Permanen</span>
                        <span id="deleteLoading{{ $test->id }}" class="hidden ml-2">
                            <i class="fas fa-spinner fa-spin"></i>
                        </span>
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>
@endforeach
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('[data-modal-toggle]').forEach(button => {
        button.addEventListener('click', function() {
            const modalId = this.getAttribute('data-modal-toggle');
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden';
                const firstInput = modal.querySelector('input, textarea, select');
                if (firstInput) {
                    setTimeout(() => firstInput.focus(), 100);
                }
            }
        });
    });

    document.querySelectorAll('[data-modal-close]').forEach(button => {
        button.addEventListener('click', function() {
            const modalId = this.getAttribute('data-modal-close');
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = 'auto';
            }
        });
    });

    document.querySelectorAll('.fixed.bg-black').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
                this.classList.remove('flex');
                document.body.style.overflow = 'auto';
            }
        });
    });
    function setupCharCounter(textarea, counterSpan) {
        function updateCounter() {
            const currentLength = textarea.value.length;
            const maxLength = parseInt(textarea.getAttribute('maxlength')) || 1000;
            counterSpan.textContent = `${currentLength}/${maxLength}`;
            if (currentLength > maxLength * 0.9) {
                counterSpan.classList.remove('text-gray-400');
                counterSpan.classList.add('text-red-600');
            } else if (currentLength > maxLength * 0.75) {
                counterSpan.classList.remove('text-gray-400', 'text-red-600');
                counterSpan.classList.add('text-yellow-600');
            } else {
                counterSpan.classList.remove('text-yellow-600', 'text-red-600');
                counterSpan.classList.add('text-gray-400');
            }
        }
        
        textarea.addEventListener('input', updateCounter);
        updateCounter();
    }
    document.querySelectorAll('textarea').forEach(textarea => {
        const counterSpan = textarea.parentElement.querySelector('.char-counter');
        if (counterSpan) {
            setupCharCounter(textarea, counterSpan);
        }
    });
    function setupFormValidation(formId, submitBtnId, loadingId) {
        const form = document.getElementById(formId);
        const submitBtn = document.getElementById(submitBtnId);
        const loadingSpan = document.getElementById(loadingId);
        
        if (form && submitBtn) {
            form.addEventListener('submit', function(e) {
                let isValid = true;
                let firstInvalidField = null;
                form.querySelectorAll('[required]').forEach(field => {
                    if (!field.value.trim()) {
                        isValid = false;
                        if (!firstInvalidField) firstInvalidField = field;
                        field.classList.add('border-red-500');
                        const errorMsg = document.createElement('p');
                        errorMsg.className = 'mt-2 text-sm text-red-600 flex items-center';
                        errorMsg.innerHTML = `<i class="fas fa-exclamation-circle mr-1"></i>Field ini wajib diisi`;
                        const existingError = field.parentElement.querySelector('.text-red-600');
                        if (existingError) existingError.remove();
                        
                        field.parentElement.appendChild(errorMsg);
                    } else {
                        field.classList.remove('border-red-500');
                        const existingError = field.parentElement.querySelector('.text-red-600');
                        if (existingError) existingError.remove();
                    }
                });
                form.querySelectorAll('[pattern]').forEach(field => {
                    const pattern = new RegExp(field.getAttribute('pattern'));
                    if (field.value && !pattern.test(field.value)) {
                        isValid = false;
                        if (!firstInvalidField) firstInvalidField = field;
                        
                        field.classList.add('border-red-500');
                        const errorMsg = document.createElement('p');
                        errorMsg.className = 'mt-2 text-sm text-red-600 flex items-center';
                        errorMsg.innerHTML = `<i class="fas fa-exclamation-circle mr-1"></i>${field.getAttribute('title')}`;
                        
                        const existingError = field.parentElement.querySelector('.text-red-600');
                        if (existingError) existingError.remove();
                        
                        field.parentElement.appendChild(errorMsg);
                    }
                });
                form.querySelectorAll('[maxlength]').forEach(field => {
                    const maxLength = parseInt(field.getAttribute('maxlength'));
                    if (field.value.length > maxLength) {
                        isValid = false;
                        if (!firstInvalidField) firstInvalidField = field;
                        
                        field.classList.add('border-red-500');
                        const errorMsg = document.createElement('p');
                        errorMsg.className = 'mt-2 text-sm text-red-600 flex items-center';
                        errorMsg.innerHTML = `<i class="fas fa-exclamation-circle mr-1"></i>Maksimal ${maxLength} karakter`;
                        
                        const existingError = field.parentElement.querySelector('.text-red-600');
                        if (existingError) existingError.remove();
                        
                        field.parentElement.appendChild(errorMsg);
                    }
                });
                
                if (!isValid) {
                    e.preventDefault();
                    e.stopPropagation();
                    if (firstInvalidField) {
                        firstInvalidField.scrollIntoView({ 
                            behavior: 'smooth', 
                            block: 'center' 
                        });
                        firstInvalidField.focus();
                    }
                    
                    return false;
                }
                submitBtn.disabled = true;
                if (loadingSpan) {
                    loadingSpan.classList.remove('hidden');
                }
                
                return true;
            });
        }
    }
    setupFormValidation('createTestForm', 'createSubmitBtn', 'createLoading');
    @if(isset($tests) && count($tests) > 0)
        @foreach($tests as $test)
            setupFormValidation('editTestForm{{ $test->id }}', 'editSubmitBtn{{ $test->id }}', 'editLoading{{ $test->id }}');
        @endforeach
    @endif
    @if(isset($tests) && count($tests) > 0)
        @foreach($tests as $test)
            @if(isset($test->id))
                const testMenu{{ $test->id }} = document.getElementById('testMenu{{ $test->id }}');
                const testDropdown{{ $test->id }} = document.getElementById('testDropdown{{ $test->id }}');
                
                if (testMenu{{ $test->id }} && testDropdown{{ $test->id }}) {
                    testMenu{{ $test->id }}.addEventListener('click', function(e) {
                        e.stopPropagation();
                        document.querySelectorAll('[id^="testDropdown"]').forEach(dropdown => {
                            if (dropdown !== testDropdown{{ $test->id }}) {
                                dropdown.classList.add('hidden');
                            }
                        });
                        testDropdown{{ $test->id }}.classList.toggle('hidden');
                    });
                    
                    document.addEventListener('click', function() {
                        testDropdown{{ $test->id }}.classList.add('hidden');
                    });
                    
                    testDropdown{{ $test->id }}.addEventListener('click', function(e) {
                        e.stopPropagation();
                    });
                }
            @endif
        @endforeach
    @endif
    setTimeout(() => {
        const successMsg = document.getElementById('success-message');
        const errorMsg = document.getElementById('error-message');
        if (successMsg) successMsg.remove();
        if (errorMsg) errorMsg.remove();
    }, 5000);
    @if(session('error_type'))
        @if(str_starts_with(session('error_type'), 'create'))
            const createModal = document.getElementById('createTestModal');
            if (createModal) {
                createModal.classList.remove('hidden');
                createModal.classList.add('flex');
                document.body.style.overflow = 'hidden';
            }
        @endif
        
        @foreach($tests ?? [] as $test)
            @if(session('error_type') == 'edit_' . $test->id)
                const editModal{{ $test->id }} = document.getElementById('editTestModal{{ $test->id }}');
                if (editModal{{ $test->id }}) {
                    editModal{{ $test->id }}.classList.remove('hidden');
                    editModal{{ $test->id }}.classList.add('flex');
                    document.body.style.overflow = 'hidden';
                }
            @endif
            
            @if(session('error_type') == 'delete_' . $test->id)
                const deleteModal{{ $test->id }} = document.getElementById('deleteTestModal{{ $test->id }}');
                if (deleteModal{{ $test->id }}) {
                    deleteModal{{ $test->id }}.classList.remove('hidden');
                    deleteModal{{ $test->id }}.classList.add('flex');
                    document.body.style.overflow = 'hidden';
                }
            @endif
        @endforeach
    @endif
});
function toggleDeleteButton(testId, testName) {
    const confirmInput = document.getElementById('deleteConfirm' + testId);
    const deleteBtn = document.getElementById('deleteSubmitBtn' + testId);
    
    if (confirmInput && deleteBtn) {
        if (confirmInput.value === testName) {
            deleteBtn.disabled = false;
            confirmInput.classList.remove('border-red-500');
            confirmInput.classList.add('border-green-500');
        } else {
            deleteBtn.disabled = true;
            confirmInput.classList.remove('border-green-500');
            confirmInput.classList.add('border-red-500');
        }
    }
}
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('.fixed.bg-black').forEach(modal => {
            if (!modal.classList.contains('hidden')) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = 'auto';
            }
        });
    }
});

document.querySelectorAll('textarea').forEach(textarea => {
    textarea.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && e.ctrlKey) {
            return true;
        }
    });
});
</script>

<style>
.required:after {
    content: " *";
    color: #ef4444;
}

@keyframes fadeIn {
    from { 
        opacity: 0; 
        transform: translateY(-20px); 
    }
    to { 
        opacity: 1; 
        transform: translateY(0); 
    }
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

.modal-animation {
    animation: fadeIn 0.3s ease-out;
}

.animate-fade-in {
    animation: fadeInUp 0.5s ease-out;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.hover-lift {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.hover-lift:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
}

.fixed.bg-black > div > div {
    max-height: 90vh;
    overflow-y: auto;
}

.fixed.bg-black > div > div::-webkit-scrollbar {
    width: 8px;
}

.fixed.bg-black > div > div::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

.fixed.bg-black > div > div::-webkit-scrollbar-thumb {
    background: #dc2626;
    border-radius: 4px;
}

.fixed.bg-black > div > div::-webkit-scrollbar-thumb:hover {
    background: #b91c1c;
}

.fa-spinner {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

@media (max-width: 640px) {
    .grid-cols-1 {
        grid-template-columns: 1fr;
    }
    
    .p-6 {
        padding: 1rem;
    }
    
    .text-3xl {
        font-size: 1.875rem;
    }
    
    .w-full.max-w-md {
        margin: 1rem;
        width: calc(100% - 2rem);
    }
}

@media print {
    .fixed,
    [data-modal-toggle],
    [data-modal-close],
    button {
        display: none !important;
    }
}
</style>
@endsection
