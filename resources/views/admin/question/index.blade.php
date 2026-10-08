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
        <div>
            <div class="flex items-center space-x-2 text-sm text-gray-600 mb-2">
                <a href="{{ route('test.index') }}" class="hover:text-red-600 transition-colors">Test</a>
                <span>/</span>
                <a href="{{ route('type-test.index', $test->slug) }}" class="hover:text-red-600 transition-colors">{{ $test->name }}</a>
                <span>/</span>
                <a href="{{ route('sub-test.index', [$test->slug, $typeTest->slug]) }}" class="hover:text-red-600 transition-colors">{{ $typeTest->name }}</a>
                <span>/</span>
                <span class="text-gray-900 font-medium">{{ $subTest->name }}</span>
            </div>
            <h1 class="text-3xl font-bold text-gray-900">Manajemen Pertanyaan</h1>
            <p class="text-gray-600 mt-2">{{ $subTest->name }} - Kelola soal dan pilihan jawaban</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('sub-test.index', [$test->slug, $typeTest->slug]) }}" 
               class="px-4 py-2.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-all flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
            <button 
                class="bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white px-6 py-2.5 rounded-xl font-semibold shadow-md hover:shadow-lg transition-all flex items-center"
                data-modal-toggle="addModal">
                <i class="fas fa-plus mr-2"></i> Tambah Pertanyaan
            </button>
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow p-5 transition-all hover:shadow-md">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-question-circle text-blue-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Total Pertanyaan</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalQuestions ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow p-5 transition-all hover:shadow-md">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-image text-green-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Dengan Gambar</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $questionsWithImage ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow p-5 transition-all hover:shadow-md">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-list-alt text-purple-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Rata-rata Opsi</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $avgChoices ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-md overflow-hidden mb-6">
        <div class="px-6 py-4 border-b">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Daftar Pertanyaan</h2>
                    <p class="text-gray-600 text-sm mt-1">Kelola semua pertanyaan untuk test ini</p>
                </div>
                <div class="flex items-center justify-between md:justify-end w-full md:w-auto">
                    <span class="text-sm text-gray-500 mr-4">
                        {{ $questions->total() }} total soal
                    </span>
                    <button 
                        id="filterToggle" 
                        class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 text-sm flex items-center"
                    >
                        <i class="fas fa-filter mr-2"></i> Filter
                    </button>
                </div>
            </div>
            <div id="filterForm" class="hidden mt-4 pt-4 border-t">
                <form method="GET" action="{{ route('question.index', [$test->slug, $typeTest->slug, $subTest->slug]) }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-2">Cari Pertanyaan</label>
                        <div class="relative">
                            <input 
                                type="text" 
                                name="search" 
                                value="{{ request('search') }}"
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all text-sm"
                                placeholder="Cari pertanyaan atau opsi..."
                            >
                            <i class="fas fa-search absolute left-3 top-3.5 text-gray-400"></i>
                        </div>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-2">Filter Gambar</label>
                        <select name="has_image" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all text-sm">
                            <option value="">Semua</option>
                            <option value="yes" {{ request('has_image') == 'yes' ? 'selected' : '' }}>Dengan Gambar</option>
                            <option value="no" {{ request('has_image') == 'no' ? 'selected' : '' }}>Tanpa Gambar</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-2">Jawaban Benar</label>
                        <select name="has_correct" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all text-sm">
                            <option value="">Semua</option>
                            <option value="yes" {{ request('has_correct') == 'yes' ? 'selected' : '' }}>Sudah ada jawaban</option>
                            <option value="no" {{ request('has_correct') == 'no' ? 'selected' : '' }}>Belum ada jawaban</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-2">Urutkan</label>
                        <select name="sort_by" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all text-sm">
                            <option value="created_at" {{ request('sort_by', 'created_at') == 'created_at' ? 'selected' : '' }}>Tanggal Dibuat</option>
                            <option value="question_text" {{ request('sort_by') == 'question_text' ? 'selected' : '' }}>Pertanyaan A-Z</option>
                        </select>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="md:col-span-4 flex items-center justify-end space-x-3 pt-2">
                        <a href="{{ route('question.index', [$test->slug, $typeTest->slug, $subTest->slug]) }}" 
                           class="px-4 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all text-sm">
                            Reset Filter
                        </a>
                        <button type="submit" class="px-4 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-all text-sm">
                            Terapkan Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @if($questions->isEmpty())
        <div class="py-12 text-center">
            <div class="w-24 h-24 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-6">
                <i class="fas fa-question-circle text-3xl text-gray-400"></i>
            </div>
            <h4 class="text-xl font-semibold text-gray-700 mb-2">Belum Ada Pertanyaan</h4>
            <p class="text-gray-500 mb-6">Mulai dengan menambahkan pertanyaan pertama</p>
            <button 
                class="bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white px-6 py-3 rounded-xl font-semibold shadow-md hover:shadow-lg transition-all flex items-center mx-auto"
                data-modal-toggle="addModal">
                <i class="fas fa-plus mr-2"></i> Tambah Pertanyaan
            </button>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b">
                        <th class="py-4 px-6 text-left text-gray-700 font-semibold text-sm w-16">#</th>
                        <th class="py-4 px-6 text-left text-gray-700 font-semibold text-sm">Pertanyaan</th>
                        <th class="py-4 px-6 text-left text-gray-700 font-semibold text-sm">Keterangan</th>
                        <th class="py-4 px-6 text-left text-gray-700 font-semibold text-sm w-32">Gambar</th>
                        <th class="py-4 px-6 text-left text-gray-700 font-semibold text-sm w-40">Opsi Jawaban</th>
                        <th class="py-4 px-6 text-left text-gray-700 font-semibold text-sm w-48">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($questions as $q)
                    @php
                        $hasImage = !empty($q->image);
                        $optionsCount = $q->choices->count();
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="py-4 px-6">
                            <div class="flex items-center justify-center w-8 h-8 bg-gray-100 rounded-lg font-semibold text-gray-700">
                                {{ ($questions->currentPage() - 1) * $questions->perPage() + $loop->iteration }}
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="max-w-md">
                                <p class="font-medium text-gray-900 line-clamp-2">{{ $q->question_text ?? '-' }}</p>
                                @if($q->correct_option_id)
                                <div class="flex items-center mt-2">
                                    <i class="fas fa-check-circle text-green-500 text-xs mr-2"></i>
                                    <span class="text-green-600 text-xs font-medium">Sudah ada jawaban benar</span>
                                </div>
                                @endif
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <p class="text-gray-600 text-sm line-clamp-2">{{ $q->question_optional ?? '-' }}</p>
                        </td>
                        <td class="py-4 px-6">
                            @if($hasImage)
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-image text-blue-600"></i>
                            </div>
                            @else
                            <span class="text-gray-400 text-sm">-</span>
                            @endif
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    <i class="fas fa-list-ol mr-1"></i>
                                    {{ $optionsCount }} opsi
                                </span>
                                <button 
                                    class="ml-3 text-purple-600 hover:text-purple-800 transition-colors"
                                    data-modal-toggle="detailModal{{ $q->id }}"
                                    title="Lihat detail opsi">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center space-x-2">
                                <button 
                                    class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                    data-modal-toggle="editModal{{ $q->id }}"
                                    title="Edit pertanyaan">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button 
                                    class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors"
                                    data-modal-toggle="detailModal{{ $q->id }}"
                                    title="Lihat detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <form 
                                    action="{{ route('question.destroy', [$test->slug, $typeTest->slug, $subTest->slug, $q->id]) }}"
                                    method="POST" 
                                    class="inline delete-form"
                                    data-confirm-delete="true"
                                    data-item-name="{{ Str::limit($q->question_text, 50) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button 
                                        type="submit"
                                        class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors delete-btn"
                                        title="Hapus pertanyaan">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($questions->hasPages())
        <div class="px-6 py-4 border-t">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <div class="text-sm text-gray-700 mb-4 md:mb-0">
                    Menampilkan <span class="font-medium">{{ $questions->firstItem() }}</span> - 
                    <span class="font-medium">{{ $questions->lastItem() }}</span> dari 
                    <span class="font-medium">{{ $questions->total() }}</span> pertanyaan
                </div>
                
                <nav class="flex items-center space-x-2">
                    {{ $questions->onEachSide(1)->links('vendor.pagination.custom') }}
                </nav>
            </div>
        </div>
        @endif
        @endif
    </div>
