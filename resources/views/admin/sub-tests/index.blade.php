@extends('layouts.sidebar')

@section('page-title', 'Daftar Subtest')
@section('current-page', 'Subtest')
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
            <div class="min-w-0">
                <nav aria-label="Breadcrumb" class="mb-3 flex flex-wrap items-center gap-x-2 gap-y-1 text-xs font-medium text-rose-100 sm:text-sm">
                    <a href="{{ route('test.index') }}" class="hover:text-white">Tes</a>
                    <i class="fas fa-chevron-right text-[9px] opacity-70"></i>
                    <a href="{{ route('type-test.index', $test->slug) }}" class="max-w-[12rem] truncate hover:text-white">{{ $test->name }}</a>
                    <i class="fas fa-chevron-right text-[9px] opacity-70"></i>
                    <span class="max-w-[12rem] truncate text-white">{{ $typeTest->name }}</span>
                </nav>
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-rose-100">Kelola materi tes</p>
                <h1 class="mt-2 break-words text-2xl font-black tracking-tight sm:text-3xl lg:text-4xl">{{ $typeTest->name }}</h1>
                <p class="mt-2 text-sm leading-6 text-rose-50 sm:text-base">Atur subtest, durasi, dan kumpulan soal untuk kategori ini.</p>
            </div>
            <button type="button" class="inline-flex min-h-11 w-full shrink-0 items-center justify-center rounded-xl bg-white px-5 py-2.5 text-sm font-bold text-rose-700 shadow-md transition hover:-translate-y-0.5 hover:bg-rose-50 focus:outline-none focus:ring-2 focus:ring-white/80 sm:w-auto" data-modal-toggle="createListTestModal">
                <i class="fas fa-plus mr-2"></i> Tambah Subtest
            </button>
        </div>
    </div>
    @if($subTests->isEmpty())
    <div class="rounded-3xl border border-slate-200 bg-white px-5 py-10 text-center shadow-sm sm:px-8 sm:py-14">
        <div class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-2xl bg-rose-50 text-rose-600 ring-1 ring-rose-100 sm:h-20 sm:w-20">
            <i class="fas fa-list-alt text-2xl sm:text-3xl"></i>
        </div>
        <h3 class="text-xl font-extrabold text-slate-900 sm:text-2xl">Belum ada subtest</h3>
        <p class="mx-auto mb-7 mt-2 max-w-lg text-sm leading-6 text-slate-500 sm:text-base">
            Buat subtest untuk mengelompokkan soal berdasarkan topik atau tipe yang sama.
        </p>
        <button type="button"
            class="mx-auto inline-flex min-h-11 items-center justify-center rounded-xl bg-gradient-to-r from-rose-600 to-rose-700 px-6 py-3 font-bold text-white shadow-md transition hover:-translate-y-0.5 hover:from-rose-700 hover:to-rose-800"
            data-modal-toggle="createListTestModal">
            <i class="fas fa-plus mr-2"></i> Buat Subtest Pertama
        </button>
    </div>
    @else
    <div class="space-y-4">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="text-lg font-extrabold text-slate-900 sm:text-xl">Daftar Subtest</h2>
                <p class="mt-1 text-sm text-slate-500">Pilih subtest untuk mengelola soal atau rentang pengerjaan.</p>
            </div>
            <p class="text-sm font-semibold text-slate-500"><span class="text-slate-900">{{ $subTests->count() }}</span> subtest tersedia</p>
        </div>
        
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3 sm:gap-5">
            @foreach($subTests as $subTest)
            @php
                $questionCount = $subTest->questions_count ?? 0;
                $colors = [
                    'border-blue-200 hover:border-blue-300' => 'from-blue-50 to-blue-100',
                    'border-green-200 hover:border-green-300' => 'from-green-50 to-green-100',
                    'border-purple-200 hover:border-purple-300' => 'from-purple-50 to-purple-100',
                    'border-amber-200 hover:border-amber-300' => 'from-amber-50 to-amber-100',
                    'border-rose-200 hover:border-rose-300' => 'from-rose-50 to-rose-100'
                ];
                $colorIndex = $loop->index % count($colors);
                $borderColor = array_keys($colors)[$colorIndex];
                $bgColor = array_values($colors)[$colorIndex];
            @endphp
            
            <div class="group overflow-visible rounded-2xl border border-slate-200 bg-white shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:border-rose-200 hover:shadow-lg hover:shadow-slate-900/5">
                <div class="flex items-center justify-between rounded-t-2xl border-b border-slate-100 bg-gradient-to-r {{ $bgColor }} px-4 py-4 sm:px-5">
                    <div class="flex items-center min-w-0 flex-1">
                        <div class="mr-3 flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-rose-500 to-rose-700 shadow-sm">
                            <i class="fas fa-list text-white"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <h3 class="font-bold text-gray-900 truncate pr-2" title="{{ $subTest->name }}">
                                {{ $subTest->name }}
                            </h3>
                        </div>
                    </div>
                    <div class="relative flex-shrink-0">
                        <button type="button" aria-label="Aksi untuk {{ $subTest->name }}" class="rounded-xl p-2.5 text-slate-500 transition-colors duration-200 hover:bg-white/80 hover:text-rose-700 focus:outline-none focus:ring-2 focus:ring-rose-300" id="listMenu{{ $subTest->id }}">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <div id="listDropdown{{ $subTest->id }}" class="hidden absolute right-0 mt-2 w-48 rounded-xl border border-slate-200 bg-white py-2 shadow-xl z-30">
                            <a href="{{ route('question.index', [$test->slug, $typeTest->slug, $subTest->slug]) }}" 
                               class="flex items-center px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-600 transition-colors duration-150">
                                <i class="fas fa-eye mr-3 text-gray-400 w-4 text-center"></i>
                                <span>Lihat Soal</span>
                            </a>
                            <a href="#" 
                               data-modal-toggle="editModal{{ $subTest->id }}" 
                               class="flex items-center px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-600 transition-colors duration-150">
                                <i class="fas fa-edit mr-3 text-gray-400 w-4 text-center"></i>
                                <span>Update</span>
                            </a>
                            <div class="border-t border-gray-100 my-1"></div>
                            <a href="#" 
                               data-modal-toggle="deleteModal{{ $subTest->id }}" 
                               class="flex items-center px-4 py-2 text-red-600 hover:bg-red-50 transition-colors duration-150">
                                <i class="fas fa-trash mr-3 w-4 text-center"></i>
                                <span>Hapus</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="p-4 sm:p-5">
                    <div class="mb-4">
                        <p class="text-slate-600 text-sm leading-6 line-clamp-3">
                            {{ $subTest->description ?? 'Tidak ada deskripsi' }}
                        </p>
                    </div>
                    <div class="flex flex-wrap items-center justify-between gap-2 text-sm">
                        <span class="flex items-center font-medium text-slate-500">
                            <i class="far fa-clock mr-1"></i>
                            {{ $subTest->duration ?? 10 }} menit
                        </span>
                        <span class="rounded-full border border-rose-100 bg-rose-50 px-2.5 py-1 text-xs font-semibold text-rose-700">
                            {{ $questionCount }} soal
                        </span>
                    </div>
                </div>
                <div class="rounded-b-2xl border-t border-slate-100 bg-slate-50/70 px-4 py-3 sm:px-5">
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <a href="{{ route('question.index', [$test->slug, $typeTest->slug, $subTest->slug]) }}" 
                           class="flex items-center text-sm font-semibold text-rose-700 transition-transform group-hover:translate-x-0.5 hover:text-rose-800">
                            <span>Kelola Soal</span>
                            <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                        <a href="{{ route('sub-test.ranges.index', [
                            'test' => $test->slug,
                            'typeTest' => $typeTest->slug,
                            'subTest' => $subTest->slug
                        ]) }}"
                        class="flex items-center rounded-lg px-3 py-2 text-sm font-semibold text-slate-600 transition-colors hover:bg-rose-50 hover:text-rose-700">
                            <i class="fas fa-sliders-h mr-1.5"></i>
                            <span>Range</span>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
