@extends('layouts.sidebar')

@section('page-title', 'Manipulasi Hasil')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">
            Daftar Attempt Kandidat
        </h1>
        <p class="text-gray-600">
            Kelola semua hasil ujian dan attempt kandidat
        </p>
    </div>
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 mb-6 p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-gray-800">Filter & Pencarian</h2>
            <button onclick="toggleFilter()" class="md:hidden flex items-center gap-2 text-gray-600 hover:text-gray-800">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                Filter
            </button>
        </div>
        
        <form method="GET" action="{{ route('admin.attempts.index') }}" id="filterForm" class="space-y-4 md:space-y-0 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Cari Kandidat</label>
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Nama atau email..."
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Test</label>
                <select name="test_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    <option value="">Semua Test</option>
                    @foreach($tests as $test)
                        <option value="{{ $test->id }}" {{ request('test_id') == $test->id ? 'selected' : '' }}>
                            {{ $test->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Urutkan</label>
                <select name="sort_order" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    <option value="desc" {{ request('sort_order', 'desc') == 'desc' ? 'selected' : '' }}>Terbaru</option>
                    <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Terlama</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Score Min</label>
                <input type="number" name="score_min" value="{{ request('score_min') }}" 
                       placeholder="0"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Score Max</label>
                <input type="number" name="score_max" value="{{ request('score_max') }}" 
                       placeholder="100"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Item per Halaman</label>
                <select name="per_page" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    <option value="5" {{ request('per_page', 10) == 5 ? 'selected' : '' }}>5</option>
                    <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                    <option value="20" {{ request('per_page', 10) == 20 ? 'selected' : '' }}>20</option>
                    <option value="50" {{ request('per_page', 10) == 50 ? 'selected' : '' }}>50</option>
                </select>
            </div>
            <div class="lg:col-span-4 pt-2 flex flex-wrap gap-3">
                <button type="submit" 
                        class="px-6 py-2.5 bg-gradient-to-r from-red-600 to-red-700 text-white font-medium rounded-lg hover:from-red-700 hover:to-red-800 transition-all duration-200 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Terapkan Filter
                </button>
                
                <a href="{{ route('admin.attempts.index') }}" 
                   class="px-6 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-all duration-200 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Reset Filter
                </a>
                
                @if(request()->hasAny(['search', 'user_id', 'test_id', 'date_from', 'date_to', 'score_min', 'score_max']))
                    <div class="ml-auto bg-red-50 text-red-700 px-4 py-2 rounded-lg text-sm font-medium">
                        {{ $attempts->total() }} hasil ditemukan
                    </div>
                @endif
            </div>
        </form>
    </div>
    @if(request()->hasAny(['search', 'user_id', 'test_id']))
    <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-lg shadow p-4 border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-lg mr-4">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Attempts</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $attempts->total() }}</p>
                </div>
            </div>
        </div>
        
        @if($attempts->count() > 0)
        <div class="bg-white rounded-lg shadow p-4 border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-lg mr-4">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-4 border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-lg mr-4">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Rentang Tanggal</p>
                    <p class="text-lg font-bold text-gray-800">
                        @php
                            $minDate = $attempts->min('created_at');
                            $maxDate = $attempts->max('created_at');
                        @endphp
                        {{ $minDate ? $minDate->format('d M') : '-' }} - {{ $maxDate ? $maxDate->format('d M Y') : '-' }}
                    </p>
                </div>
            </div>
        </div>
        @endif
    </div>
    @endif
    <div id="confirmModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden z-50 transition-opacity">
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative transform overflow-hidden rounded-lg bg-white shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                <h3 class="text-lg font-semibold leading-6 text-gray-900" id="modal-title">
                                    Konfirmasi Hapus
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500" id="modal-message">
                                        Apakah Anda yakin ingin menghapus data ini?
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <form id="delete-form" method="POST">
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
    @if($attempts->count() > 0)
        @foreach($attempts as $attempt)
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 mb-6 overflow-hidden hover:shadow-xl transition-shadow duration-300">
            <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-red-50 to-white">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="space-y-3">
                        <div>
                            <span class="inline-flex items-center rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-800">
                                Attempt ID: {{ $attempt->id }}
                            </span>
                        </div>
                        <div class="space-y-2">
                            <div>
                                <p class="text-sm text-gray-500 font-medium">Kandidat</p>
                                <div class="flex items-center gap-2 mt-1">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <p class="font-semibold text-gray-800 text-lg">
                                        {{ $attempt->user->name ?? '-' }}
                                    </p>
                                    <span class="text-sm text-gray-500">({{ $attempt->user->email ?? '-' }})</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center md:text-right">
                        <p class="text-sm text-gray-500 font-medium mb-1">Total Score</p>
                        <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-gradient-to-br from-red-500 to-red-600 text-white shadow-lg">
                            <div>
                                <p class="text-3xl font-bold">{{ $attempt->score ?? 0 }}</p>
                                <p class="text-xs opacity-90">Points</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="px-6 py-4 space-y-4">
                @forelse($attempt->typeTests as $typeTest)
                    <div class="border border-gray-200 rounded-xl p-5 bg-gradient-to-r from-gray-50 to-white hover:border-red-200 transition-colors duration-200">
                        <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="p-2 bg-red-100 rounded-lg">
                                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500 font-medium">Type Test</p>
                                        <p class="font-semibold text-gray-800 text-lg">
                                            {{ $typeTest->typeTest->name ?? 'Unknown Type Test' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                                    <div class="bg-white rounded-lg p-3 border border-gray-100 shadow-sm">
                                        <p class="text-sm text-gray-500">Correct</p>
                                        <p class="text-xl font-bold text-green-600">
                                            {{ $typeTest->correct }} / {{ $typeTest->total }}
                                        </p>
                                    </div>
                                    <div class="bg-white rounded-lg p-3 border border-gray-100 shadow-sm">
                                        <p class="text-sm text-gray-500">Accuracy</p>
                                        <p class="text-xl font-bold text-blue-600">
                                            @php
                                                $accuracy = $typeTest->total > 0 ? ($typeTest->correct / $typeTest->total) * 100 : 0;
                                            @endphp
                                            {{ number_format($accuracy, 1) }}%
                                        </p>
                                    </div>
                                </div>
                                @if($typeTest->subAnswers->count() > 0)
                                    <div class="mt-6">
                                        <p class="text-sm font-medium text-gray-700 mb-3">Sub Tests:</p>
                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                            @foreach($typeTest->subAnswers as $subTest)
                                                <div class="group bg-white border border-gray-200 rounded-lg p-4 hover:border-red-300 hover:shadow-md transition-all duration-200">
                                                    <div class="flex justify-between items-start mb-2">
                                                        <div>
                                                            <p class="font-medium text-gray-800">
                                                                {{ $subTest->subTest->name ?? 'Unknown Sub Test' }}
                                                            </p>
                                                            <p class="text-sm text-gray-500 mt-1">
                                                                Score: <span class="font-semibold text-green-600">{{ $subTest->score }}</span>
                                                            </p>
                                                        </div>
                                                        <button
                                                            onclick="showDeleteModal('{{ route('admin.attempts.sub-test.destroy', $subTest->id) }}', 'sub test: ' + '{{ $subTest->subTest->name ?? 'Unknown Sub Test' }}')"
                                                            class="opacity-0 group-hover:opacity-100 text-gray-400 hover:text-red-600 p-1 rounded-full hover:bg-red-50 transition-all duration-200"
                                                            title="Hapus Sub Test">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="flex flex-col sm:flex-row lg:flex-col gap-2">
                                <button
                                    onclick="showDeleteModal('{{ route('admin.attempts.type-test.destroy', $typeTest->id) }}', 'type test: ' + '{{ $typeTest->typeTest->name ?? 'Unknown Type Test' }}')"
                                    class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium text-red-700 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 hover:border-red-300 transition-all duration-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Hapus Type
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 border-2 border-dashed border-gray-300 rounded-xl">
                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p class="text-gray-500 font-medium">Tidak ada type test</p>
                        <p class="text-sm text-gray-400 mt-1">Belum ada data type test untuk attempt ini</p>
                    </div>
                @endforelse
            </div>
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                    <div class="text-sm text-gray-500">
                        <span class="inline-flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $attempt->created_at->format('d M Y, H:i') }}
                        </span>
                    </div>
                    <button
                        onclick="showDeleteModal('{{ route('admin.attempts.destroy', $attempt->id) }}', 'semua data attempt untuk kandidat: ' + '{{ $attempt->user->name ?? '-' }}')"
                        class="inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-red-600 to-red-700 rounded-lg hover:from-red-700 hover:to-red-800 shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Hapus Entire Attempt
                    </button>
                </div>
            </div>
        </div>
        @endforeach
        <div class="mt-8 bg-white rounded-xl shadow-lg border border-gray-200 p-6">
            <div class="mt-8 bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                <div class="flex flex-col lg:flex-row justify-between items-center gap-6">
                    <div class="text-sm text-gray-600">
                        Menampilkan <span class="font-semibold">{{ $attempts->firstItem() }}</span> 
                        sampai <span class="font-semibold">{{ $attempts->lastItem() }}</span> 
                        dari <span class="font-semibold">{{ $attempts->total() }}</span> hasil
                    </div>
                    
                    <div class="flex flex-col sm:flex-row items-center gap-4 w-full sm:w-auto">
                        <div class="sm:hidden flex items-center justify-between w-full mb-4">
                            <div class="text-sm text-gray-600">
                                Halaman {{ $attempts->currentPage() }} dari {{ $attempts->lastPage() }}
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="{{ $attempts->previousPageUrl() }}" 
                                class="px-3 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors flex-shrink-0 {{ $attempts->onFirstPage() ? 'opacity-50 cursor-not-allowed' : '' }}"
                                {{ $attempts->onFirstPage() ? 'disabled' : '' }}>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                    </svg>
                                </a>
                                <a href="{{ $attempts->nextPageUrl() }}" 
                                class="px-3 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors flex-shrink-0 {{ $attempts->hasMorePages() ? '' : 'opacity-50 cursor-not-allowed' }}"
                                {{ $attempts->hasMorePages() ? '' : 'disabled' }}>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                        <div class="hidden sm:flex items-center gap-2">
                            <a href="{{ $attempts->previousPageUrl() }}" 
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors {{ $attempts->onFirstPage() ? 'opacity-50 cursor-not-allowed' : '' }}"
                            {{ $attempts->onFirstPage() ? 'disabled' : '' }}>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                            </a>
                            
                            @php
                                $current = $attempts->currentPage();
                                $last = $attempts->lastPage();
                                $start = max(1, $current - 2);
                                $end = min($last, $current + 2);
                            @endphp
                            
                            @if($start > 1)
                                <a href="{{ $attempts->url(1) }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                                    1
                                </a>
                                @if($start > 2)
                                    <span class="px-2 py-2 text-gray-400">...</span>
                                @endif
                            @endif
                            
                            @for ($page = $start; $page <= $end; $page++)
                                @if($page == $current)
                                    <span class="px-4 py-2 bg-gradient-to-r from-red-600 to-red-700 text-white font-semibold rounded-lg">
                                        {{ $page }}
                                    </span>
                                @else
                                    <a href="{{ $attempts->url($page) }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endfor
                            
                            @if($end < $last)
                                @if($end < $last - 1)
                                    <span class="px-2 py-2 text-gray-400">...</span>
                                @endif
                                <a href="{{ $attempts->url($last) }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                                    {{ $last }}
                                </a>
                            @endif
                            
                            <a href="{{ $attempts->nextPageUrl() }}" 
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors {{ $attempts->hasMorePages() ? '' : 'opacity-50 cursor-not-allowed' }}"
                            {{ $attempts->hasMorePages() ? '' : 'disabled' }}>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                        <div class="flex items-center justify-center sm:justify-end gap-2 text-sm w-full sm:w-auto">
                            <span class="text-gray-600 whitespace-nowrap">Lompat ke:</span>
                            <form method="GET" action="{{ route('admin.attempts.index') }}" class="flex items-center gap-2">
                                @foreach(request()->all() as $key => $value)
                                    @if($key !== 'page')
                                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                    @endif
                                @endforeach
                                <input type="number" name="page" min="1" max="{{ $attempts->lastPage() }}" 
                                    value="{{ $attempts->currentPage() }}"
                                    class="w-20 px-3 py-2 border border-gray-300 rounded-lg text-center focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                <button type="submit" class="px-3 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors whitespace-nowrap">
                                    Go
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
function toggleFilter() {
    const form = document.getElementById('filterForm');
    form.classList.toggle('hidden');
    form.classList.toggle('block');
}

function showDeleteModal(url, itemName = 'data ini') {
    const modal = document.getElementById('confirmModal');
    const form = document.getElementById('delete-form');
    const message = document.getElementById('modal-message');
    
    form.action = url;
    message.textContent = `Apakah Anda yakin ingin menghapus ${itemName}? Tindakan ini tidak dapat dikembalikan.`;
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeDeleteModal() {
    const modal = document.getElementById('confirmModal');
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
}

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('confirmModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeleteModal();
        }
    });

    document.addEventListener('keydown', function(e) {
        const modal = document.getElementById('confirmModal');
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeDeleteModal();
        }
    });

    if (window.innerWidth < 768) {
        const filterForm = document.getElementById('filterForm');
        if (filterForm) {
            filterForm.classList.add('hidden');
        }
    }

    const today = new Date().toISOString().split('T')[0];
    const dateToInput = document.querySelector('input[name="date_to"]');
    if (dateToInput && !dateToInput.value) {
        dateToInput.value = today;
    }

    const dateFromInput = document.querySelector('input[name="date_from"]');
    if (dateFromInput && !dateFromInput.value) {
        const thirtyDaysAgo = new Date();
        thirtyDaysAgo.setDate(thirtyDaysAgo.getDate() - 30);
        dateFromInput.value = thirtyDaysAgo.toISOString().split('T')[0];
    }

    const perPageSelects = document.querySelectorAll('select[name="per_page"]');
    perPageSelects.forEach(select => {
        select.addEventListener('change', function() {
            this.closest('form').submit();
        });
    });
    const filterForm = document.getElementById('filterForm');
    if (filterForm) {
        filterForm.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.innerHTML = `
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Memproses...
                `;
                submitBtn.disabled = true;
            }
        });
    }
});