</div>

<div id="addModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-3xl max-h-[90vh] overflow-hidden modal-animation">
        <form action="{{ route('question.store', [$test->slug, $typeTest->slug, $subTest->slug]) }}" 
              method="POST" enctype="multipart/form-data" id="createQuestionForm" novalidate>
            @csrf
            <div class="px-6 py-4 border-b flex items-center justify-between">
                <h3 class="text-xl font-bold text-gray-900">Tambah Pertanyaan Baru</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors" data-modal-close="addModal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="overflow-y-auto p-6" style="max-height: calc(90vh - 120px);">
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
                <div class="mb-6">
                    <h4 class="font-semibold text-gray-900 mb-4">Detail Pertanyaan</h4>
                    <div class="mb-5">
                        <label class="block text-gray-700 text-sm font-medium mb-2 required">
                            Pertanyaan
                        </label>
                        <textarea 
                            name="question_text" 
                            rows="3"
                            class="w-full px-4 py-3 border {{ session('error_type') == 'create' && $errors->has('question_text') ? 'border-red-500' : 'border-gray-300' }} rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all" 
                            placeholder="Masukkan pertanyaan..."
                            required
                            maxlength="1000"
                        >{{ old('question_text', session('old_input.question_text') ?? '') }}</textarea>
                        @if(session('error_type') == 'create' && $errors->has('question_text'))
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $errors->first('question_text') }}
                            </p>
                        @endif
                        <div class="flex justify-between items-center mt-1">
                            <p class="text-xs text-gray-500">
                                Maksimal 1000 karakter
                            </p>
                            <span class="text-xs text-gray-400 char-counter" data-target="question_text">0/1000</span>
                        </div>
                    </div>
                    <div class="mb-5">
                        <label class="block text-gray-700 text-sm font-medium mb-2">
                            Keterangan <span class="text-gray-400">(Opsional)</span>
                        </label>
                        <input 
                            type="text" 
                            name="question_optional" 
                            class="w-full px-4 py-3 border {{ session('error_type') == 'create' && $errors->has('question_optional') ? 'border-red-500' : 'border-gray-300' }} rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all" 
                            placeholder="Keterangan tambahan..."
                            value="{{ old('question_optional', session('old_input.question_optional') ?? '') }}"
                            maxlength="500"
                        >
                        @if(session('error_type') == 'create' && $errors->has('question_optional'))
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $errors->first('question_optional') }}
                            </p>
                        @endif
                        <p class="mt-1 text-xs text-gray-500">
                            Maksimal 500 karakter
                        </p>
                    </div>
                    <div class="mb-5">
                        <label class="block text-gray-700 text-sm font-medium mb-2">
                            Gambar Pertanyaan <span class="text-gray-400">(Opsional)</span>
                        </label>
                        <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-red-300 transition-colors">
                            <input 
                                type="file" 
                                name="image" 
                                id="imageInput" 
                                class="hidden" 
                                accept="image/*"
                                onchange="previewImage(event)"
                            >
                            <div id="imagePreview" class="hidden mb-4">
                                <img id="preview" class="max-w-full max-h-48 mx-auto rounded-lg">
                                <button type="button" onclick="removeImage()" class="mt-2 text-sm text-red-600 hover:text-red-800">
                                    <i class="fas fa-trash mr-1"></i> Hapus Gambar
                                </button>
                            </div>
                            <div id="uploadArea" class="cursor-pointer" onclick="document.getElementById('imageInput').click()">
                                <div class="w-16 h-16 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-cloud-upload-alt text-2xl text-gray-400"></i>
                                </div>
                                <p class="text-gray-600 font-medium">Klik untuk upload gambar</p>
                                <p class="text-gray-500 text-sm mt-1">Format: JPG, PNG, JPEG, GIF, WebP (max: 2MB)</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="font-semibold text-gray-900">Opsi Jawaban</h4>
                        <button 
                            type="button" 
                            id="add-option"
                            class="px-4 py-2 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-lg hover:from-red-700 hover:to-red-800 transition-all flex items-center text-sm"
                        >
                            <i class="fas fa-plus mr-2"></i> Tambah Opsi
                        </button>
                    </div>
                    
                    <div id="options-container" class="space-y-4">
                    </div>
                    
                    <div class="mt-6 p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-yellow-500 mt-1 mr-3"></i>
                            <div>
                                <p class="text-yellow-800 text-sm font-medium">Tips:</p>
                                <p class="text-yellow-700 text-xs mt-1">
                                    • Pastikan minimal ada 2 opsi jawaban<br>
                                    • Tandai satu opsi sebagai jawaban benar<br>
                                    • Maksimal 6 opsi jawaban
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="px-6 py-4 border-t flex justify-end space-x-3">
                <button 
                    type="button" 
                    class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-all font-medium"
                    data-modal-close="addModal"
                >
                    Batal
                </button>
                <button 
                    type="submit" 
                    class="px-5 py-2.5 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl hover:from-red-700 hover:to-red-800 transition-all font-medium shadow-md disabled:opacity-50 disabled:cursor-not-allowed"
                    id="submitBtn"
                >
                    <span class="flex items-center">
                        <i class="fas fa-save mr-2"></i>
                        <span>Simpan Pertanyaan</span>
                        <span id="loading" class="hidden ml-2">
                            <i class="fas fa-spinner fa-spin"></i>
                        </span>
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>

