@extends('layouts.sidebar')

@section('page-title', 'Kategori Tes')
@section('current-page', 'Kategori')
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
                <nav aria-label="Breadcrumb" class="mb-3 flex items-center gap-2 text-xs font-medium text-rose-100 sm:text-sm">
                    <a href="{{ route('test.index') }}" class="hover:text-white">Daftar Tes</a>
                    <i class="fas fa-chevron-right text-[9px] opacity-70"></i>
                    <span class="max-w-[16rem] truncate text-white">{{ $test->name }}</span>
                </nav>
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-rose-100">Kelola kategori tes</p>
                <h1 class="mt-2 break-words text-2xl font-black tracking-tight sm:text-3xl lg:text-4xl">{{ $test->name }}</h1>
                <p class="mt-2 text-sm leading-6 text-rose-50 sm:text-base">Atur kategori, petunjuk, dan kumpulan subtest untuk tes ini.</p>
            </div>
            <button type="button" class="inline-flex min-h-11 w-full shrink-0 items-center justify-center rounded-xl bg-white px-5 py-2.5 text-sm font-bold text-rose-700 shadow-md transition hover:-translate-y-0.5 hover:bg-rose-50 focus:outline-none focus:ring-2 focus:ring-white/80 sm:w-auto"
                data-modal-toggle="createTypeTestModal">
                <i class="fas fa-plus mr-2"></i> Tambah Kategori
            </button>
        </div>
    </div>
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 px-4 py-4 sm:px-6 sm:py-5">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="mr-3 flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-rose-50 text-rose-700 ring-1 ring-rose-100 sm:mr-4 sm:h-12 sm:w-12">
                        <i class="fas fa-info-circle text-lg sm:text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900 sm:text-lg">Informasi Tes</h3>
                        <p class="text-xs text-slate-500 sm:text-sm">Detail, peraturan, dan instruksi</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="p-4 sm:p-6">
            <div class="mb-6">
                <div class="text-gray-700">
                    @if($test->rules_formatted)
                        <ul class="space-y-2 sm:ml-2">
                            @foreach($test->rules_formatted as $rule)
                                <li class="flex items-start">
                                    <span class="mr-2 mt-0.5 text-rose-600">•</span>
                                    <span class="text-sm leading-6 text-slate-700 sm:text-base">{{ $rule }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-sm italic text-slate-500">Tidak ada peraturan tersedia.</p>
                    @endif
                </div>
            </div>

            @if($test->instructions_formatted)
            <div class="mt-6">
                <h4 class="mb-3 font-bold text-slate-900">Instruksi</h4>
                <div class="rounded-xl border border-amber-100 bg-amber-50/70 p-4 sm:p-5">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-amber-600 text-lg sm:text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <ol class="list-decimal space-y-2 pl-5 text-sm leading-6 text-slate-700 sm:text-base">
                                @foreach($test->instructions_formatted as $instruction)
                                    <li>{{ $instruction }}</li>
                                @endforeach
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    @if($typeTests->isEmpty())
    <div class="rounded-3xl border border-slate-200 bg-white px-5 py-10 text-center shadow-sm sm:px-8 sm:py-14">
        <div class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-2xl bg-rose-50 text-rose-600 ring-1 ring-rose-100 sm:h-20 sm:w-20">
            <i class="fas fa-layer-group text-2xl sm:text-3xl"></i>
        </div>
        <h3 class="text-xl font-extrabold text-slate-900 sm:text-2xl">Belum ada kategori tes</h3>
        <p class="mx-auto mb-7 mt-2 max-w-lg text-sm leading-6 text-slate-500 sm:text-base">
            Buat kategori untuk mengelompokkan subtest dan soal berdasarkan bagian tes.
        </p>
        <button type="button"
            class="mx-auto inline-flex min-h-11 items-center justify-center rounded-xl bg-gradient-to-r from-rose-600 to-rose-700 px-6 py-3 font-bold text-white shadow-md transition hover:-translate-y-0.5 hover:from-rose-700 hover:to-rose-800"
            data-modal-toggle="createTypeTestModal">
            <i class="fas fa-plus mr-2"></i> Buat Kategori Pertama
        </button>
    </div>
    @else
    <div class="space-y-4">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="text-lg font-extrabold text-slate-900 sm:text-xl">Kategori Tes</h2>
                <p class="mt-1 text-sm text-slate-500">Pilih kategori untuk mengelola subtest dan soal.</p>
            </div>
            <p class="text-sm font-semibold text-slate-500"><span class="text-slate-900">{{ $typeTests->count() }}</span> kategori tersedia</p>
        </div>
        
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3 sm:gap-5">
            @foreach($typeTests as $type)
            <div class="overflow-visible rounded-2xl border border-slate-200 bg-white shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:border-rose-200 hover:shadow-lg hover:shadow-slate-900/5">
                <div class="flex items-center justify-between rounded-t-2xl border-b border-slate-100 bg-gradient-to-r from-rose-50 to-white px-4 py-4 sm:px-5">
                    <div class="flex items-center">
                        <div class="mr-3 flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-rose-500 to-rose-700 shadow-sm">
                            <i class="fas fa-cube text-white"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900">{{ $type->name }}</h3>
                        </div>
                    </div>
                    <div class="relative">
                        <button type="button" aria-label="Aksi untuk {{ $type->name }}" class="rounded-xl p-2.5 text-slate-500 transition-colors hover:bg-white hover:text-rose-700 focus:outline-none focus:ring-2 focus:ring-rose-300" id="typeMenu{{ $type->id }}">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>

                        <div id="typeDropdown{{ $type->id }}" class="hidden absolute right-0 mt-2 w-48 rounded-xl border border-slate-200 bg-white py-2 shadow-xl z-30">
                            <a href="{{ route('sub-test.index', [$test->slug, $type->slug]) }}" 
                               class="flex items-center px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-600 transition-colors duration-150">
                                <i class="fas fa-eye mr-3 text-gray-400 w-4 text-center"></i>
                                <span>Lihat Soal</span>
                            </a>
                            <a href="#" 
                               data-modal-toggle="editModal{{ $type->id }}" 
                               class="flex items-center px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-600 transition-colors duration-150">
                                <i class="fas fa-edit mr-3 text-gray-400 w-4 text-center"></i>
                                <span>Update</span>
                            </a>
                            <div class="border-t border-gray-100 my-1"></div>
                            <a href="#" 
                               data-modal-toggle="deleteModal{{ $type->id }}" 
                               class="flex items-center px-4 py-2 text-red-600 hover:bg-red-50 transition-colors duration-150">
                                <i class="fas fa-trash mr-3 w-4 text-center"></i>
                                <span>Hapus</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="p-4 sm:p-5">
                    <div class="mb-4">
                        <p class="text-sm leading-6 text-slate-600 line-clamp-3">
                            {{ $type->description ?? 'Tidak ada deskripsi' }}
                        </p>
                    </div>
                    <div class="flex flex-wrap items-center justify-between gap-2 text-sm">
                        <span class="text-slate-500">
                            <i class="far fa-calendar mr-1"></i>
                            {{ $type->created_at->format('d M Y') }}
                        </span>
                        <span class="rounded-full border border-rose-100 bg-rose-50 px-2.5 py-1 text-xs font-semibold text-rose-700">
                            {{ $type->subTests->count() }} sub tes
                        </span>
                    </div>
                </div>

                <div class="rounded-b-2xl border-t border-slate-100 bg-slate-50/70 px-4 py-3 sm:px-5">
                    <a href="{{ route('sub-test.index', [$test->slug, $type->slug]) }}" 
                       class="flex items-center justify-between text-sm font-semibold text-rose-700 hover:text-rose-800 group">
                        <span>Kelola Soal</span>
                        <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<div id="createTypeTestModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-950/60 p-3 backdrop-blur-sm sm:p-4">
    <div class="modal-animation max-h-[92vh] w-full max-w-md overflow-y-auto overscroll-contain rounded-2xl bg-white shadow-2xl">
        <form action="{{ route('type-test.store', $test->slug) }}" method="POST" id="createTypeTestForm" novalidate>
            @csrf
            <div class="px-6 py-4 border-b flex items-center justify-between">
                <h3 class="text-xl font-bold text-gray-900">Buat Sub Test Baru</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors" data-modal-close="createTypeTestModal">
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
                        Nama Sub Test
                    </label>
                    <input 
                        type="text" 
                        name="name" 
                        class="w-full px-4 py-3 border {{ session('error_type') == 'create' && $errors->has('name') ? 'border-red-500' : 'border-gray-300' }} rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all" 
                        placeholder="Contoh: Test Verbal, Matematika Dasar, dll."
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
                    <label class="block text-gray-700 text-sm font-medium mb-2 required">
                        Deskripsi
                    </label>
                    <textarea 
                        name="description" 
                        rows="4"
                        class="w-full px-4 py-3 border {{ session('error_type') == 'create' && $errors->has('description') ? 'border-red-500' : 'border-gray-300' }} rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all" 
                        placeholder="Jelaskan tujuan dan konten dari sub test ini..."
                        required
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
            </div>
            
            <div class="px-6 py-4 border-t flex justify-end space-x-3">
                <button 
                    type="button" 
                    class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-all font-medium"
                    data-modal-close="createTypeTestModal"
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
                        <span>Simpan Sub Test</span>
                        <span id="createLoading" class="hidden ml-2">
                            <i class="fas fa-spinner fa-spin"></i>
                        </span>
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>

@foreach($typeTests as $type)
<div id="editModal{{ $type->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-950/60 p-3 backdrop-blur-sm sm:p-4">
    <div class="modal-animation max-h-[92vh] w-full max-w-md overflow-y-auto overscroll-contain rounded-2xl bg-white shadow-2xl">
        <form action="{{ route('type-test.update', [$test->slug, $type->slug]) }}" method="POST" id="editTypeTestForm{{ $type->id }}" novalidate>
            @csrf
            @method('PUT')
            <div class="px-6 py-4 border-b flex items-center justify-between">
                <h3 class="text-xl font-bold text-gray-900">Edit Sub Test</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors" data-modal-close="editModal{{ $type->id }}">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="p-6">
                @if(session('error_type') == 'edit_' . $type->id)
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
                        Nama Sub Test
                    </label>
                    <input 
                        type="text" 
                        name="name" 
                        class="w-full px-4 py-3 border {{ session('error_type') == 'edit_' . $type->id && $errors->has('name') ? 'border-red-500' : 'border-gray-300' }} rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all" 
                        value="{{ old('name', session('error_type') == 'edit_' . $type->id ? (session('old_input.name') ?? $type->name) : $type->name) }}"
                        required
                        maxlength="255"
                        minlength="3"
                        pattern="[a-zA-Z0-9\s\-]+"
                        title="Nama hanya boleh mengandung huruf, angka, spasi, dan tanda hubung"
                    >
                    @if(session('error_type') == 'edit_' . $type->id && $errors->has('name'))
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
                    <label class="block text-gray-700 text-sm font-medium mb-2 required">
                        Deskripsi
                    </label>
                    <textarea 
                        name="description" 
                        rows="4"
                        class="w-full px-4 py-3 border {{ session('error_type') == 'edit_' . $type->id && $errors->has('description') ? 'border-red-500' : 'border-gray-300' }} rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all"
                        required
                        maxlength="1000"
                    >{{ old('description', session('error_type') == 'edit_' . $type->id ? (session('old_input.description') ?? $type->description) : $type->description) }}</textarea>
                    @if(session('error_type') == 'edit_' . $type->id && $errors->has('description'))
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $errors->first('description') }}
                        </p>
                    @endif
                    <div class="flex justify-between items-center mt-1">
                        <p class="text-xs text-gray-500">
                            Maksimal 1000 karakter
                        </p>
                        <span class="text-xs text-gray-400 char-counter" data-target="description{{ $type->id }}">0/1000</span>
                    </div>
                </div>
            </div>
            
            <div class="px-6 py-4 border-t flex justify-end space-x-3">
                <button 
                    type="button" 
                    class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-all font-medium"
                    data-modal-close="editModal{{ $type->id }}"
                >
                    Batal
                </button>
                <button 
                    type="submit" 
                    class="px-5 py-2.5 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl hover:from-red-700 hover:to-red-800 transition-all font-medium shadow-md disabled:opacity-50 disabled:cursor-not-allowed"
                    id="editSubmitBtn{{ $type->id }}"
                >
                    <span class="flex items-center">
                        <i class="fas fa-save mr-2"></i>
                        <span>Update Sub Test</span>
                        <span id="editLoading{{ $type->id }}" class="hidden ml-2">
                            <i class="fas fa-spinner fa-spin"></i>
                        </span>
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>

