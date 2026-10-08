@extends('layouts.sidebar')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-start mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Preview Range Instruksi
            </h1>
            <div class="mt-2 space-y-1">
                <p class="text-gray-600">
                    <span class="font-medium">Test:</span> {{ $test->name }}
                </p>
                <p class="text-gray-600">
                    <span class="font-medium">Tipe Test:</span> {{ $typeTest->name }}
                </p>
                <p class="text-gray-600">
                    <span class="font-medium">Sub Test:</span> {{ $subTest->name }}
                </p>
            </div>
        </div>
        <a href="{{ route('sub-test.ranges.index', [$test->slug, $typeTest->slug, $subTest->slug]) }}" 
           class="px-5 py-2.5 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition flex items-center gap-2 shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Range
        </a>
    </div>
    <div class="mb-8 grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Range</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $ranges->count() }}</p>
                </div>
                <div class="p-3 bg-blue-50 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Soal</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $questionsWithNumber->count() }}</p>
                </div>
                <div class="p-3 bg-green-50 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Range Aktif</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">
                        {{ $ranges->where('is_active', true)->count() }}
                    </p>
                </div>
                <div class="p-3 bg-purple-50 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>
    @if($ranges->count() > 0)
        <div class="space-y-4" id="rangeAccordion">
            @foreach($ranges as $index => $range)
                @php
                    $startNumber = $questionIdToNumber[$range->start_number] ?? null;
                    $endNumber = $questionIdToNumber[$range->end_number] ?? null;
                    $questionCount = 0;
                    $rangeQuestions = collect();
                    
                    if ($startNumber && $endNumber) {
                        $rangeQuestions = $questionsWithNumber->filter(function($question) use ($startNumber, $endNumber, $questionIdToNumber) {
                            $questionNumber = $questionIdToNumber[$question->id] ?? null;
                            return $questionNumber && $questionNumber >= $startNumber && $questionNumber <= $endNumber;
                        });
                        $questionCount = $rangeQuestions->count();
                    }
                @endphp

                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    <button type="button" 
                            class="w-full px-6 py-5 text-left hover:bg-gray-50 transition-colors"
                            data-accordion-target="range-{{ $range->id }}"
                            aria-expanded="{{ $index === 0 ? 'true' : 'false' }}">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="flex flex-col items-center">
                                    <span class="text-sm text-gray-500">Order</span>
                                    <span class="text-2xl font-bold text-gray-800">{{ $range->order_number }}</span>
                                </div>
                                <div class="h-10 w-px bg-gray-300"></div>
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-1">
                                        <h3 class="text-lg font-semibold text-gray-800">
                                            Range Instruksi #{{ $range->order_number }}
                                        </h3>
                                        <span class="px-3 py-1 text-sm rounded-full font-medium {{ $range->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                            {{ $range->is_active ? '✓ Aktif' : '✗ Nonaktif' }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-4 text-sm text-gray-600">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                            </svg>
                                            <span>Soal {{ $startNumber ?? '?' }} - {{ $endNumber ?? '?' }}</span>
                                            @if($questionCount > 0)
                                                <span class="text-gray-400">({{ $questionCount }} soal)</span>
                                            @endif
                                        </div>
                                        
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                                            </svg>
                                            <span>ID: {{ $range->id }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-sm text-gray-500">
                                    {{ $questionCount }} soal
                                </span>
                                <svg class="w-5 h-5 text-gray-500 transform transition-transform duration-200"
                                     data-accordion-icon
                                     fill="none" 
                                     stroke="currentColor" 
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round" 
                                          stroke-linejoin="round" 
                                          stroke-width="2" 
                                          d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                    </button>
                    <div class="{{ $index === 0 ? '' : 'hidden' }} px-6 pb-6 border-t border-gray-200"
                         id="range-{{ $range->id }}"
                         data-accordion-content>
                        <div class="mt-6">
                            <div class="flex items-center gap-2 mb-3">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <h4 class="font-semibold text-gray-800">Instruksi</h4>
                            </div>
                            
                            @if($range->instruction && $range->instruction->description)
                                <div class="p-5 bg-gray-50 rounded-lg border border-gray-200">
                                    <div class="prose max-w-none text-gray-700">
                                        {!! $range->instruction->description !!}
                                    </div>
                                </div>
                            @else
                                <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                        </svg>
                                        <p class="text-yellow-700">
                                            Range ini tidak memiliki instruksi spesifik.
                                        </p>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="mt-8">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="text-lg font-semibold text-gray-800">
                                    Daftar Soal dalam Range
                                </h4>
                                <span class="px-3 py-1 bg-gray-100 text-gray-700 text-sm rounded-full font-medium">
                                    {{ $questionCount }} soal
                                </span>
                            </div>

                            @if($questionCount == 0)
                                <div class="p-8 text-center border-2 border-dashed border-gray-300 rounded-xl">
                                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <p class="text-gray-500 font-medium">Tidak ada soal dalam range ini</p>
                                    <p class="text-gray-400 text-sm mt-1">
                                        Kemungkinan ID soal tidak valid atau range belum diatur dengan benar
                                    </p>
                                </div>
                            @else
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($rangeQuestions as $question)
                                        <div class="border border-gray-200 rounded-lg overflow-hidden hover:border-blue-300 transition-colors bg-white">
                                            <div class="bg-gray-50 px-4 py-3 border-b">
                                                <div class="flex justify-between items-center">
                                                    <div class="flex items-center gap-3">
                                                        <span class="px-3 py-1 bg-blue-100 text-blue-700 text-sm font-semibold rounded">
                                                            Soal #{{ $question->display_number }}
                                                        </span>
                                                        @if($question->question_type == 'multiple_choice')
                                                            <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded">
                                                                Pilihan Ganda
                                                            </span>
                                                        @elseif($question->question_type == 'essay')
                                                            <span class="px-2 py-1 bg-purple-100 text-purple-700 text-xs rounded">
                                                                Essay
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <span class="text-xs text-gray-500">
                                                        ID: {{ $question->id }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="p-4">
                                                <div class="mb-3">
                                                    @if($question->question_text)
                                                        <div class="text-gray-700 line-clamp-2">
                                                            {!! Str::limit(strip_tags($question->question_text), 150) !!}
                                                        </div>
                                                    @elseif($question->question_image)
                                                        <div class="flex items-center gap-2 text-gray-600">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                            </svg>
                                                            Soal dengan gambar
                                                        </div>
                                                    @else
                                                        <p class="text-gray-500 italic">Tidak ada konten soal</p>
                                                    @endif
                                                </div>
                                                @if($question->options && count($question->options) > 0)
                                                    <div class="mt-3 pt-3 border-t border-gray-100">
                                                        <p class="text-xs text-gray-500 mb-2">Opsi Jawaban:</p>
                                                        <div class="grid grid-cols-2 gap-1">
                                                            @foreach($question->options as $key => $option)
                                                                @if($key < 4)
                                                                    <div class="flex items-center gap-2 p-1 hover:bg-gray-50 rounded">
                                                                        <span class="text-xs font-medium text-gray-600 min-w-[20px]">
                                                                            {{ chr(65 + $key) }}.
                                                                        </span>
                                                                        <span class="text-sm text-gray-700 truncate">
                                                                            {{ Str::limit($option['text'] ?? 'Option ' . ($key + 1), 25) }}
                                                                        </span>
                                                                        @if(isset($option['is_correct']) && $option['is_correct'])
                                                                            <span class="ml-auto text-green-600">
                                                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                                                </svg>
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                            @if(count($question->options) > 4)
                                                                <div class="col-span-2 text-center pt-1">
                                                                    <span class="text-xs text-gray-400">
                                                                        + {{ count($question->options) - 4 }} opsi lainnya
                                                                    </span>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endif
                                                <div class="mt-3 flex justify-between text-xs text-gray-500">
                                                    @if($question->time_limit)
                                                        <span class="flex items-center gap-1">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                            {{ $question->time_limit }} detik
                                                        </span>
                                                    @endif
                                                    @if($question->points)
                                                        <span class="flex items-center gap-1">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                            {{ $question->points }} poin
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <div class="flex items-center gap-2 mb-3">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <h5 class="text-sm font-medium text-gray-700">Informasi Teknis</h5>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <div class="bg-gray-50 p-3 rounded-lg">
                                    <div class="space-y-1">
                                        <p class="text-gray-600">
                                            <span class="font-medium">Start ID:</span> 
                                            {{ $range->start_number }}
                                            @if($startNumber)
                                                <span class="text-gray-400">(Soal #{{ $startNumber }})</span>
                                            @endif
                                        </p>
                                        <p class="text-gray-600">
                                            <span class="font-medium">End ID:</span> 
                                            {{ $range->end_number }}
                                            @if($endNumber)
                                                <span class="text-gray-400">(Soal #{{ $endNumber }})</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="bg-gray-50 p-3 rounded-lg">
                                    <div class="space-y-1">
                                        <p class="text-gray-600">
                                            <span class="font-medium">Tanggal dibuat:</span> 
                                            {{ $range->created_at->format('d/m/Y H:i') }}
                                        </p>
                                        <p class="text-gray-600">
                                            <span class="font-medium">Terakhir update:</span> 
                                            {{ $range->updated_at->format('d/m/Y H:i') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-4 p-4 bg-gray-50 rounded-lg">
            <div class="text-sm text-gray-600">
                <span class="font-medium">Keterangan:</span>
                <span class="px-2 py-1 mx-1 bg-green-100 text-green-700 text-xs rounded">Aktif</span>
                <span class="px-2 py-1 mx-1 bg-gray-100 text-gray-600 text-xs rounded">Nonaktif</span>
            </div>
            
            <div class="flex gap-2">
                <button type="button" 
                        id="expandAllBtn"
                        class="px-4 py-2 text-sm bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition">
                    Buka Semua
                </button>
                <button type="button" 
                        id="collapseAllBtn"
                        class="px-4 py-2 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                    Tutup Semua
                </button>
            </div>
        </div>

    @else
        <div class="text-center py-12">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 text-blue-600 mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">Belum Ada Range Instruksi</h3>
            <p class="text-gray-500 mb-6 max-w-md mx-auto">
                Anda belum membuat range instruksi untuk subtest ini. 
                Range instruksi digunakan untuk mengelompokkan soal dan menampilkan instruksi khusus.
            </p>
            <a href="{{ route('sub-test.ranges.index', [$test->slug, $typeTest->slug, $subTest->slug]) }}" 
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Buat Range Pertama
            </a>
        </div>
    @endif
</div>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.prose {
    max-width: none !important;
}

.prose p {
    margin-top: 0.5em;
    margin-bottom: 0.5em;
}

.prose img {
    max-width: 100%;
    height: auto;
    border-radius: 0.375rem;
}

[data-accordion-content] {
    transition: all 0.3s ease-in-out;
}

[data-accordion-target]:hover {
    background-color: rgba(249, 250, 251, 0.8);
}

[data-accordion-target][aria-expanded="true"] [data-accordion-icon] {
    transform: rotate(180deg);
}

.transition-all {
    transition-property: all;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 150ms;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const accordionButtons = document.querySelectorAll('[data-accordion-target]');
    accordionButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-accordion-target');
            const targetContent = document.getElementById(targetId);
            const isExpanded = this.getAttribute('aria-expanded') === 'true';
            const accordionIcon = this.querySelector('[data-accordion-icon]');
            if (isExpanded) {
                targetContent.classList.add('hidden');
                this.setAttribute('aria-expanded', 'false');
                if (accordionIcon) {
                    accordionIcon.classList.remove('rotate-180');
                }
            } else {
                targetContent.classList.remove('hidden');
                this.setAttribute('aria-expanded', 'true');
                if (accordionIcon) {
                    accordionIcon.classList.add('rotate-180');
                }
            }
        });
    });
    const expandAllBtn = document.getElementById('expandAllBtn');
    if (expandAllBtn) {
        expandAllBtn.addEventListener('click', function() {
            accordionButtons.forEach(button => {
                const targetId = button.getAttribute('data-accordion-target');
                const targetContent = document.getElementById(targetId);
                const accordionIcon = button.querySelector('[data-accordion-icon]');
                
                targetContent.classList.remove('hidden');
                button.setAttribute('aria-expanded', 'true');
                if (accordionIcon) {
                    accordionIcon.classList.add('rotate-180');
                }
            });
        });
    }
    const collapseAllBtn = document.getElementById('collapseAllBtn');
    if (collapseAllBtn) {
        collapseAllBtn.addEventListener('click', function() {
            accordionButtons.forEach(button => {
                const targetId = button.getAttribute('data-accordion-target');
                const targetContent = document.getElementById(targetId);
                const accordionIcon = button.querySelector('[data-accordion-icon]');
                
                targetContent.classList.add('hidden');
                button.setAttribute('aria-expanded', 'false');
                if (accordionIcon) {
                    accordionIcon.classList.remove('rotate-180');
                }
            });
        });
    }
    if (accordionButtons.length === 1) {
        const firstButton = accordionButtons[0];
        const targetId = firstButton.getAttribute('data-accordion-target');
        const targetContent = document.getElementById(targetId);
        const accordionIcon = firstButton.querySelector('[data-accordion-icon]');
        
        targetContent.classList.remove('hidden');
        firstButton.setAttribute('aria-expanded', 'true');
        if (accordionIcon) {
            accordionIcon.classList.add('rotate-180');
        }
    }
    accordionButtons.forEach(button => {
        button.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                this.click();
            }
        });
    });
    accordionButtons.forEach(button => {
        button.addEventListener('click', function() {
            const isExpanded = this.getAttribute('aria-expanded') === 'true';
            if (isExpanded) {
                setTimeout(() => {
                    this.scrollIntoView({
                        behavior: 'smooth',
                        block: 'nearest'
                    });
                }, 150);
            }
        });
    });
});
</script>
@endsection