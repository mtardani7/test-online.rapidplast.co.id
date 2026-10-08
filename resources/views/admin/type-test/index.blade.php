@extends('layouts.sidebar')

@section('content')
<div class="p-6">
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
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div class="flex items-center space-x-2 text-sm text-gray-600 mb-2">
            <a href="{{ route('test.index') }}" class="hover:text-red-600 transition-colors">Test</a>
            <span>/</span>
            <span class="text-gray-900 font-medium">{{ $test->name }}</span>
        </div>
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $test->name }}</h1>
            <p class="text-gray-600 mt-2">Kelola sub test untuk test ini</p>
        </div>
        <button 
            class="bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white px-6 py-3 rounded-xl font-semibold shadow-md hover:shadow-lg transition-all flex items-center"
            data-modal-toggle="createTypeTestModal">
            <i class="fas fa-plus mr-2"></i> Tambah Sub Test
        </button>
    </div>
    <div class="bg-gradient-to-r from-red-50 to-white rounded-2xl shadow-md border border-red-100 mb-8 overflow-hidden">
        <div class="px-6 py-5 border-b border-red-100">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-red-600 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-info-circle text-white text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Informasi Test</h3>
                        <p class="text-gray-600 text-sm">Detail dan peraturan untuk test ini</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="p-6">
            <div class="mb-6">
                <div class="text-gray-700">
                    @if($test->rules_formatted)
                        <ul class="space-y-2 ml-4">
                            @foreach($test->rules_formatted as $rule)
                                <li class="flex items-start">
                                    <span class="text-red-600 mr-2">•</span>
                                    <span>{{ $rule }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-gray-500 italic">Tidak ada peraturan tersedia.</p>
                    @endif
                </div>
            </div>

            @if($test->instructions_formatted)
            <div class="mt-6">
                <h4 class="font-semibold text-gray-900 mb-3">Instruksi</h4>
                <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-r-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-yellow-500 text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <ol class="list-decimal pl-5 space-y-2 text-gray-800">
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
    <div class="bg-white rounded-2xl shadow-md p-12 text-center">
        <div class="w-24 h-24 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-6">
            <i class="fas fa-layer-group text-3xl text-gray-400"></i>
        </div>
        <h3 class="text-2xl font-bold text-gray-700 mb-3">Belum Ada Sub Test</h3>
        <p class="text-gray-500 mb-8 max-w-md mx-auto">
            Anda belum membuat sub test untuk test ini. Sub test digunakan untuk mengelompokkan soal berdasarkan kategori tertentu.
        </p>
        <button 
            class="bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white px-8 py-3 rounded-xl font-semibold shadow-md hover:shadow-lg transition-all flex items-center mx-auto"
            data-modal-toggle="createTypeTestModal">
            <i class="fas fa-plus mr-2"></i> Buat Sub Test Pertama
        </button>
    </div>
    @else
    <div class="mb-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Daftar Sub Test</h2>
            <p class="text-gray-600 text-sm">{{ $typeTests->count() }} sub test tersedia</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($typeTests as $type)
            <div class="bg-white rounded-2xl shadow-md border border-gray-200 hover:border-red-300 transition-all duration-300 hover:shadow-lg overflow-hidden">
                <div class="px-5 py-4 border-b bg-gradient-to-r from-gray-50 to-white flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-r from-red-500 to-red-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-cube text-white"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900">{{ $type->name }}</h3>
                        </div>
                    </div>
                    <div class="relative">
                        <button class="text-gray-400 hover:text-gray-600 p-2 rounded-lg hover:bg-gray-100" id="typeMenu{{ $type->id }}">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>

                        <div id="typeDropdown{{ $type->id }}" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border py-2 z-10">
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

                <div class="p-5">
                    <div class="mb-4">
                        <p class="text-gray-600 text-sm line-clamp-3 text-justify">
                            {{ $type->description ?? 'Tidak ada deskripsi' }}
                        </p>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">
                            <i class="far fa-calendar mr-1"></i>
                            {{ $type->created_at->format('d M Y') }}
                        </span>
                        <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full">
                            {{ $type->subTests->count() }} sub tes
                        </span>
                    </div>
                </div>

                <div class="px-5 py-3 bg-gray-50 border-t">
                    <a href="{{ route('sub-test.index', [$test->slug, $type->slug]) }}" 
                       class="text-red-600 hover:text-red-700 font-medium text-sm flex items-center justify-between group">
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

<div id="createTypeTestModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-md modal-animation">
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
<div id="editModal{{ $type->id }}" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-md modal-animation">
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

<div id="deleteModal{{ $type->id }}" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-md modal-animation">
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