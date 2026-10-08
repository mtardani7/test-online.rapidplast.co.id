@extends('layouts.navbar')

@section('content')
<section class="py-8 md:py-12 bg-gradient-to-b from-gray-50 to-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-10 md:mb-16">
            <h1 class="text-3xl md:text-5xl font-bold text-gray-800 mb-4">
                Tes <span class="text-red-600">Seleksi Awal</span>
            </h1>
            <p class="text-lg text-gray-600 mb-8 max-w-2xl mx-auto">
                Lengkapi serangkaian tes untuk mengevaluasi kemampuan dan kesesuaian Anda dengan posisi yang dilamar.
            </p>
            <div class="mt-8 flex flex-wrap justify-center gap-4">
                <div class="flex items-center bg-white rounded-xl p-4 shadow-md">
                    <div class="mr-4 p-3 bg-gradient-to-r from-red-100 to-red-50 rounded-lg">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-800">{{ $tests->count() }}</p>
                        <p class="text-sm text-gray-600">Total Tes</p>
                    </div>
                </div>
                
                <div class="flex items-center bg-white rounded-xl p-4 shadow-md">
                    <div class="mr-4 p-3 bg-gradient-to-r from-red-100 to-red-50 rounded-lg">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-800">{{ $totalDurationAllTests }} menit</p>
                        <p class="text-sm text-gray-600">Rata-rata Waktu</p>
                    </div>
                </div>
                <div class="flex items-center bg-white rounded-xl p-4 shadow-md">
                    <div class="mr-4 p-3 bg-gradient-to-r from-red-100 to-red-50 rounded-lg">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-800">100%</p>
                        <p class="text-sm text-gray-600">Rahasia</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-1 xl:grid-cols-2 gap-6">
        @foreach($tests as $test)
        <div class="group">
            <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 overflow-hidden transform hover:-translate-y-1">
                <div class="p-6 md:p-8">
                    <div class="flex flex-col md:flex-row gap-6">
                        <div class="flex-shrink-0 relative">
                            <div class="w-16 h-16 md:w-20 md:h-20 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-shadow duration-300">
                                <svg class="w-8 h-8 md:w-10 md:h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            {{-- Badge selesai --}}
                            @if($test->all_type_tests_completed ?? false)
                            <div class="absolute -top-2 -right-2">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 border border-green-200 shadow-sm">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    Selesai
                                </span>
                            </div>
                            @endif
                        </div>
                        <div class="flex-grow">
                            <div class="mb-4">
                                <div class="flex flex-wrap items-center justify-between gap-2 mb-3">
                                    <h3 class="text-xl md:text-2xl font-bold text-gray-800 group-hover:text-red-700 transition-colors duration-300">
                                        {{ $test->name }}
                                    </h3>
                                    @if($test->is_new)
                                    <span class="px-3 py-1 bg-gradient-to-r from-green-100 to-green-50 text-green-700 text-xs font-semibold rounded-full">
                                        Baru
                                    </span>
                                    @endif
                                </div>
                                <div class="prose max-w-none">
                                    <div class="h-40 overflow-y-auto pr-2 custom-scrollbar">
                                        <p class="text-gray-600 leading-relaxed text-justify">
                                            {{ $test->description }}
                                        </p>
                                        <div class="mt-4 flex flex-wrap gap-4">
                                            <div class="flex items-center text-sm text-gray-500">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span>{{ $test->total_duration }} menit</span> 
                                            </div>
                                            <div class="flex items-center text-sm text-gray-500">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                <span>Diawasi</span>
                                            </div>
                                            @if($test->total_questions)
                                            <div class="flex items-center text-sm text-gray-500">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                                <span>{{ $test->total_questions }} soal</span>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="pt-4 border-t border-gray-100">
                                @if($test->all_type_tests_completed ?? false)
                                {{-- Tombol Selesai (non-clickable) --}}
                                <div class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-green-700 text-white font-semibold rounded-lg shadow-md cursor-not-allowed opacity-90">
                                    <span class="mr-2">Semua Tes Selesai</span>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                </div>
                                @else
                                {{-- Tombol Mulai (normal) --}}
                                <a href="{{ route('user.type-tests', $test->slug) }}" 
                                class="inline-flex items-center text-red-600 hover:text-red-800 font-semibold group/btn transition-all duration-300">
                                    <span class="mr-2">Start Assessment</span>
                                    <svg class="w-5 h-5 transform group-hover/btn:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                </a>
                                @endif
                                
                                @if($test->instruction)
                                <div class="mt-4 text-sm text-gray-500">
                                    <button onclick="toggleInstruction('instruction-{{ $test->id }}')" class="flex items-center text-red-600 hover:text-red-800">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Lihat Petunjuk
                                    </button>
                                    <div id="instruction-{{ $test->id }}" class="mt-2 p-3 bg-gray-50 rounded-lg hidden">
                                        <p class="text-gray-700 text-sm">{{ $test->instruction }}</p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
        @if($tests->isEmpty())
        <div class="text-center py-16">
            <div class="mx-auto w-24 h-24 bg-gradient-to-r from-red-100 to-red-50 rounded-full flex items-center justify-center mb-6">
                <svg class="w-12 h-12 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <h3 class="text-2xl font-semibold text-gray-700 mb-3">Belum Ada Tes Tersedia</h3>
            <p class="text-gray-600 max-w-md mx-auto">
                Saat ini belum ada tes penilaian yang tersedia. Silakan periksa kembali nanti atau hubungi administrator.
            </p>
        </div>
        @endif
    </div>
</section>
<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: linear-gradient(to bottom, #ef4444, #dc2626);
        border-radius: 10px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(to bottom, #dc2626, #b91c1c);
    }
    
    .pagination {
        display: flex;
        justify-content: center;
        list-style: none;
        padding: 0;
        margin-top: 2rem;
    }
    
    .pagination li {
        margin: 0 4px;
    }
    
    .pagination li a,
    .pagination li span {
        display: inline-block;
        padding: 8px 16px;
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.3s;
        font-size: 14px;
    }
    
    .pagination li.active span {
        background: linear-gradient(to right, #ef4444, #dc2626);
        color: white;
        font-weight: 600;
    }
    
    .pagination li:not(.disabled):not(.active) a {
        color: #4b5563;
        background: white;
        border: 1px solid #e5e7eb;
    }
    
    .pagination li:not(.disabled):not(.active) a:hover {
        background: #fef2f2;
        border-color: #ef4444;
        color: #ef4444;
        transform: translateY(-2px);
    }
    
    .pagination li.disabled span {
        color: #9ca3af;
        background: #f3f4f6;
        border: 1px solid #e5e7eb;
    }
</style>

<script>
function toggleInstruction(id) {
    const element = document.getElementById(id);
    if (element.classList.contains('hidden')) {
        element.classList.remove('hidden');
    } else {
        element.classList.add('hidden');
    }
}
</script>
@endsection