@foreach ($questions as $q)
<div id="detailModal{{ $q->id }}" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden modal-animation">
        <div class="px-6 py-4 border-b flex items-center justify-between">
            <h3 class="text-xl font-bold text-gray-900">Detail Pertanyaan</h3>
            <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors" data-modal-close="detailModal{{ $q->id }}">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="overflow-y-auto p-6" style="max-height: calc(90vh - 120px);">
            <div class="mb-6">
                <h4 class="font-semibold text-gray-900 mb-3">Pertanyaan</h4>
                <div class="bg-gray-50 rounded-xl p-5">
                    <p class="text-gray-900 text-lg">{{ $q->question_text ?? '-' }}</p>
                    @if($q->question_optional)
                    <p class="text-gray-600 mt-2 text-sm">
                        <i class="fas fa-info-circle mr-2"></i> {{ $q->question_optional }}
                    </p>
                    @endif
                </div>
            </div>
            
            @if($q->image)
            <div class="mb-6">
                <h4 class="font-semibold text-gray-900 mb-3">Gambar Pertanyaan</h4>
                <div class="border rounded-xl overflow-hidden">
                    <img src="{{ $q->image }}" alt="Gambar Pertanyaan" class="w-full h-auto">
                </div>
            </div>
            @endif
            
            <div>
                <h4 class="font-semibold text-gray-900 mb-3">Opsi Jawaban</h4>
                <div class="space-y-3">
                    @foreach ($q->choices as $opt)
                    <div class="border rounded-xl p-4 {{ $q->correct_option_id == $opt->id ? 'border-green-500 bg-green-50' : 'border-gray-200' }}">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center">
                                <div class="w-8 h-8 {{ $q->correct_option_id == $opt->id ? 'bg-green-600' : 'bg-gray-200' }} rounded-lg flex items-center justify-center mr-3">
                                    <span class="font-bold text-white">{{ $opt->option_label }}</span>
                                </div>
                                <span class="font-medium text-gray-900">{{ $opt->option_text }}</span>
                            </div>
                            @if($q->correct_option_id == $opt->id)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check mr-2"></i> Jawaban Benar
                            </span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        
        <div class="px-6 py-4 border-t flex justify-end">
            <button 
                type="button" 
                class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-all font-medium"
                data-modal-close="detailModal{{ $q->id }}"
            >
                Tutup
            </button>
        </div>
    </div>
