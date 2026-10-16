@extends('layouts.sidebar')

@section('page-title', 'Pengaturan Rumus Penilaian')
@section('content')
<div class="mx-auto max-w-7xl space-y-6 px-3 py-5 sm:px-6 sm:py-7 lg:px-8">
    <!-- Header -->
    <div class="mb-6 sm:mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Pengaturan Rumus Penilaian</h1>
        <p class="text-gray-600 mt-1 sm:mt-2 text-sm sm:text-base">Atur cara sistem menghitung nilai untuk setiap tes.</p>
    </div>

    <!-- Success Message -->
    @if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-r-lg">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-check-circle text-green-500 text-lg mt-0.5"></i>
            </div>
            <div class="ml-3">
                <p class="text-green-800 font-medium">{{ session('success') }}</p>
            </div>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-circle text-red-500 text-lg mt-0.5"></i>
            </div>
            <div class="ml-3">
                <p class="text-red-800 font-medium">{{ session('error') }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Create New Formula Card -->
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-md mb-6 sm:mb-8 overflow-hidden">
        <div class="px-4 sm:px-6 py-4 border-b bg-gradient-to-r from-red-50 to-white">
            <div class="flex items-center">
                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-red-600 rounded-lg flex items-center justify-center mr-3 sm:mr-4">
                    <i class="fas fa-calculator text-white text-sm sm:text-base"></i>
                </div>
                <div>
                    <h2 class="text-lg sm:text-xl font-bold text-gray-900">Buat rumus baru</h2>
                    <p class="text-gray-600 text-xs sm:text-sm mt-0.5">Tentukan rumus yang digunakan untuk menghitung nilai tes.</p>
                </div>
            </div>
        </div>
        
        <div class="p-4 sm:p-6">
            <form action="{{ route('scoring.store') }}" method="POST" id="formulaForm">
                @csrf
                
                <!-- Test Selection -->
                <div class="mb-4 sm:mb-6">
                    <label class="block text-gray-700 text-sm font-medium mb-2">
                        Pilih tes <span class="text-red-500">*</span>
                    </label>
                    <select name="test_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all" required>
                        <option value="">-- Pilih Test --</option>
                        @foreach($tests as $test)
                            <option value="{{ $test->id }}" {{ old('test_id') == $test->id ? 'selected' : '' }}>
                                {{ $test->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('test_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Formula Input -->
                <div class="mb-4 sm:mb-6">
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-gray-700 text-sm font-medium">
                            Rumus penilaian <span class="text-red-500">*</span>
                        </label>
                        <button type="button" onclick="showFormulaHelp()" class="text-red-600 hover:text-red-700 text-sm">
                            <i class="fas fa-question-circle mr-1"></i> Bantuan
                        </button>
                    </div>
                    <div class="relative">
                        <input 
                            type="text" 
                            name="formula" 
                            value="{{ old('formula') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all font-mono text-sm sm:text-base" 
                            placeholder="Contoh: (sinonim_benar*2) + (antonim_benar*2) - total_salah"
                            required
                            id="formulaInput"
                        >
                        <div class="absolute right-3 top-3">
                            <button type="button" onclick="validateFormula()" class="text-gray-400 hover:text-green-600" title="Validasi Formula">
                                <i class="fas fa-check"></i>
                            </button>
                        </div>
                    </div>
                    <p class="text-gray-500 text-xs mt-2">
                        Gunakan variabel dengan underscore (contoh: sinonim_benar). Operator yang didukung: +, -, *, /, (, )
                    </p>
                    @error('formula')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-4 sm:mb-6">
                    <label class="block text-gray-700 text-sm font-medium mb-2">
                        Deskripsi (Opsional)
                    </label>
                    <textarea 
                        name="description" 
                        rows="3"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all text-sm sm:text-base" 
                        placeholder="Deskripsi singkat tentang rumus ini..."
                    >{{ old('description') }}</textarea>
                </div>

                <!-- Variables Section -->
                <div class="mb-6 sm:mb-8">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4">
                        <div class="mb-3 sm:mb-0">
                            <h4 class="text-lg font-semibold text-gray-900">Variabel yang Digunakan</h4>
                            <p class="text-gray-600 text-xs sm:text-sm mt-0.5">Definisikan variabel yang akan digunakan dalam formula</p>
                        </div>
                        <button 
                            type="button" 
                            onclick="addComponent()"
                            class="px-3 sm:px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all flex items-center justify-center text-xs sm:text-sm w-full sm:w-auto"
                        >
                            <i class="fas fa-plus mr-2"></i> Tambah Variabel
                        </button>
                    </div>
                    
                    <div id="component-wrapper" class="space-y-3">
                        @if(old('components'))
                            @foreach(old('components') as $component)
                                <div class="flex items-center space-x-3">
                                    <input 
                                        type="text" 
                                        name="components[]" 
                                        value="{{ $component }}"
                                        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all font-mono text-sm" 
                                        placeholder="Contoh: sinonim_benar"
                                        required
                                    >
                                    <button 
                                        type="button" 
                                        onclick="removeComponent(this)"
                                        class="w-8 h-8 sm:w-10 sm:h-10 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition-all flex items-center justify-center"
                                    >
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            @endforeach
                        @else
                            <div class="flex items-center space-x-3">
                                <input 
                                    type="text" 
                                    name="components[]" 
                                    class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all font-mono text-sm" 
                                    placeholder="Contoh: sinonim_benar"
                                    required
                                >
                                <button 
                                    type="button" 
                                    onclick="addComponent()"
                                    class="w-8 h-8 sm:w-10 sm:h-10 bg-green-100 text-green-600 rounded-lg hover:bg-green-200 transition-all flex items-center justify-center"
                                >
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Help Card -->
                    <div class="mt-4 sm:mt-6 p-3 sm:p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <div class="flex items-start">
                            <i class="fas fa-lightbulb text-blue-500 mt-1 mr-2 sm:mr-3 text-sm sm:text-base"></i>
                            <div>
                                <p class="text-blue-800 text-xs sm:text-sm font-medium">Contoh Penggunaan:</p>
                                <ul class="text-blue-700 text-xs mt-2 space-y-1">
                                    <li class="flex items-start">
                                        <span class="bg-blue-100 px-2 py-0.5 rounded mr-2 font-mono">sinonim_benar</span>
                                        <span>Jumlah jawaban benar untuk sinonim</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="bg-blue-100 px-2 py-0.5 rounded mr-2 font-mono">total_salah</span>
                                        <span>Total jawaban salah</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="bg-blue-100 px-2 py-0.5 rounded mr-2 font-mono">waktu_detik</span>
                                        <span>Waktu pengerjaan dalam detik</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="bg-blue-100 px-2 py-0.5 rounded mr-2 font-mono">skor_mentah</span>
                                        <span>Skor sebelum dikonversi</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="pt-4 border-t">
                    <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-3">
                        <button 
                            type="reset" 
                            class="px-4 sm:px-5 py-2.5 border border-gray-300 text-gray-700 rounded-lg sm:rounded-xl hover:bg-gray-50 transition-all font-medium text-sm sm:text-base order-2 sm:order-1"
                        >
                            Reset Form
                        </button>
                        <button 
                            type="submit" 
                            class="px-4 sm:px-5 py-2.5 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-lg sm:rounded-xl hover:from-red-700 hover:to-red-800 transition-all font-medium shadow-md text-sm sm:text-base order-1 sm:order-2"
                        >
                            <i class="fas fa-save mr-2"></i> Simpan Rumus
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Rules List Card -->
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-md overflow-hidden">
        <div class="px-4 sm:px-6 py-4 border-b bg-gradient-to-r from-gray-50 to-white">
            <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                <div class="flex items-center mb-3 sm:mb-0">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-purple-600 rounded-lg flex items-center justify-center mr-3 sm:mr-4">
                        <i class="fas fa-list-alt text-white text-sm sm:text-base"></i>
                    </div>
                    <div>
                        <h2 class="text-lg sm:text-xl font-bold text-gray-900">Daftar rumus penilaian</h2>
                        <p class="text-gray-600 text-xs sm:text-sm mt-0.5">Rumus yang digunakan untuk menghitung nilai setiap tes</p>
                    </div>
                </div>
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                    @if($rules->isNotEmpty())
                        <label for="scoringRuleSearch" class="sr-only">Cari berdasarkan nama tes atau rumus</label>
                        <input id="scoringRuleSearch" type="search" placeholder="Cari tes atau rumus..." class="min-h-10 w-full rounded-xl border border-slate-200 bg-white px-3 text-sm outline-none transition focus:border-rose-400 focus:ring-4 focus:ring-rose-100 sm:w-64">
                    @endif
                    <span class="px-3 sm:px-4 py-1.5 sm:py-2 bg-purple-100 text-purple-800 rounded-full text-xs sm:text-sm font-medium whitespace-nowrap">
                        <span id="scoringRuleCount">{{ $rules->count() }}</span> rumus
                    </span>
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table id="scoringRulesTable" class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b">
                        <th class="py-3 sm:py-4 px-4 sm:px-6 text-left text-gray-700 font-semibold text-xs sm:text-sm">Tes</th>
                        <th class="py-3 sm:py-4 px-4 sm:px-6 text-left text-gray-700 font-semibold text-xs sm:text-sm">Rumus</th>
                        <th class="py-3 sm:py-4 px-4 sm:px-6 text-left text-gray-700 font-semibold text-xs sm:text-sm">Variabel</th>
                        <th class="py-3 sm:py-4 px-4 sm:px-6 text-left text-gray-700 font-semibold text-xs sm:text-sm w-28 sm:w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($rules as $rule)
                    <tr class="scoring-rule-row hover:bg-gray-50 transition-colors">
                        <td data-label="Tes" class="py-3 sm:py-4 px-4 sm:px-6">
                            <div class="flex items-center">
                                <div>
                            <p class="font-medium text-gray-900 text-sm sm:text-base">{{ $rule->test->name ?? 'Tes tidak ditemukan' }}</p>
                                    <p class="text-gray-500 text-xs mt-0.5">{{ $rule->test->slug ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td data-label="Rumus" class="py-3 sm:py-4 px-4 sm:px-6">
                            <div class="font-mono text-xs sm:text-sm bg-gray-100 px-3 py-2 rounded-lg text-gray-800 break-all">
                                {{ $rule->formula }}
                            </div>
                            @if($rule->description)
                            <p class="text-gray-600 text-xs mt-1 sm:mt-2 line-clamp-2">{{ $rule->description }}</p>
                            @endif
                        </td>
                        <td data-label="Variabel" class="py-3 sm:py-4 px-4 sm:px-6">
                            <div class="flex flex-wrap gap-1 sm:gap-2 max-w-[200px]">
                                @foreach($rule->components as $c)
                                <span class="inline-flex items-center px-2 sm:px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mb-1">
                                    <i class="fas fa-variable mr-1 text-xs"></i>
                                    {{ $c->key }}
                                </span>
                                @endforeach
                            </div>
                        </td>
                        <td data-label="Aksi" class="py-3 sm:py-4 px-4 sm:px-6">
                            <div class="flex items-center space-x-1 sm:space-x-2">
                                <button 
                                    onclick="editRule({{ json_encode($rule) }}, {{ json_encode($rule->components->pluck('key')->toArray()) }})"
                                    class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                    title="Edit rumus"
                                >
                                    <i class="fas fa-edit text-sm sm:text-base"></i>
                                    <span class="sr-only">Edit rumus untuk {{ $rule->test->name ?? 'tes ini' }}</span>
                                </button>
                                <button 
                                    onclick="confirmDelete({{ $rule->id }}, '{{ $rule->test->name ?? 'Rule' }}')"
                                    class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                    title="Hapus rumus"
                                >
                                    <i class="fas fa-trash text-sm sm:text-base"></i>
                                    <span class="sr-only">Hapus rumus untuk {{ $rule->test->name ?? 'tes ini' }}</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-8 sm:py-12 text-center">
                            <div class="w-16 h-16 sm:w-24 sm:h-24 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4 sm:mb-6">
                                <i class="fas fa-calculator text-xl sm:text-3xl text-gray-400"></i>
                            </div>
                            <h4 class="text-base sm:text-xl font-semibold text-gray-700 mb-2">Belum ada rumus penilaian</h4>
                            <p class="text-gray-500 text-sm sm:text-base mb-4">Buat rumus pertama agar sistem dapat menghitung nilai.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <p id="scoringRuleSearchEmpty" class="hidden px-4 py-8 text-center text-sm text-slate-500 sm:px-6">Rumus tidak ditemukan. Coba kata kunci lain.</p>
    </div>

    <!-- Help Section -->
    <div class="mt-6 sm:mt-8 bg-gradient-to-r from-red-50 to-white rounded-xl sm:rounded-2xl shadow-md p-4 sm:p-6">
        <div class="flex items-center mb-4">
            <div class="w-8 h-8 sm:w-10 sm:h-10 bg-red-600 rounded-lg flex items-center justify-center mr-3 sm:mr-4">
                <i class="fas fa-question-circle text-white text-sm sm:text-base"></i>
            </div>
            <div>
                <h3 class="text-base sm:text-lg font-bold text-gray-900">Cara Menggunakan Rumus Scoring</h3>
                <p class="text-gray-600 text-xs sm:text-sm mt-0.5">Panduan membuat rumus perhitungan nilai</p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
            <div>
                <h4 class="font-semibold text-gray-900 mb-2 sm:mb-3 text-sm sm:text-base">📋 Format Variabel</h4>
                <ul class="space-y-2 text-xs sm:text-sm text-gray-700">
                    <li class="flex items-start">
                        <i class="fas fa-check text-green-500 mt-1 mr-2 text-xs"></i>
                        <span>Gunakan underscore untuk memisahkan kata (contoh: <code class="bg-gray-100 px-1 rounded font-mono">verbal_benar</code>)</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-green-500 mt-1 mr-2 text-xs"></i>
                        <span>Variabel harus unik dan konsisten dalam formula</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-green-500 mt-1 mr-2 text-xs"></i>
                        <span>Hindari spasi dan karakter khusus selain underscore</span>
                    </li>
                </ul>
            </div>
            
            <div>
                <h4 class="font-semibold text-gray-900 mb-2 sm:mb-3 text-sm sm:text-base">🧮 Contoh Formula</h4>
                <div class="space-y-2 sm:space-y-3">
                    <div class="bg-gray-100 rounded-lg p-2 sm:p-3">
                        <p class="text-xs sm:text-sm font-medium text-gray-900 mb-1">Skor TPA Umum:</p>
                        <code class="text-xs text-gray-700 font-mono">(verbal_benar*2) + (numerik_benar*3) - (total_salah*0.5)</code>
                    </div>
                    <div class="bg-gray-100 rounded-lg p-2 sm:p-3">
                        <p class="text-xs sm:text-sm font-medium text-gray-900 mb-1">Skor dengan waktu:</p>
                        <code class="text-xs text-gray-700 font-mono">(total_benar*4) - (waktu_detik/60)</code>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden z-50 transition-opacity">
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative max-h-[90vh] w-full max-w-md overflow-y-auto rounded-2xl bg-white shadow-xl transition-all">
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100">
                            <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                        </div>
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                            <h3 class="text-lg font-semibold leading-6 text-gray-900" id="deleteModalTitle">
                                Konfirmasi Hapus
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500" id="deleteModalMessage">
                                    Apakah Anda yakin ingin menghapus data ini?
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <form id="deleteForm" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto">
                            Hapus
                        </button>
                    </form>
                    <button type="button"
                        onclick="closeDeleteModal()"
                        class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden z-50 transition-opacity">
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative max-h-[90vh] w-full max-w-2xl overflow-y-auto rounded-2xl bg-white shadow-xl transition-all">
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-lg font-semibold leading-6 text-gray-900">
                                Edit Rumus Scoring
                            </h3>
                            <p class="text-sm text-gray-500 mt-1">Edit rumus perhitungan nilai</p>
                        </div>
                        <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-500">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                    
                    <form id="editForm" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="rule_id" id="editRuleId">
                        
                        <div>
                            <label class="block text-gray-700 text-sm font-medium mb-2">
                                Test
                            </label>
                            <input type="text" id="editTestName" class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50" readonly>
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 text-sm font-medium mb-2">
                                Formula (Rumus Scoring) <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="formula" 
                                id="editFormula"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all font-mono" 
                                required
                            >
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 text-sm font-medium mb-2">
                                Deskripsi (Opsional)
                            </label>
                            <textarea 
                                name="description" 
                                id="editDescription"
                                rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all" 
                                placeholder="Deskripsi singkat tentang rumus ini..."
                            ></textarea>
                        </div>
                        
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label class="block text-gray-700 text-sm font-medium">
                                    Variabel yang Digunakan <span class="text-red-500">*</span>
                                </label>
                                <button type="button" onclick="addEditComponent()" class="text-sm text-red-600 hover:text-red-700">
                                    <i class="fas fa-plus mr-1"></i> Tambah
                                </button>
                            </div>
                            <div id="editComponentWrapper" class="space-y-2">
                                <!-- Components will be added here dynamically -->
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-2 pt-4 border-t">
                            <input type="checkbox" name="is_active" id="editIsActive" class="rounded text-red-600">
                            <label for="editIsActive" class="text-sm text-gray-700">Aktifkan rule ini</label>
                        </div>
                        
                        <div class="flex justify-end space-x-3 pt-4">
                            <button type="button" onclick="closeEditModal()" 
                                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all font-medium">
                                Batal
                            </button>
                            <button type="submit" 
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-all font-medium">
                                <i class="fas fa-save mr-2"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Component Management
function addComponent() {
    let wrapper = document.getElementById('component-wrapper');
    let newComponent = document.createElement('div');
    newComponent.className = 'flex items-center space-x-3';
    newComponent.innerHTML = `
        <input 
            type="text" 
            name="components[]" 
            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all font-mono text-sm" 
            placeholder="Contoh: numerik_benar"
            required
        >
        <button 
            type="button" 
            onclick="removeComponent(this)"
            class="w-8 h-8 sm:w-10 sm:h-10 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition-all flex items-center justify-center"
        >
            <i class="fas fa-minus"></i>
        </button>
    `;
    wrapper.appendChild(newComponent);
    newComponent.querySelector('input').focus();
}

function removeComponent(btn) {
    if (document.querySelectorAll('#component-wrapper .flex.items-center.space-x-3').length > 1) {
        btn.closest('.flex.items-center.space-x-3').remove();
    }
}

// Edit Component Management
function addEditComponent() {
    let wrapper = document.getElementById('editComponentWrapper');
    let newComponent = document.createElement('div');
    newComponent.className = 'flex items-center space-x-2';
    newComponent.innerHTML = `
        <input 
            type="text" 
            name="edit_components[]" 
            class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all font-mono text-sm" 
            placeholder="Contoh: numerik_benar"
            required
        >
        <button 
            type="button" 
            onclick="removeEditComponent(this)"
            class="w-8 h-8 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition-all flex items-center justify-center"
        >
            <i class="fas fa-minus text-xs"></i>
        </button>
    `;
    wrapper.appendChild(newComponent);
}

function removeEditComponent(btn) {
    btn.closest('.flex.items-center.space-x-2').remove();
}

// Formula Validation
function validateFormula() {
    const formula = document.getElementById('formulaInput').value;
    if (!formula) {
        alert('Formula tidak boleh kosong');
        return false;
    }
    
    // Basic validation for allowed characters
    const allowedPattern = /^[a-zA-Z0-9_\s\+\-\*\/\(\)\.]+$/;
    if (!allowedPattern.test(formula.replace(/\s/g, ''))) {
        alert('Formula mengandung karakter yang tidak diizinkan. Hanya huruf, angka, underscore, dan operator matematika yang diperbolehkan.');
        return false;
    }
    
    // Extract variables from formula
    const variables = formula.match(/[a-zA-Z_][a-zA-Z0-9_]*/g) || [];
    const uniqueVariables = [...new Set(variables)];
    
    // Get defined components
    const components = Array.from(document.querySelectorAll('input[name="components[]"]'))
        .map(input => input.value.trim())
        .filter(value => value);
    
    // Check for undefined variables
    const undefinedVars = uniqueVariables.filter(v => !components.includes(v));
    
    if (undefinedVars.length > 0) {
        alert('Variabel berikut belum didefinisikan: ' + undefinedVars.join(', '));
        return false;
    }
    
    alert('✓ Formula valid!');
    return true;
}

function showFormulaHelp() {
    alert('Format Formula:\n\n' +
          '1. Gunakan variabel dengan underscore: variable_name\n' +
          '2. Operator yang didukung: +, -, *, /\n' +
          '3. Gunakan tanda kurung untuk pengelompokan: (a + b) * c\n' +
          '4. Contoh: (sinonim_benar*2) + (antonim_benar*3) - total_salah\n\n' +
          'Pastikan semua variabel dalam formula sudah didefinisikan di bagian Variabel yang Digunakan.');
}

// Delete Modal Functions
let currentDeleteId = null;

function confirmDelete(id, name) {
    currentDeleteId = id;
    document.getElementById('deleteModalTitle').textContent = 'Hapus Rumus Scoring';
    document.getElementById('deleteModalMessage').textContent = `Apakah Anda yakin ingin menghapus rumus scoring "${name}"? Tindakan ini tidak dapat dibatalkan.`;
    document.getElementById('deleteForm').action = `/scoring/${id}`;
    document.getElementById('deleteModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    currentDeleteId = null;
}

// Status Toggle
function toggleRuleStatus(id, isActive) {
    if (confirm(`Apakah Anda yakin ingin ${isActive ? 'menonaktifkan' : 'mengaktifkan'} rule ini?`)) {
        fetch(`/scoring/${id}/toggle`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ is_active: !isActive })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengubah status.');
        });
    }
}

// Edit Modal Functions
function editRule(rule, components) {
    document.getElementById('editRuleId').value = rule.id;
    document.getElementById('editTestName').value = rule.test?.name || 'Test tidak ditemukan';
    document.getElementById('editFormula').value = rule.formula;
    document.getElementById('editDescription').value = rule.description || '';
    document.getElementById('editIsActive').checked = rule.is_active;
    
    // Set edit form action
    document.getElementById('editForm').action = `/scoring/${rule.id}`;
    
    // Clear and populate components
    const wrapper = document.getElementById('editComponentWrapper');
    wrapper.innerHTML = '';
    
    components.forEach(component => {
        wrapper.innerHTML += `
            <div class="flex items-center space-x-2">
                <input 
                    type="text" 
                    name="edit_components[]" 
                    value="${component}"
                    class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all font-mono text-sm" 
                    required
                >
                <button 
                    type="button" 
                    onclick="removeEditComponent(this)"
                    class="w-8 h-8 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition-all flex items-center justify-center"
                >
                    <i class="fas fa-minus text-xs"></i>
                </button>
            </div>
        `;
    });
    
    document.getElementById('editModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Close modals with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        if (!document.getElementById('deleteModal').classList.contains('hidden')) {
            closeDeleteModal();
        }
        if (!document.getElementById('editModal').classList.contains('hidden')) {
            closeEditModal();
        }
    }
});

// Close modals when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});

document.getElementById('editModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeEditModal();
    }
});

