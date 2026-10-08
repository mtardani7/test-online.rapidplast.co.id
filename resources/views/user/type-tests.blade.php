@extends('layouts.navbar')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white py-8 md:py-12">
    <div class="container mx-auto px-4 max-w-7xl">
        <div class="mb-10 md:mb-16">
            <nav class="flex mb-6" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('user.tests') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-red-600 transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            Dashboard
                        </a>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            <span class="ml-1 text-sm font-medium text-red-600 md:ml-2">
                                {{ $test->name }}
                            </span>
                        </div>
                    </li>
                </ol>
            </nav>
            <div class="bg-white rounded-2xl shadow-lg p-6 md:p-8 border border-gray-100">
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                    <div class="flex-1">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-16 h-16 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-2xl md:text-4xl font-bold text-gray-800">{{ $test->name }}</h1>

                            </div>
                        </div>
                        <div>
                            <div class="text-gray-700">
                                @if($test->rules_formatted)
                                    <ul class="ml-4">
                                        @foreach($test->rules_formatted as $rule)
                                            <li class="flex items-start">
                                                <span class="text-red-600 mr-2">•</span>
                                                <span class="font-normal text-lg">{{ $rule }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p>Tidak ada peraturan tersedia.</p>
                                @endif
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-4 mt-6">
                            <div class="flex items-center text-sm text-gray-600 bg-gray-50 px-4 py-2 rounded-lg">
                                <svg class="w-4 h-4 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>{{ $typeTests->count() }} Tes</span>
                            </div>
                            <div class="flex items-center text-sm text-gray-600 bg-gray-50 px-4 py-2 rounded-lg">
                                <svg class="w-4 h-4 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                <span>Aman & Diawasi</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mb-12">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Jenis Tes Tersedia</h2>
                    <p class="text-gray-600 mt-2">Pilih tes untuk memulai penilaian</p>
                </div>
                <div class="text-sm text-gray-500">
                    Menampilkan {{ $typeTests->count() }} dari {{ $typeTests->count() }} tes
                </div>
            </div>

            @if($typeTests->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($typeTests as $type)
                <div class="group">
                    <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 overflow-hidden h-full transform hover:-translate-y-1">
                        <div class="px-6 pt-6 pb-4">
                            {{-- ... konten card ... --}}
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-shrink-0">
                                    <div class="w-14 h-14 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center shadow-md group-hover:shadow-lg transition-shadow duration-300">
                                        <span class="text-white font-bold text-lg">{{ $loop->iteration }}</span>
                                    </div>
                                </div>
                                {{-- Tambahkan badge jika semua subtest selesai --}}
                                @if($type->all_subtests_completed ?? false)
                                <div class="flex-shrink-0">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                        Selesai
                                    </span>
                                </div>
                                @endif
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 mb-3 group-hover:text-red-700 transition-colors duration-300">
                                {{ $type->name }}
                            </h3>
                            <div class="h-40 overflow-y-auto pr-2 custom-scrollbar mb-4">
                                <p class="text-gray-600 leading-relaxed text-justify">
                                    {{ $type->description }}
                                </p>
                            </div>
                        </div>
                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                            <div class="flex items-center justify-between">
                                @if($type->all_subtests_completed ?? false)
                                {{-- Tombol Selesai (non-clickable) --}}
                                <div class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-green-700 text-white font-semibold rounded-lg shadow-md cursor-not-allowed opacity-90">
                                    <span class="mr-2">Selesai</span>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                </div>
                                @else
                                {{-- Tombol Mulai (normal) --}}
                                <a href="{{ route('user.sub-tests', [$test->slug, $type->slug]) }}" 
                                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-red-600 to-red-700 text-white font-semibold rounded-lg hover:from-red-700 hover:to-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 shadow-md hover:shadow-lg transition-all duration-200 transform group-hover:-translate-y-0.5">
                                    <span class="mr-2">Mulai</span>
                                    <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-16">
                <div class="mx-auto w-24 h-24 bg-gradient-to-r from-red-100 to-red-50 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-12 h-12 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-semibold text-gray-700 mb-3">Belum Ada Jenis Tes Tersedia</h3>
                <p class="text-gray-600 max-w-md mx-auto">
                    Saat ini belum ada jenis tes yang tersedia untuk penilaian ini. Silakan periksa kembali nanti atau hubungi administrator.
                </p>
            </div>
            @endif
        </div>
        <div class="bg-gradient-to-r from-red-50 to-white rounded-2xl p-6 md:p-8 border border-red-100 mb-8">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Petunjuk Penilaian</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mt-1">
                                <div class="w-5 h-5 bg-gradient-to-r from-red-500 to-red-600 rounded-full flex items-center justify-center">
                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </div>
                            <p class="ml-3 text-gray-600">Pastikan koneksi internet Anda stabil</p>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mt-1">
                                <div class="w-5 h-5 bg-gradient-to-r from-red-500 to-red-600 rounded-full flex items-center justify-center">
                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </div>
                            <p class="ml-3 text-gray-600">Lengkapi semua tes untuk menyelesaikan penilaian</p>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mt-1">
                                <div class="w-5 h-5 bg-gradient-to-r from-red-500 to-red-600 rounded-full flex items-center justify-center">
                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </div>
                            <p class="ml-3 text-gray-600">Batas waktu berlaku untuk setiap tes</p>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mt-1">
                                <div class="w-5 h-5 bg-gradient-to-r from-red-500 to-red-600 rounded-full flex items-center justify-center">
                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </div>
                            <p class="ml-3 text-gray-600">Hasil akan tersedia setelah penyelesaian</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
    
    .breadcrumb-item:not(:last-child):after {
        content: "/";
        margin: 0 8px;
        color: #9ca3af;
    }
</style>
@endsection