</div>
<div id="editModal{{ $q->id }}" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-3xl max-h-[90vh] overflow-hidden modal-animation">
        <form action="{{ route('question.update', [$test->slug, $typeTest->slug, $subTest->slug, $q->id]) }}" 
              method="POST" enctype="multipart/form-data" id="editQuestionForm{{ $q->id }}" novalidate>
            @csrf
            @method('PUT')
            
            <div class="px-6 py-4 border-b flex items-center justify-between">
                <h3 class="text-xl font-bold text-gray-900">Edit Pertanyaan</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors" data-modal-close="editModal{{ $q->id }}">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="overflow-y-auto p-6" style="max-height: calc(90vh - 120px);">
                @if(session('error_type') == 'edit_' . $q->id)
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
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-2 required">
                            Pertanyaan
                        </label>
                        <textarea 
                            name="question_text" 
                            rows="3"
                            class="w-full px-4 py-3 border {{ session('error_type') == 'edit_' . $q->id && $errors->has('question_text') ? 'border-red-500' : 'border-gray-300' }} rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all" 
                            required
                            maxlength="1000"
                        >{{ old('question_text', session('error_type') == 'edit_' . $q->id ? (session('old_input.question_text') ?? $q->question_text) : $q->question_text) }}</textarea>
                        @if(session('error_type') == 'edit_' . $q->id && $errors->has('question_text'))
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $errors->first('question_text') }}
                            </p>
                        @endif
                        <div class="flex justify-between items-center mt-1">
                            <p class="text-xs text-gray-500">
                                Maksimal 1000 karakter
                            </p>
                            <span class="text-xs text-gray-400 char-counter" data-target="question_text{{ $q->id }}">0/1000</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-2">
                            Keterangan <span class="text-gray-400">(Opsional)</span>
                        </label>
                        <input 
                            type="text" 
                            name="question_optional" 
                            class="w-full px-4 py-3 border {{ session('error_type') == 'edit_' . $q->id && $errors->has('question_optional') ? 'border-red-500' : 'border-gray-300' }} rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all" 
                            value="{{ old('question_optional', session('error_type') == 'edit_' . $q->id ? (session('old_input.question_optional') ?? $q->question_optional) : $q->question_optional) }}"
                            maxlength="500"
                        >
                        @if(session('error_type') == 'edit_' . $q->id && $errors->has('question_optional'))
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $errors->first('question_optional') }}
                            </p>
                        @endif
                        <p class="mt-1 text-xs text-gray-500">
                            Maksimal 500 karakter
                        </p>
                    </div>
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-medium mb-2">
                        Gambar Pertanyaan <span class="text-gray-400">(Opsional)</span>
                    </label>
                    @if($q->image)
                    <div class="mb-4">
                        <p class="text-sm text-gray-600 mb-2">Gambar saat ini:</p>
                        <div class="relative inline-block">
                            <img src="{{ $q->image }}" alt="Current Image" class="max-w-48 max-h-48 rounded-lg">
                            <button type="button" onclick="removeCurrentImage({{ $q->id }})" class="absolute top-2 right-2 bg-red-600 text-white p-1.5 rounded-full hover:bg-red-700">
                                <i class="fas fa-times text-xs"></i>
                            </button>
                        </div>
                        <input type="hidden" name="current_image" value="{{ $q->image }}">
                    </div>
                    @endif
                    
                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-red-300 transition-colors">
                        <input 
                            type="file" 
                            name="image" 
                            id="imageInput{{ $q->id }}" 
                            class="hidden" 
                            accept="image/*"
                            onchange="previewEditImage(event, {{ $q->id }})"
                        >
                        <div id="imagePreview{{ $q->id }}" class="hidden mb-4">
                            <img id="preview{{ $q->id }}" class="max-w-full max-h-48 mx-auto rounded-lg">
                            <button type="button" onclick="removeEditImage({{ $q->id }})" class="mt-2 text-sm text-red-600 hover:text-red-800">
                                <i class="fas fa-trash mr-1"></i> Hapus Gambar Baru
                            </button>
                        </div>
                        <div id="uploadArea{{ $q->id }}" class="cursor-pointer" onclick="document.getElementById('imageInput{{ $q->id }}').click()">
                            <div class="w-16 h-16 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                <i class="fas fa-cloud-upload-alt text-2xl text-gray-400"></i>
                            </div>
                            <p class="text-gray-600 font-medium">Klik untuk upload gambar baru</p>
                            <p class="text-gray-500 text-sm mt-1">Format: JPG, PNG, JPEG, GIF, WebP (max: 2MB)</p>
                        </div>
                    </div>
                </div>
                <div class="mb-6">
                    <h4 class="font-semibold text-gray-900 mb-4">Opsi Jawaban</h4>
                    <div class="space-y-4">
                        @foreach ($q->choices as $opt)
                        <div class="border rounded-xl p-4">
                            <div class="flex items-center justify-between mb-3">
                                <label class="font-medium text-gray-900">Pilihan {{ $opt->option_label }}</label>
                                @if($q->correct_option_id == $opt->id)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check mr-2"></i> Jawaban Benar Saat Ini
                                </span>
                                @endif
                            </div>
                            <input 
                                type="text" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all mb-3"
                                name="options[{{ $opt->option_label }}]"
                                value="{{ old('options.'.$opt->option_label, session('error_type') == 'edit_' . $q->id ? (session('old_input.options.'.$opt->option_label) ?? $opt->option_text) : $opt->option_text) }}" 
                                required
                                maxlength="255"
                            >
                            <div class="flex items-center">
                                <input 
                                    type="radio" 
                                    name="correct" 
                                    id="correct_{{ $q->id }}_{{ $opt->option_label }}"
                                    value="{{ $opt->option_label }}"
                                    class="w-4 h-4 text-red-600 border-gray-300 focus:ring-red-500"
                                    {{ $q->correct_option_id == $opt->id ? 'checked' : '' }}
                                    required
                                >
                                <label for="correct_{{ $q->id }}_{{ $opt->option_label }}" class="ml-2 text-gray-700 text-sm">
                                    Tandai sebagai jawaban benar
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="px-6 py-4 border-t flex justify-end space-x-3">
                <button 
                    type="button" 
                    class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-all font-medium"
                    data-modal-close="editModal{{ $q->id }}"
                >
                    Batal
                </button>
                <button 
                    type="submit" 
                    class="px-5 py-2.5 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl hover:from-red-700 hover:to-red-800 transition-all font-medium shadow-md disabled:opacity-50 disabled:cursor-not-allowed"
                    id="editSubmitBtn{{ $q->id }}"
                >
                    <span class="flex items-center">
                        <i class="fas fa-save mr-2"></i>
                        <span>Update Pertanyaan</span>
                        <span id="editLoading{{ $q->id }}" class="hidden ml-2">
                            <i class="fas fa-spinner fa-spin"></i>
                        </span>
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>
@endforeach
@if(!view()->exists('vendor.pagination.custom'))
    @php
        $customPagination = '
        @if ($paginator->hasPages())
            <ul class="flex flex-wrap items-center">
                @if ($paginator->onFirstPage())
                    <li>
                        <span class="px-3 py-2 border border-gray-300 rounded-lg text-gray-400 cursor-not-allowed text-sm">
                            <i class="fas fa-chevron-left mr-1"></i> Sebelumnya
                        </span>
                    </li>
                @else
                    <li>
                        <a href="{{ $paginator->previousPageUrl() }}" class="px-3 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-all text-sm">
                            <i class="fas fa-chevron-left mr-1"></i> Sebelumnya
                        </a>
                    </li>
                @endif

                @foreach ($elements as $element)
                    @if (is_string($element))
                        <li>
                            <span class="px-3 py-2 border border-gray-300 rounded-lg text-gray-700 text-sm">{{ $element }}</span>
                        </li>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li class="mx-1">
                                    <span class="px-3 py-2 border border-red-600 bg-red-600 text-white rounded-lg text-sm">{{ $page }}</span>
                                </li>
                            @else
                                <li class="mx-1">
                                    <a href="{{ $url }}" class="px-3 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-all text-sm">{{ $page }}</a>
                                </li>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                @if ($paginator->hasMorePages())
                    <li>
                        <a href="{{ $paginator->nextPageUrl() }}" class="px-3 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-all text-sm">
                            Selanjutnya <i class="fas fa-chevron-right ml-1"></i>
                        </a>
                    </li>
                @else
                    <li>
                        <span class="px-3 py-2 border border-gray-300 rounded-lg text-gray-400 cursor-not-allowed text-sm">
                            Selanjutnya <i class="fas fa-chevron-right ml-1"></i>
                        </span>
                    </li>
                @endif
            </ul>
        @endif
        ';
    @endphp
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
    const filterToggle = document.getElementById('filterToggle');
    const filterForm = document.getElementById('filterForm');
    
    if (filterToggle && filterForm) {
        filterToggle.addEventListener('click', function() {
            filterForm.classList.toggle('hidden');
        });
    }
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

    let nextLabel = 'A';
    const addButton = document.getElementById('add-option');
    const optionsContainer = document.getElementById('options-container');
    
    if (addButton && optionsContainer) {
        optionsContainer.innerHTML = '';
        
        addOption('A');
        addOption('B');
        
        nextLabel = 'C'; 
        
        addButton.addEventListener('click', function () {
            if (nextLabel.charCodeAt(0) <= 'F'.charCodeAt(0)) {
                addOption(nextLabel);
                nextLabel = String.fromCharCode(nextLabel.charCodeAt(0) + 1);
            } else {
                showAlertModal('warning', 'Peringatan', 'Maksimal 6 opsi jawaban');
            }
        });
        
        document.addEventListener('click', function(e) {
            if (e.target.closest('.remove-option')) {
                const optionItem = e.target.closest('.option-item');
                const label = optionItem.dataset.label;
                if (document.querySelectorAll('.option-item').length <= 2) {
                    showAlertModal('warning', 'Peringatan', 'Minimal harus ada 2 opsi jawaban');
                    return;
                }
                const isCorrect = optionItem.querySelector('input[type="radio"]').checked;
                optionItem.remove();
                if (isCorrect) {
                    const firstRadio = optionsContainer.querySelector('input[type="radio"]');
                    if (firstRadio) {
                        firstRadio.checked = true;
                    }
                }
            }
        });
    }

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

                const radios = form.querySelectorAll('input[type="radio"][name="correct"]');
                if (radios.length > 0) {
                    const isRadioChecked = Array.from(radios).some(radio => radio.checked);
                    if (!isRadioChecked) {
                        isValid = false;
                        const radioContainer = radios[0].closest('.option-item') || form.querySelector('#options-container');
                        if (radioContainer) {
                            const errorMsg = document.createElement('p');
                            errorMsg.className = 'mt-2 text-sm text-red-600 flex items-center';
                            errorMsg.innerHTML = `<i class="fas fa-exclamation-circle mr-1"></i>Pilih salah satu jawaban yang benar`;
                            radioContainer.appendChild(errorMsg);
                        }
                    }
                }

                if (form.id === 'createQuestionForm') {
                    const optionItems = form.querySelectorAll('.option-item');
                    if (optionItems.length < 2) {
                        isValid = false;
                        const optionsContainer = document.getElementById('options-container');
                        if (optionsContainer) {
                            const errorMsg = document.createElement('p');
                            errorMsg.className = 'mt-2 text-sm text-red-600 flex items-center';
                            errorMsg.innerHTML = `<i class="fas fa-exclamation-circle mr-1"></i>Minimal 2 opsi jawaban diperlukan`;
                            optionsContainer.appendChild(errorMsg);
                        }
                    }
                }
                
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

    setupFormValidation('createQuestionForm', 'submitBtn', 'loading');
    
    @foreach ($questions as $q)
        setupFormValidation('editQuestionForm{{ $q->id }}', 'editSubmitBtn{{ $q->id }}', 'editLoading{{ $q->id }}');
    @endforeach

    setTimeout(() => {
        const successMsg = document.getElementById('success-message');
        const errorMsg = document.getElementById('error-message');
        if (successMsg) successMsg.remove();
        if (errorMsg) errorMsg.remove();
    }, 5000);

    @if(session('error_type'))
        @if(str_starts_with(session('error_type'), 'create'))
            const createModal = document.getElementById('addModal');
            if (createModal) {
                createModal.classList.remove('hidden');
                createModal.classList.add('flex');
                document.body.style.overflow = 'hidden';
            }
        @endif
        
        @foreach ($questions as $q)
            @if(session('error_type') == 'edit_' . $q->id)
                const editModal{{ $q->id }} = document.getElementById('editModal{{ $q->id }}');
                if (editModal{{ $q->id }}) {
                    editModal{{ $q->id }}.classList.remove('hidden');
                    editModal{{ $q->id }}.classList.add('flex');
                    document.body.style.overflow = 'hidden';
                }
            @endif
        @endforeach
    @endif

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
    initializeDeleteForms();
});