<div id="createListTestModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-950/60 p-3 backdrop-blur-sm sm:p-4">
    <div class="modal-animation max-h-[92vh] w-full max-w-md overflow-y-auto overscroll-contain rounded-2xl bg-white shadow-2xl">
        <form action="{{ route('sub-test.store', [$test->slug, $typeTest->slug]) }}" method="POST" id="createListTestForm" novalidate>
            @csrf
            <div class="px-6 py-4 border-b flex items-center justify-between">
                <h3 class="text-xl font-bold text-gray-900">Buat List Test Baru</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors" data-modal-close="createListTestModal">
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
                        Nama List Test
                    </label>
                    <input 
                        type="text" 
                        name="name" 
                        class="w-full px-4 py-3 border {{ session('error_type') == 'create' && $errors->has('name') ? 'border-red-500' : 'border-gray-300' }} rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all" 
                        placeholder="Contoh: Sinonim, Antonim, Matematika Dasar, dll."
                        required
                        value="{{ old('name', session('old_input.name') ?? '') }}"
                        maxlength="255"
                        minlength="3"
                        pattern="[a-zA-Z0-9\s\-]+"
                        title="Nama hanya boleh mengandung huruf, angka, spasi, dan tanda hubung"
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
                </div>
                <div class="mb-5">
                    <label class="block text-gray-700 text-sm font-medium mb-2">
                        Deskripsi <span class="text-gray-400">(Opsional)</span>
                    </label>
                    <textarea 
                        name="description" 
                        rows="3"
                        class="w-full px-4 py-3 border {{ session('error_type') == 'create' && $errors->has('description') ? 'border-red-500' : 'border-gray-300' }} rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all" 
                        placeholder="Deskripsi singkat tentang list test ini..."
                        maxlength="500"
                    >{{ old('description', session('old_input.description') ?? '') }}</textarea>
                    @if(session('error_type') == 'create' && $errors->has('description'))
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $errors->first('description') }}
                        </p>
                    @endif
                    <div class="flex justify-between items-center mt-1">
                        <p class="text-xs text-gray-500">
                            Maksimal 500 karakter
                        </p>
                        <span class="text-xs text-gray-400 char-counter" data-target="description">0/500</span>
                    </div>
                </div>
                <div class="mb-5">
                    <label class="block text-gray-700 text-sm font-medium mb-2">
                        Durasi <span class="text-gray-400">(Menit)</span>
                    </label>
                    <input 
                        type="number" 
                        name="duration" 
                        class="w-full px-4 py-3 border {{ session('error_type') == 'create' && $errors->has('duration') ? 'border-red-500' : 'border-gray-300' }} rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all" 
                        value="{{ old('duration', session('old_input.duration') ?? 10) }}"
                        min="1"
                        max="300"
                        step="1"
                    >
                    @if(session('error_type') == 'create' && $errors->has('duration'))
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $errors->first('duration') }}
                        </p>
                    @endif
                    <p class="mt-1 text-xs text-gray-500">
                        Durasi pengerjaan dalam menit (1-300 menit)
                    </p>
                </div>
            </div>
            
            <div class="px-6 py-4 border-t flex justify-end space-x-3">
                <button 
                    type="button" 
                    class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-all font-medium"
                    data-modal-close="createListTestModal"
                >
                    Batal
                </button>
                <button 
                    type="submit" 
                    class="px-5 py-2.5 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl hover:from-red-700 hover:to-red-800 transition-all font-medium shadow-md disabled:opacity-50 disabled:cursor-not-allowed"
                    id="createSubmitBtn"
                >
                    <span class="flex items-center">
                        <i class="fas fa-save mr-2"></i>
                        <span>Simpan List Test</span>
                        <span id="createLoading" class="hidden ml-2">
                            <i class="fas fa-spinner fa-spin"></i>
                        </span>
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>

