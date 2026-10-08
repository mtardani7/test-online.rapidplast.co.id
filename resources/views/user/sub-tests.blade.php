@extends('layouts.navbar')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white py-8 md:py-12">
    <div class="container mx-auto px-4 max-w-7xl">
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
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                        <a href="{{ route('user.type-tests', $test->slug) }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-red-600 transition-colors duration-200 md:ml-2">
                            {{ $test->name }}
                        </a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                        <span class="ml-1 text-sm font-medium text-red-600 md:ml-2">
                            {{ $typeTest->name }}
                        </span>
                    </div>
                </li>
            </ol>
        </nav>
        <div class="mb-10 md:mb-16">
            <div class="bg-white rounded-2xl shadow-xl p-6 md:p-8 border border-gray-100">
                <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-8">
                    <div class="flex-1">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-16 h-16 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-2xl md:text-4xl font-bold text-gray-800">{{ $typeTest->name }}</h1>
                                <p class="text-gray-600 mt-2">Pilih sub-tes untuk memulai penilaian</p>
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-4 mt-6">
                            <div class="flex items-center text-sm text-gray-600 bg-gray-50 px-4 py-2 rounded-lg">
                                <svg class="w-4 h-4 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <span>{{ $subTests->count() }} Sub-Tes</span>
                            </div>
                            <div class="flex items-center text-sm text-gray-600 bg-gray-50 px-4 py-2 rounded-lg">
                                <svg class="w-4 h-4 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>{{ $totalDuration }} menit total</span>
                            </div>
                            <div class="flex items-center text-sm text-gray-600 bg-gray-50 px-4 py-2 rounded-lg">
                                <svg class="w-4 h-4 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                <span>Penilaian Aman</span>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gradient-to-r from-red-50 to-pink-50 rounded-xl p-6 border border-red-100 min-w-[200px]">
                        <div class="text-center">
                            <div class="relative inline-block mb-4">
                                <svg class="w-20 h-20" viewBox="0 0 36 36">
                                    <path
                                        d="M18 2
                                        a 16 16 0 0 1 0 32
                                        a 16 16 0 0 1 0 -32"
                                        fill="none"
                                        stroke="#fecaca"
                                        stroke-width="3" />
                                    <path
                                        d="M18 2
                                        a 16 16 0 0 1 0 32
                                        a 16 16 0 0 1 0 -32"
                                        fill="none"
                                        stroke="#dc2626"
                                        stroke-width="3"
                                        stroke-dasharray="{{ $completionRate }}, 100" />
                                    <text
                                        x="18"
                                        y="20.35"
                                        text-anchor="middle"
                                        fill="#dc2626"
                                        class="text-xl font-bold">
                                        {{ $completionRate }}%
                                    </text>
                                </svg>
                            </div>
                            <div class="text-sm font-medium text-gray-700">Tingkat Penyelesaian</div>
                            <div class="text-xs text-gray-500 mt-1">Kemajuan Keseluruhan</div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="mb-12">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Sub-Tes Tersedia</h2>
                    <p class="text-gray-600 mt-2">Selesaikan semua sub-tes untuk menyelesaikan jenis penilaian ini</p>
                </div>
                <div class="text-sm text-gray-500">
                    <span class="font-medium text-red-600">{{ $subTests->where('completed', true)->count() }}</span>
                    dari
                    <span class="font-medium text-gray-800">{{ $subTests->count() }}</span>
                    selesai
                </div>
            </div>
            @if($subTests->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($subTests as $subTest)
                <div class="group">
                    <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 overflow-hidden h-full transform hover:-translate-y-1">
                        <div class="px-6 pt-6 pb-4">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-600 rounded-lg flex items-center justify-center shadow-md group-hover:shadow-lg transition-shadow duration-300 mr-3">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-xs font-medium text-gray-500 mb-1">SUB-TES</div>
                                        <div class="text-lg font-bold text-gray-800">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</div>
                                    </div>
                                </div>

                                @if($subTest->completed)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-green-50 to-emerald-50 text-green-700 border border-green-100">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Selesai
                                </span>
                                @elseif($subTest->has_answers)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-yellow-50 to-amber-50 text-yellow-700 border border-yellow-100">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Dalam Proses
                                </span>
                                @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-red-50 to-pink-50 text-red-700 border border-red-100">
                                    <span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span>
                                    Belum Dimulai
                                </span>
                                @endif
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 mb-3 group-hover:text-red-700 transition-colors duration-300">
                                {{ $subTest->name }}
                            </h3>
                            <div class="h-40 overflow-y-auto pr-2 custom-scrollbar mb-4">
                                <p class="text-gray-600 leading-relaxed text-justify">
                                    {{ $subTest->description }}
                                </p>
                                <div class="mt-4 space-y-2">
                                    <div class="flex items-center text-sm text-gray-500">
                                        <svg class="w-4 h-4 mr-2 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>{{ $subTest->duration }} menit perkiraan</span>
                                    </div>
                                    <div class="flex items-center text-sm text-gray-500">
                                        <svg class="w-4 h-4 mr-2 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                        <span>{{ $subTest->total_questions }} soal</span>
                                    </div>
                                    <div class="flex items-center text-sm text-gray-500">
                                        <svg class="w-4 h-4 mr-2 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                        </svg>
                                        <span>Skor: {{ $subTest->correct_count }}/{{ $subTest->total_questions }}</span>
                                    </div>
                                    @if($subTest->completed)
                                    <div class="flex items-center text-sm text-green-600">
                                        <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span>Selesai: {{ \Carbon\Carbon::now()->format('d M Y') }}</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                            <div class="flex items-center justify-between">
                                <div class="flex-1 mr-4">
                                    <div class="text-xs font-medium text-gray-700 mb-1">Progres</div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-gradient-to-r from-red-500 to-red-600 h-2 rounded-full"
                                            style="width: {{ $subTest->progress }}%"></div>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        @if($subTest->completed)
                                        <span class="text-green-600 font-medium">Selesai ({{ $subTest->progress }}%)</span>
                                        @elseif($subTest->has_answers)
                                        <span class="text-yellow-600 font-medium">Dalam Proses ({{ $subTest->progress }}%)</span>
                                        @else
                                        <span>Belum Dimulai</span>
                                        @endif
                                    </div>
                                </div>
                                @if($subTest->completed)
                                <div class="text-center">
                                    <div class="text-sm font-medium text-green-600 mb-1">
                                        <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Selesai
                                    </div>
                                    <div class="text-xs text-gray-500">Skor: {{ $subTest->correct_count }}/{{ $subTest->total_questions }}</div>
                                </div>
                                @else
                                <a href="{{ route('user.questions', [$test->slug, $typeTest->slug, $subTest->slug]) }}"
                                    class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-red-600 to-red-700 text-white font-semibold rounded-lg hover:from-red-700 hover:to-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 shadow-md hover:shadow-lg transition-all duration-200 transform group-hover:-translate-y-0.5 min-w-[100px] justify-center">
                                    @if($subTest->has_answers)
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                    Lanjutkan
                                    @else
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                    Mulai
                                    @endif
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
                <h3 class="text-2xl font-semibold text-gray-700 mb-3">Tidak Ada Sub-Tes Tersedia</h3>
                <p class="text-gray-600 max-w-md mx-auto">
                    Saat ini belum ada sub-tes yang tersedia untuk jenis penilaian ini. Silakan periksa kembali nanti atau hubungi administrator.
                </p>
            </div>
            @endif
        </div>
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6 md:p-8 border border-blue-100 mb-8">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Panduan Penilaian</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mt-1">
                                <div class="w-5 h-5 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center">
                                    <span class="text-white text-xs font-bold">1</span>
                                </div>
                            </div>
                            <div class="ml-3">
                                <h4 class="font-medium text-gray-800">Manajemen Waktu</h4>
                                <p class="text-sm text-gray-600 mt-1">Setiap sub-tes memiliki batas waktu. Kelola waktu Anda dengan efektif.</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mt-1">
                                <div class="w-5 h-5 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center">
                                    <span class="text-white text-xs font-bold">2</span>
                                </div>
                            </div>
                            <div class="ml-3">
                                <h4 class="font-medium text-gray-800">Tidak Bisa Kembali</h4>
                                <p class="text-sm text-gray-600 mt-1">Setelah mengirim jawaban, Anda tidak bisa kembali ke soal sebelumnya.</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mt-1">
                                <div class="w-5 h-5 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center">
                                    <span class="text-white text-xs font-bold">3</span>
                                </div>
                            </div>
                            <div class="ml-3">
                                <h4 class="font-medium text-gray-800">Jawab Semua Soal</h4>
                                <p class="text-sm text-gray-600 mt-1">Pastikan untuk menjawab semua soal sebelum waktu habis.</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mt-1">
                                <div class="w-5 h-5 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center">
                                    <span class="text-white text-xs font-bold">4</span>
                                </div>
                            </div>
                            <div class="ml-3">
                                <h4 class="font-medium text-gray-800">Masalah Teknis</h4>
                                <p class="text-sm text-gray-600 mt-1">Jika mengalami masalah teknis, hubungi dukungan segera.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-8 border-t border-gray-200">
            <a href="{{ route('user.type-tests', $test->slug) }}"
                class="inline-flex items-center px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>
    </div>
</div>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const startButtons = document.querySelectorAll('a[href*="questions"]');
    startButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            const isCompleted = this.closest('.group').querySelector('span:contains("Selesai")');
            if (!isCompleted || !isCompleted.textContent.includes('Selesai')) {
                const testSlug = "{{ $test->slug }}";
                const typeTestSlug = "{{ $typeTest->slug }}";
                Object.keys(localStorage).forEach(key => {
                    if (key.includes(testSlug) || key.includes(typeTestSlug)) {
                        localStorage.removeItem(key);
                    }
                });
                const confirmStart = confirm('Apakah Anda yakin ingin memulai tes? Semua jawaban yang belum disimpan akan dihapus.');
                if (!confirmStart) {
                    e.preventDefault();
                    return false;
                }
            }
        });
    });
});
</script>
@endsection
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

    .circular-progress {
        transform: rotate(-90deg);
    }

    .breadcrumb-item:not(:last-child):after {
        content: "/";
        margin: 0 8px;
        color: #9ca3af;
    }
</style>
@endsection