function addOption(label) {
    const optionsContainer = document.getElementById('options-container');
    if (!optionsContainer) return;
    
    const optionItem = document.createElement('div');
    optionItem.className = 'border rounded-xl p-4 option-item';
    optionItem.dataset.label = label;
    optionItem.innerHTML = `
        <div class="flex items-center justify-between mb-3">
            <label class="font-medium text-gray-900">Pilihan ${label}</label>
            <button type="button" class="text-red-600 hover:text-red-800 remove-option">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <input 
            type="text" 
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all mb-3"
            name="options[${label}]"
            placeholder="Masukkan teks opsi ${label}"
            required
            maxlength="255"
        >
        <div class="flex items-center">
            <input 
                type="radio" 
                name="correct" 
                value="${label}"
                class="w-4 h-4 text-red-600 border-gray-300 focus:ring-red-500"
                ${label === 'A' ? 'checked' : ''}
                required
            >
            <label class="ml-2 text-gray-700 text-sm">
                Tandai sebagai jawaban benar
            </label>
        </div>
    `;
    
    optionsContainer.appendChild(optionItem);
}

function previewImage(event) {
    const input = event.target;
    const preview = document.getElementById('preview');
    const previewContainer = document.getElementById('imagePreview');
    const uploadArea = document.getElementById('uploadArea');
    
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const maxSize = 2 * 1024 * 1024; 
        
        if (file.size > maxSize) {
            showAlertModal('error', 'Ukuran File Melebihi Batas', 'Ukuran file maksimal 2MB');
            input.value = '';
            return;
        }
        
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            previewContainer.classList.remove('hidden');
            uploadArea.classList.add('hidden');
        }
        
        reader.readAsDataURL(file);
    }
}