@foreach($subTests as $subTest)
<div id="editModal{{ $subTest->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-950/60 p-3 backdrop-blur-sm sm:p-4">
    <div class="modal-animation max-h-[92vh] w-full max-w-md overflow-y-auto overscroll-contain rounded-2xl bg-white shadow-2xl">
        <form action="{{ route('sub-test.update', [$test->slug, $typeTest->slug, $subTest->id]) }}" method="POST" id="editListTestForm{{ $subTest->id }}" novalidate>
            @csrf
            @method('PUT')
            <div class="px-6 py-4 border-b flex items-center justify-between">
                <h3 class="text-xl font-bold text-gray-900">Update List Test</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors" data-modal-close="editModal{{ $subTest->id }}">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="p-6">
                @if(session('error_type') == 'edit_' . $subTest->id)
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
                        Nama List Test
                    </label>
                    <input 
                        type="text" 
                        name="name" 
                        class="w-full px-4 py-3 border {{ session('error_type') == 'edit_' . $subTest->id && $errors->has('name') ? 'border-red-500' : 'border-gray-300' }} rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all" 
                        value="{{ old('name', session('error_type') == 'edit_' . $subTest->id ? (session('old_input.name') ?? $subTest->name) : $subTest->name) }}"
                        required
                        maxlength="255"
                        minlength="3"
                        pattern="[a-zA-Z0-9\s\-]+"
                        title="Nama hanya boleh mengandung huruf, angka, spasi, dan tanda hubung"
                    >
                    @if(session('error_type') == 'edit_' . $subTest->id && $errors->has('name'))
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $errors->first('name') }}
                        </p>
                    @endif
                    <p class="mt-1 text-xs text-gray-500">
                        Minimal 3 karakter, maksimal 255 karakter. Tidak boleh mengandung simbol khusus.
                    </p>
                </div>
                
                <div class="mb-5">
                    <label class="block text-gray-700 text-sm font-medium mb-2">
                        Deskripsi <span class="text-gray-400">(Opsional)</span>
                    </label>
                    <textarea 
                        name="description" 
                        rows="3"
                        class="w-full px-4 py-3 border {{ session('error_type') == 'edit_' . $subTest->id && $errors->has('description') ? 'border-red-500' : 'border-gray-300' }} rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all"
                        maxlength="500"
                    >{{ old('description', session('error_type') == 'edit_' . $subTest->id ? (session('old_input.description') ?? $subTest->description) : $subTest->description) }}</textarea>
                    @if(session('error_type') == 'edit_' . $subTest->id && $errors->has('description'))
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $errors->first('description') }}
                        </p>
                    @endif
                    <div class="flex justify-between items-center mt-1">
                        <p class="text-xs text-gray-500">
                            Maksimal 500 karakter
                        </p>
                        <span class="text-xs text-gray-400 char-counter" data-target="description{{ $subTest->id }}">0/500</span>
                    </div>
                </div>
                
                <div class="mb-5">
                    <label class="block text-gray-700 text-sm font-medium mb-2">
                        Durasi <span class="text-gray-400">(Menit)</span>
                    </label>
                    <input 
                        type="number" 
                        name="duration" 
                        class="w-full px-4 py-3 border {{ session('error_type') == 'edit_' . $subTest->id && $errors->has('duration') ? 'border-red-500' : 'border-gray-300' }} rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all" 
                        value="{{ old('duration', session('error_type') == 'edit_' . $subTest->id ? (session('old_input.duration') ?? $subTest->duration) : $subTest->duration) }}"
                        min="1"
                        max="300"
                        step="1"
                    >
                    @if(session('error_type') == 'edit_' . $subTest->id && $errors->has('duration'))
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $errors->first('duration') }}
                        </p>
                    @endif
                    <p class="mt-1 text-xs text-gray-500">
                        Durasi pengerjaan dalam menit (1-300 menit)
                    </p>
                </div>
            </div>
            
            <div class="px-6 py-4 border-t flex justify-end space-x-3">
                <button 
                    type="button" 
                    class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-all font-medium"
                    data-modal-close="editModal{{ $subTest->id }}"
                >
                    Batal
                </button>
                <button 
                    type="submit" 
                    class="px-5 py-2.5 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl hover:from-red-700 hover:to-red-800 transition-all font-medium shadow-md disabled:opacity-50 disabled:cursor-not-allowed"
                    id="editSubmitBtn{{ $subTest->id }}"
                >
                    <span class="flex items-center">
                        <i class="fas fa-save mr-2"></i>
                        <span>Update List Test</span>
                        <span id="editLoading{{ $subTest->id }}" class="hidden ml-2">
                            <i class="fas fa-spinner fa-spin"></i>
                        </span>
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>