<div id="deleteModal{{ $type->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-950/60 p-3 backdrop-blur-sm sm:p-4">
    <div class="modal-animation max-h-[92vh] w-full max-w-md overflow-y-auto overscroll-contain rounded-2xl bg-white shadow-2xl">
        <form action="{{ route('type-test.destroy', [$test->slug, $type->slug]) }}" method="POST" id="deleteTypeTestForm{{ $type->id }}">
            @csrf
            @method('DELETE')
            <div class="px-6 py-4 border-b flex items-center justify-between">
                <h3 class="text-xl font-bold text-gray-900">Hapus Sub Test</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors" data-modal-close="deleteModal{{ $type->id }}">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="p-6">
                @if(session('error_type') == 'delete_' . $type->id)
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
                    Anda akan menghapus sub test <span class="font-bold">{{ $type->name }}</span>.
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
                                    <li>Semua soal terkait akan dihapus</li>
                                    <li>Histori attempt dari sub test ini akan hilang</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Ketik "<span class="font-bold text-red-600">{{ $type->name }}</span>" untuk konfirmasi
                    </label>
                    <input 
                        type="text" 
                        id="deleteConfirm{{ $type->id }}" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all"
                        placeholder="Ketik nama sub test untuk konfirmasi"
                        onkeyup="toggleDeleteButton({{ $type->id }}, '{{ $type->name }}')"
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
                    data-modal-close="deleteModal{{ $type->id }}"
                >
                    Batal
                </button>
                <button 
                    type="submit" 
                    class="px-5 py-2.5 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-all font-medium shadow-md disabled:opacity-50 disabled:cursor-not-allowed"
                    id="deleteSubmitBtn{{ $type->id }}"
                    disabled
                >
                    <span class="flex items-center">
                        <i class="fas fa-trash mr-2"></i>
                        <span>Ya, Hapus Permanen</span>
                        <span id="deleteLoading{{ $type->id }}" class="hidden ml-2">
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

    setupFormValidation('createTypeTestForm', 'createSubmitBtn', 'createLoading');
    
    @foreach($typeTests as $type)
        setupFormValidation('editTypeTestForm{{ $type->id }}', 'editSubmitBtn{{ $type->id }}', 'editLoading{{ $type->id }}');
    @endforeach

    @foreach($typeTests as $type)
        const typeMenu{{ $type->id }} = document.getElementById('typeMenu{{ $type->id }}');
        const typeDropdown{{ $type->id }} = document.getElementById('typeDropdown{{ $type->id }}');
        
        if (typeMenu{{ $type->id }} && typeDropdown{{ $type->id }}) {
            typeMenu{{ $type->id }}.addEventListener('click', function(e) {
                e.stopPropagation();
                document.querySelectorAll('[id^="typeDropdown"]').forEach(dropdown => {
                    if (dropdown !== typeDropdown{{ $type->id }}) {
                        dropdown.classList.add('hidden');
                    }
                });
                typeDropdown{{ $type->id }}.classList.toggle('hidden');
            });
            
            document.addEventListener('click', function() {
                typeDropdown{{ $type->id }}.classList.add('hidden');
            });
            
            typeDropdown{{ $type->id }}.addEventListener('click', function(e) {
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
            const createModal = document.getElementById('createTypeTestModal');
            if (createModal) {
                createModal.classList.remove('hidden');
                createModal.classList.add('flex');
                document.body.style.overflow = 'hidden';
            }
        @endif
        
        @foreach($typeTests as $type)
            @if(session('error_type') == 'edit_' . $type->id)
                const editModal{{ $type->id }} = document.getElementById('editModal{{ $type->id }}');
                if (editModal{{ $type->id }}) {
                    editModal{{ $type->id }}.classList.remove('hidden');
                    editModal{{ $type->id }}.classList.add('flex');
                    document.body.style.overflow = 'hidden';
                }
            @endif
            
            @if(session('error_type') == 'delete_' . $type->id)
                const deleteModal{{ $type->id }} = document.getElementById('deleteModal{{ $type->id }}');
                if (deleteModal{{ $type->id }}) {
                    deleteModal{{ $type->id }}.classList.remove('hidden');
                    deleteModal{{ $type->id }}.classList.add('flex');
                    document.body.style.overflow = 'hidden';
                }
            @endif
        @endforeach
    @endif
});

function toggleDeleteButton(typeId, typeName) {
    const confirmInput = document.getElementById('deleteConfirm' + typeId);
    const deleteBtn = document.getElementById('deleteSubmitBtn' + typeId);
    
    if (confirmInput && deleteBtn) {
        if (confirmInput.value === typeName) {
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