function previewEditImage(event, questionId) {
    const input = event.target;
    const preview = document.getElementById('preview' + questionId);
    const previewContainer = document.getElementById('imagePreview' + questionId);
    const uploadArea = document.getElementById('uploadArea' + questionId);
    
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const maxSize = 2 * 1024 * 1024;
        
        if (file.size > maxSize) {
            showAlertModal('error', 'Ukuran File Melebihi Batas', 'Ukuran file maksimal 2MB');
            input.value = '';
            return;
        }
        
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            previewContainer.classList.remove('hidden');
            uploadArea.classList.add('hidden');
        }
        
        reader.readAsDataURL(file);
    }
}

function removeImage() {
    const imageInput = document.getElementById('imageInput');
    const previewContainer = document.getElementById('imagePreview');
    const uploadArea = document.getElementById('uploadArea');
    
    imageInput.value = '';
    previewContainer.classList.add('hidden');
    uploadArea.classList.remove('hidden');
}

function removeEditImage(questionId) {
    const imageInput = document.getElementById('imageInput' + questionId);
    const previewContainer = document.getElementById('imagePreview' + questionId);
    const uploadArea = document.getElementById('uploadArea' + questionId);
    
    imageInput.value = '';
    previewContainer.classList.add('hidden');
    uploadArea.classList.remove('hidden');
}