// Form validation
document.getElementById('formulaForm').addEventListener('submit', function(e) {
    const formula = document.querySelector('input[name="formula"]').value;
    const components = Array.from(document.querySelectorAll('input[name="components[]"]'))
        .map(input => input.value.trim())
        .filter(value => value);
    
    if (components.length === 0) {
        e.preventDefault();
        alert('Harap tambahkan minimal satu variabel.');
        return;
    }
    
    if (!validateFormula()) {
        e.preventDefault();
    }
});

document.getElementById('scoringRuleSearch')?.addEventListener('input', function() {
    const term = this.value.trim().toLocaleLowerCase();
    const rows = [...document.querySelectorAll('#scoringRulesTable .scoring-rule-row')];
    let visible = 0;
    rows.forEach(row => {
        const matches = row.textContent.toLocaleLowerCase().includes(term);
        row.classList.toggle('hidden', !matches);
        if (matches) visible++;
    });
    document.getElementById('scoringRuleCount').textContent = visible;
    document.getElementById('scoringRuleSearchEmpty')?.classList.toggle('hidden', visible !== 0 || term === '');
});
</script>

<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    @media (max-width: 767px) {
        #scoringRulesTable,
        #scoringRulesTable tbody,
        #scoringRulesTable tr,
        #scoringRulesTable td {
            display: block;
            width: 100%;
        }

        #scoringRulesTable thead {
            display: none;
        }

        #scoringRulesTable tbody tr.scoring-rule-row {
            margin: 0.75rem;
            width: calc(100% - 1.5rem);
            overflow: hidden;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            background: #fff;
            box-shadow: 0 1px 2px rgb(15 23 42 / 0.05);
        }

        #scoringRulesTable tbody tr.scoring-rule-row td {
            display: grid;
            grid-template-columns: minmax(5.5rem, 0.32fr) minmax(0, 1fr);
            align-items: start;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #f1f5f9;
            overflow-wrap: anywhere;
        }

        #scoringRulesTable tbody tr.scoring-rule-row td::before {
            content: attr(data-label);
            color: #64748b;
            font-size: 0.75rem;
            font-weight: 600;
            line-height: 1.25rem;
        }

        #scoringRulesTable tbody tr.scoring-rule-row td:last-child {
            border-bottom: 0;
        }

        #scoringRulesTable td[data-label="Aksi"] > div {
            justify-content: flex-end;
        }
    }
    
    code, .font-mono {
        font-family: 'Courier New', Courier, monospace;
    }
    
    input:focus, select:focus, textarea:focus {
        outline: none;
        ring: 2px;
    }
    
    .transition-all {
        transition: all 0.2s ease-in-out;
    }
</style>
@endsection