<div id="deleteModal{{ $subTest->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-950/60 p-3 backdrop-blur-sm sm:p-4">
    <div class="modal-animation max-h-[92vh] w-full max-w-md overflow-y-auto overscroll-contain rounded-2xl bg-white shadow-2xl">
        <form action="{{ route('sub-test.destroy', [$test->slug, $typeTest->slug, $subTest->id]) }}" method="POST" id="deleteListTestForm{{ $subTest->id }}">
            @csrf
            @method('DELETE')
            <div class="px-6 py-4 border-b flex items-center justify-between">
                <h3 class="text-xl font-bold text-gray-900">Hapus List Test</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors" data-modal-close="deleteModal{{ $subTest->id }}">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="p-6">
                @if(session('error_type') == 'delete_' . $subTest->id)
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
                
                <h4 class="text-lg font-semibold text-gray-900 text-center mb-2">Konfirmasi Hapus</h4>
                <p class="text-gray-600 text-center mb-2">
                    Anda akan menghapus list test <span class="font-bold">{{ $subTest->name }}</span>.
                </p>
                
                @php
                    $questionCount = $subTest->questions_count ?? 0;
                @endphp
                
                @if($questionCount > 0)
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
                                    <li>Terdapat {{ $questionCount }} soal di dalam list test ini</li>
                                    <li>Semua soal akan terhapus permanen</li>
                                    <li>Tindakan ini tidak dapat dibatalkan</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Ketik "<span class="font-bold text-red-600">{{ $subTest->name }}</span>" untuk konfirmasi
                    </label>
                    <input 
                        type="text" 
                        id="deleteConfirm{{ $subTest->id }}" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all"
                        placeholder="Ketik nama list test untuk konfirmasi"
                        onkeyup="toggleDeleteButton({{ $subTest->id }}, '{{ $subTest->name }}')"
                    >
                    <p class="mt-2 text-sm text-gray-500">
                        Tindakan ini untuk mencegah penghapusan yang tidak disengaja
                    </p>
                </div>
            </div>
            
            <div class="px-6 py-4 border-t flex justify-end space-x-3">
                <button 
                    type="button" 
                    class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-all font-medium"
                    data-modal-close="deleteModal{{ $subTest->id }}"
                >
                    Batal
                </button>
                <button 
                    type="submit" 
                    class="px-5 py-2.5 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-all font-medium shadow-md disabled:opacity-50 disabled:cursor-not-allowed"
                    id="deleteSubmitBtn{{ $subTest->id }}"
                    disabled
                >
                    <span class="flex items-center">
                        <i class="fas fa-trash mr-2"></i>
                        <span>Ya, Hapus Permanen</span>
                        <span id="deleteLoading{{ $subTest->id }}" class="hidden ml-2">
                            <i class="fas fa-spinner fa-spin"></i>
                        </span>
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>
@endforeach

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
            const maxLength = parseInt(textarea.getAttribute('maxlength')) || 500;
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

                form.querySelectorAll('input[type="number"]').forEach(field => {
                    const min = parseInt(field.getAttribute('min')) || 1;
                    const max = parseInt(field.getAttribute('max')) || 300;
                    const value = parseInt(field.value) || 0;
                    
                    if (value < min || value > max) {
                        isValid = false;
                        if (!firstInvalidField) firstInvalidField = field;
                        
                        field.classList.add('border-red-500');
                        const errorMsg = document.createElement('p');
                        errorMsg.className = 'mt-2 text-sm text-red-600 flex items-center';
                        errorMsg.innerHTML = `<i class="fas fa-exclamation-circle mr-1"></i>Nilai harus antara ${min} dan ${max}`;
                        
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

    setupFormValidation('createListTestForm', 'createSubmitBtn', 'createLoading');
    
    @foreach($subTests as $subTest)
        setupFormValidation('editListTestForm{{ $subTest->id }}', 'editSubmitBtn{{ $subTest->id }}', 'editLoading{{ $subTest->id }}');
    @endforeach

    @foreach($subTests as $subTest)
        const listMenu{{ $subTest->id }} = document.getElementById('listMenu{{ $subTest->id }}');
        const listDropdown{{ $subTest->id }} = document.getElementById('listDropdown{{ $subTest->id }}');
        
        if (listMenu{{ $subTest->id }} && listDropdown{{ $subTest->id }}) {
            listMenu{{ $subTest->id }}.addEventListener('click', function(e) {
                e.stopPropagation();
                document.querySelectorAll('[id^="listDropdown"]').forEach(dropdown => {
                    if (dropdown !== listDropdown{{ $subTest->id }}) {
                        dropdown.classList.add('hidden');
                    }
                });
                listDropdown{{ $subTest->id }}.classList.toggle('hidden');
            });
            
            document.addEventListener('click', function() {
                listDropdown{{ $subTest->id }}.classList.add('hidden');
            });
            
            listDropdown{{ $subTest->id }}.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        }
    @endforeach

    setTimeout(() => {
        const successMsg = document.getElementById('success-message');
        const errorMsg = document.getElementById('error-message');
        if (successMsg) successMsg.remove();
        if (errorMsg) errorMsg.remove();
    }, 5000);

    @if(session('error_type'))
        @if(str_starts_with(session('error_type'), 'create'))
            const createModal = document.getElementById('createListTestModal');
            if (createModal) {
                createModal.classList.remove('hidden');
                createModal.classList.add('flex');
                document.body.style.overflow = 'hidden';
            }
        @endif
        
        @foreach($subTests as $subTest)
            @if(session('error_type') == 'edit_' . $subTest->id)
                const editModal{{ $subTest->id }} = document.getElementById('editModal{{ $subTest->id }}');
                if (editModal{{ $subTest->id }}) {
                    editModal{{ $subTest->id }}.classList.remove('hidden');
                    editModal{{ $subTest->id }}.classList.add('flex');
                    document.body.style.overflow = 'hidden';
                }
            @endif
            
            @if(session('error_type') == 'delete_' . $subTest->id)
                const deleteModal{{ $subTest->id }} = document.getElementById('deleteModal{{ $subTest->id }}');
                if (deleteModal{{ $subTest->id }}) {
                    deleteModal{{ $subTest->id }}.classList.remove('hidden');
                    deleteModal{{ $subTest->id }}.classList.add('flex');
                    document.body.style.overflow = 'hidden';
                }
            @endif
        @endforeach
    @endif
});

function toggleDeleteButton(subTestId, subTestName) {
    const confirmInput = document.getElementById('deleteConfirm' + subTestId);
    const deleteBtn = document.getElementById('deleteSubmitBtn' + subTestId);
    
    if (confirmInput && deleteBtn) {
        if (confirmInput.value === subTestName) {
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