async function removeCurrentImage(questionId) {
    const result = await showConfirmationModal({
        title: 'Hapus Gambar',
        icon: 'fas fa-trash',
        iconBgColor: 'bg-red-100',
        iconColor: 'text-red-600',
        heading: 'Hapus Gambar Saat Ini?',
        message: 'Apakah Anda yakin ingin menghapus gambar saat ini?',
        confirmText: 'Ya, Hapus',
        confirmColor: 'red',
        cancelText: 'Batal'
    });
    
    if (result) {
        const form = document.getElementById('editQuestionForm' + questionId);
        if (form) {
            let hiddenInput = form.querySelector('input[name="remove_current_image"]');
            if (!hiddenInput) {
                hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'remove_current_image';
                hiddenInput.value = '1';
                form.appendChild(hiddenInput);
            }
            const currentImageDisplay = form.querySelector('div.mb-4');
            if (currentImageDisplay) {
                currentImageDisplay.style.display = 'none';
            }
        }
    }
}

function showAlertModal(type, title, message, duration = 5000) {
    const modalId = `alertModal${Date.now()}`;
    
    const icon = type === 'success' ? 
        `<div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-check-circle text-3xl text-green-600"></i>
        </div>` :
        type === 'error' ? 
        `<div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-exclamation-circle text-3xl text-red-600"></i>
        </div>` :
        type === 'warning' ?
        `<div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-exclamation-triangle text-3xl text-yellow-600"></i>
        </div>` :
        `<div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-info-circle text-3xl text-blue-600"></i>
        </div>`;
    
    const modal = document.createElement('div');
    modal.id = modalId;
    modal.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4';
    modal.innerHTML = `
        <div class="bg-white rounded-2xl w-full max-w-md modal-animation">
            <div class="p-6 text-center">
                ${icon}
                <h3 class="text-xl font-bold text-gray-900 mb-2">${title}</h3>
                <p class="text-gray-600 mb-6">${message}</p>
                <button type="button" onclick="document.getElementById('${modalId}').remove(); document.body.style.overflow = 'auto';" 
                    class="px-5 py-2.5 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl hover:from-red-700 hover:to-red-800 transition-all font-medium">
                    Tutup
                </button>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    document.body.style.overflow = 'hidden';

    setTimeout(() => {
        modal.querySelector('button').focus();
    }, 100);

    setTimeout(() => {
        if (document.getElementById(modalId)) {
            document.getElementById(modalId).remove();
            document.body.style.overflow = 'auto';
        }
    }, duration);

    modal.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            modal.remove();
            document.body.style.overflow = 'auto';
        }
    });
}

function showConfirmationModal(options) {
    const modalId = `confirmationModal${Date.now()}`;
    
    const modal = document.createElement('div');
    modal.id = modalId;
    modal.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4';
    modal.innerHTML = `
        <div class="bg-white rounded-2xl w-full max-w-md modal-animation">
            <div class="px-6 py-4 border-b">
                <h3 class="text-xl font-bold text-gray-900">${options.title || 'Konfirmasi'}</h3>
            </div>
            <div class="p-6">
                <div class="flex items-center justify-center mb-6">
                    <div class="w-16 h-16 ${options.iconBgColor || 'bg-blue-100'} rounded-full flex items-center justify-center">
                        <i class="${options.icon || 'fas fa-question-circle'} text-2xl ${options.iconColor || 'text-blue-600'}"></i>
                    </div>
                </div>
                <h4 class="text-lg font-semibold text-gray-900 text-center mb-2">${options.heading || 'Konfirmasi Tindakan'}</h4>
                <p class="text-gray-600 text-center mb-6">${options.message || 'Apakah Anda yakin ingin melanjutkan?'}</p>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" id="cancelActionBtn" 
                        class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-all font-medium">
                        ${options.cancelText || 'Batal'}
                    </button>
                    <button type="button" id="confirmActionBtn" 
                        class="px-5 py-2.5 bg-gradient-to-r from-${options.confirmColor || 'blue'}-600 to-${options.confirmColor || 'blue'}-700 text-white rounded-xl hover:from-${options.confirmColor || 'blue'}-700 hover:to-${options.confirmColor || 'blue'}-800 transition-all font-medium">
                        ${options.confirmText || 'Ya, Lanjutkan'}
                    </button>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    document.body.style.overflow = 'hidden';
    
    return new Promise((resolve) => {
        const cancelBtn = modal.querySelector('#cancelActionBtn');
        const confirmBtn = modal.querySelector('#confirmActionBtn');
        
        cancelBtn.addEventListener('click', function() {
            modal.remove();
            document.body.style.overflow = 'auto';
            resolve(false);
        });
        
        confirmBtn.addEventListener('click', function() {
            modal.remove();
            document.body.style.overflow = 'auto';
            resolve(true);
        });
        
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.remove();
                document.body.style.overflow = 'auto';
                resolve(false);
            }
        });
        
        document.addEventListener('keydown', function handler(e) {
            if (e.key === 'Escape') {
                modal.remove();
                document.body.style.overflow = 'auto';
                document.removeEventListener('keydown', handler);
                resolve(false);
            }
        });

        setTimeout(() => cancelBtn.focus(), 100);
    });
}