document.addEventListener('keydown', function(e) {
    if (e.ctrlKey && e.key === 'ArrowLeft') {
        const prevLink = document.querySelector('a[href*="page="]:not([disabled])');
        if (prevLink && !prevLink.classList.contains('opacity-50')) {
            window.location.href = prevLink.href;
        }
    }

    if (e.ctrlKey && e.key === 'ArrowRight') {
        const nextLink = document.querySelectorAll('a[href*="page="]');
        if (nextLink[1] && !nextLink[1].classList.contains('opacity-50')) {
            window.location.href = nextLink[1].href;
        }
    }
});
</script>

<style>
#confirmModal ::-webkit-scrollbar {
    width: 6px;
}

#confirmModal ::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

#confirmModal ::-webkit-scrollbar-thumb {
    background: #dc2626;
    border-radius: 4px;
}

#confirmModal ::-webkit-scrollbar-thumb:hover {
    background: #b91c1c;
}

@keyframes modalFadeIn {
    from {
        opacity: 0;
        transform: translateY(-20px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

#confirmModal > div > div {
    animation: modalFadeIn 0.3s ease-out;
}

.transition-all {
    transition-property: all;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 150ms;
}

@media (max-width: 768px) {
    .pagination-numbers span,
    .pagination-numbers a:nth-child(2),
    .pagination-numbers a:nth-last-child(2),
    .pagination-numbers a:first-child,
    .pagination-numbers a:last-child {
        display: inline-block !important;
    }
    
    .pagination-numbers a:not(:nth-child(2)):not(:nth-last-child(2)):not(:first-child):not(:last-child) {
        display: none;
    }
    
    #filterForm {
        transition: all 0.3s ease-in-out;
    }
}
@media print {
    .bg-gradient-to-r,
    .shadow-lg,
    .hover\\:shadow-xl,
    button,
    .pagination,
    #filterForm {
        display: none !important;
    }
    
    .border,
    .border-gray-200 {
        border: 1px solid #ddd !important;
    }
    
    .bg-white {
        background: white !important;
    }
    
    .text-gray-800,
    .text-gray-700,
    .text-gray-900 {
        color: #000 !important;
    }
}
</style>
@endsection