function initializeDeleteForms() {
    document.querySelectorAll('.delete-form').forEach(form => {
        const deleteBtn = form.querySelector('.delete-btn');
        if (deleteBtn) {
            deleteBtn.addEventListener('click', async function(e) {
                e.preventDefault();
                
                const itemName = this.getAttribute('data-item-name') || 
                               form.getAttribute('data-item-name') ||
                               this.closest('tr').querySelector('td:nth-child(2)')?.textContent?.trim() || 
                               'item';
                
                const result = await showConfirmationModal({
                    title: 'Konfirmasi Hapus',
                    icon: 'fas fa-trash',
                    iconBgColor: 'bg-red-100',
                    iconColor: 'text-red-600',
                    heading: 'Hapus Pertanyaan?',
                    message: `Anda akan menghapus "${itemName}". Tindakan ini tidak dapat dibatalkan.`,
                    confirmText: 'Ya, Hapus',
                    confirmColor: 'red',
                    cancelText: 'Batal'
                });
                
                if (result) {
                    form.submit();
                }
            });
        }
    });
}

document.querySelectorAll('[data-modal-close="addModal"]').forEach(button => {
    button.addEventListener('click', function() {
        const previewContainer = document.getElementById('imagePreview');
        const uploadArea = document.getElementById('uploadArea');
        const imageInput = document.getElementById('imageInput');
        const optionsContainer = document.getElementById('options-container');
        
        if (previewContainer && uploadArea && imageInput) {
            previewContainer.classList.add('hidden');
            uploadArea.classList.remove('hidden');
            imageInput.value = '';
        }
        
        if (optionsContainer) {
            optionsContainer.innerHTML = '';
            nextLabel = 'A'; 
            addOption('A');
            addOption('B');
            nextLabel = 'C'; 
        }

        const form = document.getElementById('createQuestionForm');
        if (form) {
            form.reset();
        }
    });
});

@if(session('success'))
    setTimeout(() => {
        showAlertModal('success', 'Berhasil!', '{{ session('success') }}');
    }, 500);
@endif

@if(session('error'))
    setTimeout(() => {
        showAlertModal('error', 'Terjadi Kesalahan!', '{{ session('error') }}');
    }, 500);
@endif
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

@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: translateY(-50px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

@keyframes modalSlideOut {
    from {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
    to {
        opacity: 0;
        transform: translateY(-50px) scale(0.95);
    }
}

.modal-animation {
    animation: modalSlideIn 0.3s ease-out;
}

.modal-closing {
    animation: modalSlideOut 0.3s ease-out;
}

.animate-fade-in {
    animation: fadeInUp 0.5s ease-out;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.hover-lift {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.hover-lift:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px -5px rgba(0, 0, 0, 0.1);
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

.success-modal .icon-container {
    background: linear-gradient(135deg, #10b981, #059669);
}

.error-modal .icon-container {
    background: linear-gradient(135deg, #ef4444, #dc2626);
}

.warning-modal .icon-container {
    background: linear-gradient(135deg, #f59e0b, #d97706);
}

.info-modal .icon-container {
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
}

.modal button:focus {
    outline: 2px solid #3b82f6;
    outline-offset: 2px;
}

.modal button:focus:not(:focus-visible) {
    outline: none;
}

.confirm-loading {
    position: relative;
    color: transparent !important;
}

.confirm-loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 20px;
    height: 20px;
    margin: -10px 0 0 -10px;
    border: 2px solid #ffffff;
    border-top-color: transparent;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@media (max-width: 768px) {
    .grid-cols-1 {
        grid-template-columns: 1fr;
    }
    
    .p-6 {
        padding: 1rem;
    }
    
    .text-3xl {
        font-size: 1.875rem;
    }
    
    .w-full.max-w-3xl {
        margin: 0.5rem;
        width: calc(100% - 1rem);
    }
    
    table {
        display: block;
        overflow-x: auto;
        white-space: nowrap;
    }
    
    .filter-form .grid {
        grid-template-columns: 1fr !important;
    }
}

@media print {
    .fixed,
    [data-modal-toggle],
    [data-modal-close],
    button,
    .filterToggle {
        display: none !important;
    }
}
</style>
@endsection