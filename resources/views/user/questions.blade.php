@extends('layouts.navbar')
@section('content')
<style>
* {
    user-select: none;
}
</style>
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white py-8">
    <div class="container mx-auto px-4 max-w-4xl">
        <div class="mb-8">
            <nav class="flex mb-4" aria-label="Breadcrumb">
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
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            <a href="{{ route('user.sub-tests', [$test->slug, $typeTest->slug]) }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-red-600 transition-colors duration-200 md:ml-2">
                                {{ $typeTest->name }}
                            </a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            <span class="ml-1 text-sm font-medium text-red-600 md:ml-2">
                                {{ $subTest->name }}
                            </span>
                        </div>
                    </li>
                </ol>
            </nav>
            <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">{{ $subTest->name }}</h1>
                        <p class="text-gray-600 mt-1">{{ $subTest->description }}</p>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-gray-500 mb-1">Pertanyaan</div>
                        <div class="text-2xl font-bold text-red-600">
                            <span id="currentQuestion">{{ $currentQuestionIndex }}</span>/{{ $totalQuestions }}
                        </div>
                    </div>
                </div>
                
                @if($selectedRange)
                <div class="mb-6 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border border-blue-200">
                    <div class="flex items-center mb-2">
                        <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="font-semibold text-blue-800">Range {{ $rangeProgress['range_index'] + 1 }} of {{ $rangeProgress['total_ranges'] }}</h3>
                    </div>
                    <div class="mb-2">
                        <div class="flex justify-between text-sm text-blue-700 mb-1">
                            <span>Progress in this range</span>
                        </div>
                    </div>
                    <div id="selectedRangeDuration" class="text-sm text-blue-600 mt-2"></div>
                    @if(!$rangeProgress['is_last_range'])
                    <p class="text-sm text-blue-600 mt-2">
                        <span class="font-medium">Catatan:</span> Selesaikan semua pertanyaan dalam range ini untuk melanjutkan ke range berikutnya.
                    </p>
                    @endif
                </div>
                @endif
                
                @php
                    $answeredCount = $answeredInCurrentRange ?? 0;
                    $progressPercentage = $totalQuestions > 0 ? ($answeredCount / $totalQuestions) * 100 : 0;
                @endphp
                
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div id="progressBar" class="bg-gradient-to-r from-red-500 to-red-600 h-3 rounded-full transition-all duration-500" 
                         style="width: {{ $progressPercentage }}%"></div>
                </div>
                <div class="flex justify-between text-xs text-gray-500 mt-2">
                    <span>Mulai</span>
                    <span id="progressPercentage">{{ number_format($progressPercentage, 0) }}% Selesai</span>
                    <span>Selesai</span>
                </div>
                <div class="mt-4 grid grid-cols-3 gap-2 text-center">
                    <div class="bg-red-50 p-2 rounded-lg">
                        <div class="text-sm font-medium text-red-700">Dijawab</div>
                        <div class="text-xl font-bold text-red-600" id="answeredCount">{{ $answeredCount }}</div>
                    </div>
                    <div class="bg-gray-50 p-2 rounded-lg">
                        <div class="text-sm font-medium text-gray-700">Belum Dijawab</div>
                        <div class="text-xl font-bold text-gray-600" id="unansweredCount">{{ $totalQuestions - $answeredCount }}</div>
                    </div>
                    <div class="bg-blue-50 p-2 rounded-lg">
                        <div class="text-sm font-medium text-blue-700">Total</div>
                        <div class="text-xl font-bold text-blue-600">{{ $totalQuestions }}</div>
                    </div>
                </div>
            </div>
            @if($test->name !== 'Tes Speed Accuracy')
            <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100 mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Navigasi Pertanyaan</h3>
                <div class="flex flex-wrap gap-2" id="questionDotsContainer">
                    @if(isset($questions) && $questions->count() > 0)
                        @php
                            $currentRangeQuestionNumbers = [];
                            foreach ($questions as $index => $question) {
                                $currentRangeQuestionNumbers[$question->id] = $index + 1;
                            }
                        @endphp
                        @foreach($questions as $index => $question)
                        <a href="{{ route('user.questions', [
                            'test' => $test->slug,
                            'typeTest' => $typeTest->slug,
                            'subTest' => $subTest->slug,
                            'question' => $questionIdToNumber[$question->id] ?? ($index + 1),
                            'range_id' => $selectedRange->id ?? ''
                        ]) }}" 
                        class="w-10 h-10 flex items-center justify-center rounded-lg border transition-all duration-200 question-dot"
                        data-question-number="{{ $index + 1 }}"
                        id="dot-{{ $index + 1 }}"
                        title="Pertanyaan {{ $index + 1 }}">
                            {{ $index + 1 }}
                        </a>
                        @endforeach
                    @else
                        <p class="text-gray-500">Tidak ada pertanyaan tersedia.</p>
                    @endif
                </div>
            </div>
            @endif
        </div>
        <div id="noAnswerModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 mb-4">
                        <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                    </div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900 text-center mb-2">Tidak ada jawaban yang dipilih</h3>
                    <div class="px-7 py-4">
                        <p class="text-sm text-gray-500 mb-4 text-center">
                            Kamu tidak memilih jawaban untuk pertanyaan ini. Apakah kamu ingin melewati pertanyaan ini dan melanjutkan ke pertanyaan selanjutnya?
                        </p>
                        <div class="flex justify-center space-x-4">
                            <button type="button" id="skipQuestionBtn" 
                                    class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                                Lewati Pertanyaan
                            </button>
                            <button type="button" id="stayOnQuestionBtn" 
                                    class="px-4 py-2 bg-gray-200 text-gray-700 text-base font-medium rounded-md shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500">
                                Pilih Jawaban
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="confirmSubmitModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 mb-4">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900 text-center mb-2" id="submitModalTitle">
                        @if($selectedRange && !$rangeProgress['is_last_range'])
                            Range Selesai
                        @else
                            Kirim Semua Jawaban
                        @endif
                    </h3>
                    <div class="px-7 py-4">
                        <p class="text-sm text-gray-500 mb-3 text-center" id="submitModalText">
                            @if($selectedRange && !$rangeProgress['is_last_range'])
                                Kamu telah menyelesaikan semua pertanyaan dalam Range {{ $rangeProgress['range_index'] + 1 }}. 
                                Siap untuk melanjutkan ke range selanjutnya?
                            @else
                                Kamu akan mengirimkan semua jawabanmu. Setelah dikirim, kamu tidak dapat mengubahnya. 
                                Apakah kamu yakin ingin melanjutkan?
                            @endif
                        </p>
                        <div id="unansweredList" class="hidden mb-3">
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                                <div class="flex items-center text-yellow-700 mb-2">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                    </svg>
                                    <span class="font-semibold text-sm">Pertanyaan Belum Dijawab:</span>
                                </div>
                                <div id="unansweredNumbers" class="text-sm text-yellow-600 max-h-32 overflow-y-auto"></div>
                            </div>
                        </div>
                        
                        <div class="flex justify-center space-x-4">
                            <button type="button" id="confirmSubmitBtn" 
                                    class="px-4 py-2 bg-green-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                                @if($selectedRange && !$rangeProgress['is_last_range'])
                                    Lanjut ke Range Berikutnya
                                @else
                                    Ya, Kirim Semua
                                @endif
                            </button>
                            <button type="button" id="cancelSubmitBtn" 
                                    class="px-4 py-2 bg-gray-200 text-gray-700 text-base font-medium rounded-md shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500">
                                Batal
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="rangeCompleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900 text-center mb-2">Range Selesai!</h3>
                    <div class="px-7 py-4">
                        <p class="text-sm text-gray-500 mb-4 text-center">
                            Selamat! Kamu telah menyelesaikan semua pertanyaan dalam range ini. 
                            <span class="font-semibold text-green-600">Kamu akan diarahkan ke range berikutnya secara otomatis.</span>
                        </p>
                        <div class="flex justify-center">
                            <div class="inline-flex items-center px-4 py-2 bg-green-100 text-green-700 rounded-lg">
                                <svg class="w-5 h-5 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Mengarahkan...
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <form id="questionForm" action="{{ route('user.questions.submit-single', [$test->slug, $typeTest->slug, $subTest->slug]) }}" method="POST">
            @csrf
            <input type="hidden" id="currentQuestionIdInput" name="question_id" value="{{ $currentQuestion->id }}">
            <input type="hidden" id="totalDuration" value="{{ $subTest->duration }}">
            <input type="hidden" id="timeRemaining" name="time_remaining" value="">
            <input type="hidden" name="range_id" value="{{ $selectedRange->id ?? '' }}">
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden mb-8">
                <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-red-50 to-white">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div>
                                <h2 id="currentQuestionHeading" class="text-lg font-semibold text-gray-800">Pertanyaan #{{ $currentQuestionIndex }}</h2>
                                <p class="text-sm text-gray-600">Pilih jawaban terbaik</p>
                            </div>
                        </div>
                        <div class="hidden md:flex items-center px-4 py-2 bg-red-50 rounded-lg">
                            <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <div class="text-sm font-medium text-red-600">Waktu Tersisa</div>
                                <div class="text-xs text-red-500" id="timer">--:--</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-6 md:p-8">
                    <div id="questionContent" aria-live="polite">
                        @include('user.partials.question-content', [
                            'currentQuestion' => $currentQuestion,
                            'currentQuestionIndex' => $currentQuestionIndex,
                            'previousAnswer' => $previousAnswer,
                        ])
                    </div>
                    <div id="questionNavigationLoading" class="hidden mt-4 text-center text-sm text-gray-500" role="status">
                        Memuat pertanyaan...
                    </div>
                    <div id="questionNavigationError" class="hidden mt-4 text-center text-sm text-red-600" role="alert">
                        Pertanyaan gagal dimuat. Jawaban Anda tetap tersimpan. Silakan coba lagi.
                    </div>
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-gray-400 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-sm text-gray-600">
                                <span class="font-medium text-gray-800">Catatan:</span> 
                                @if($selectedRange && !$rangeProgress['is_last_range'])
                                    Kamu harus menyelesaikan semua pertanyaan dalam range ini untuk membuka range selanjutnya.
                                @elseif($selectedRange && $rangeProgress['is_last_range'])
                                    Ini adalah range terakhir. Setelah menyelesaikan semua pertanyaan, kamu bisa mengirimkan jawaban.
                                @else
                                    Kamu bisa melewatkan pertanyaan dan kembali lagi nanti. Pastikan untuk meninjau semua pertanyaan sebelum mengirimkan jawaban.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-5 bg-gray-50 border-t border-gray-200">
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                        @if($test->name !== 'Tes Speed Accuracy')
                        <a href="#" id="previousQuestionBtn" @class([
                            'inline-flex items-center px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all duration-200 w-full sm:w-auto justify-center',
                            'hidden' => $currentQuestionIndex <= 1,
                        ])>
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Pertanyaan Sebelumnya
                        </a>
                        @endif
                        <button type="button" 
                                id="nextQuestionBtn"
                                @class([
                                    'inline-flex items-center px-8 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white font-semibold rounded-lg hover:from-red-700 hover:to-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5 w-full sm:w-auto justify-center',
                                    'hidden' => $currentQuestionIndex >= $totalQuestions,
                                ])>
                            <span>Pertanyaan Berikutnya</span>
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </button>
                        <button type="button" 
                                id="submitAllBtn"
                                @class([
                                    'inline-flex items-center px-8 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold rounded-lg hover:from-green-700 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5 w-full sm:w-auto justify-center',
                                    'hidden' => $currentQuestionIndex < $totalQuestions,
                                ])>
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            @if($selectedRange && !$rangeProgress['is_last_range'])
                                Selesaikan Range
                            @else
                                Kirim Semua Jawaban
                            @endif
                        </button>
                    </div>
                    <div class="mt-4 text-center">
                        <div class="inline-flex items-center text-sm text-gray-500" id="saveStatus">
                            @if($previousAnswer)
                            <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Jawaban tersimpan
                            @else
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Pilih jawaban untuk menyimpan
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<div id="errorModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 text-center mb-2">Kesalahan Pengiriman</h3>
            <div class="px-7 py-4">
                <p class="text-sm text-gray-500 mb-4 text-center">
                    Terjadi kesalahan saat menyimpan jawaban Anda. Namun, jawaban Anda telah disimpan secara lokal.
                </p>
                <div class="flex justify-center space-x-4">
                    <button type="button" id="retryButton" 
                            class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                        Coba Kirim Ulang
                    </button>
                    <button type="button" id="continueButton" 
                            class="px-4 py-2 bg-gray-200 text-gray-700 text-base font-medium rounded-md shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500">
                        Lanjutkan Saja
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    const MAX_FOCUS_LOSS = 3; 
    let focusLossCount = 0;
    function showToast(message, duration = 3000) {
        const id = 'focus-toast';
        let t = document.getElementById(id);
        if (t) {
            t.textContent = message;
            t.classList.add('opacity-100');
        } else {
            t = document.createElement('div');
            t.id = id;
            t.className = 'fixed top-4 right-4 bg-yellow-600 text-white px-4 py-2 rounded shadow z-50';
            t.style.opacity = '0';
            t.style.transition = 'opacity .2s';
            t.textContent = message;
            document.body.appendChild(t);
            requestAnimationFrame(() => t.style.opacity = '1');
        }
        clearTimeout(t._timeout);
        t._timeout = setTimeout(() => {
            t.style.opacity = '0';
            setTimeout(() => t.remove(), 250);
        }, duration);
    }
    async function handleExcessFocusLoss() {
        showToast('Terlalu sering berpindah tab — jawaban akan dikirim otomatis.', 5000);
        try {
            await submitAllAnswersAuto();
        } catch (e) {
            window.location.href = "{{ route('user.sub-tests', ['test' => $test->slug, 'typeTest' => $typeTest->slug]) }}?flagged=focus_loss";
        }
    }
    document.addEventListener('visibilitychange', () => {
        if (document.hidden) {
            focusLossCount++;
            showToast(`Anda meninggalkan tab (${focusLossCount}/${MAX_FOCUS_LOSS})`, 4000);
            if (focusLossCount >= MAX_FOCUS_LOSS) handleExcessFocusLoss();
        }
    });
    window.addEventListener('blur', () => {
        focusLossCount++;
        showToast(`Anda kehilangan fokus (${focusLossCount}/${MAX_FOCUS_LOSS})`, 3000);
        if (focusLossCount >= MAX_FOCUS_LOSS) handleExcessFocusLoss();
    });
    document.addEventListener('keydown', (e) => {
        const key = e.key.toLowerCase();
        if ((e.ctrlKey || e.metaKey) && (key === 't' || key === 'n' || key === 'w' || key === 'r')) {
            e.preventDefault();
            showToast('Membuka tab/jendela baru diblokir selama tes.', 2000);
            return;
        }
        if (e.ctrlKey && (key === 'tab')) {
            e.preventDefault();
            showToast('Perpindahan tab diblokir.', 2000);
        }
        if (e.key === 'f5') {
            e.preventDefault();
            showToast('Refresh diblokir selama tes.', 2000);
        }
    }, { passive: false });
    
    document.addEventListener('auxclick', (e) => {
        if (e.button === 1) {
            e.preventDefault();
            showToast('Membuka link di tab baru diblokir.', 2000);
        }
    });
    document.addEventListener('click', (e) => {
        const el = e.target.closest && e.target.closest('a');
        if (!el) return;
        if (e.ctrlKey || e.metaKey) {
            e.preventDefault();
            showToast('Membuka link di tab baru diblokir.', 2000);
        }
    }, true);
    window.__focusLossCount = () => focusLossCount;
})();
</script>

<script>
document.addEventListener("contextmenu", function(e) {
    e.preventDefault();
});
document.addEventListener("copy", (e) => e.preventDefault());
document.addEventListener("paste", (e) => e.preventDefault());
document.addEventListener("cut", (e) => e.preventDefault());
document.addEventListener("keydown", function(e) {
    if (e.ctrlKey && (e.key === "c" || e.key === "v" || e.key === "x")) {
        e.preventDefault();
        return false;
    }
    if (e.ctrlKey && e.key === "a") {
        e.preventDefault();
        return false;
    }
});
</script>

<script>
    @php
    $allRanges = $allRanges ?? collect([]);
    @endphp
    let isSubmitting = false;
    let hasUnsavedChanges = false;
    let currentQuestionId = {{ $currentQuestion->id }};
    let currentQuestionNumber = {{ $currentQuestionIndex }};
    let totalQuestions = {{ $totalQuestions }};
    const examState = {
        answers: Object.create(null),
        answeredQuestions: new Set(),
    };
    let isLastRange = {{ isset($rangeProgress) && $rangeProgress['is_last_range'] ? 'true' : 'false' }};
    let currentRangeId = {{ $selectedRange->id ?? 'null' }};
    let nextRangeId = null;
    let isInternalNavigation = false;
    let isQuestionNavigating = false;
    let questionNavigationController = null;
    let questionNavigationRequestId = 0;
    let isTimeExpired = false;
    
    @if(isset($allRanges) && isset($selectedRange))
        @php
            $currentIndex = $allRanges->search(function($range) use ($selectedRange) {
                return $range->id == $selectedRange->id;
            });
            if ($currentIndex !== false && isset($allRanges[$currentIndex + 1])) {
                $nextRange = $allRanges[$currentIndex + 1];
            }
        @endphp
        @if(isset($nextRange))
            nextRangeId = {{ $nextRange->id }};
        @endif
    @endif

    let timerInterval = null;
    let timeLeft = 0;
    const deadlineExpiresAt = @json($subTestExpiresAt?->toIso8601String());
    const serverNow = @json($serverNow->toIso8601String());
    const serverNowMs = Date.parse(serverNow);
    const deadlineMs = Date.parse(deadlineExpiresAt);
    const initialClientNowMs = Date.now();
    const serverClockOffsetMs = serverNowMs - initialClientNowMs;
    const serverTimeAtSyncMs = initialClientNowMs + serverClockOffsetMs;
    const monotonicTimeAtSyncMs = performance.now();

    const subTestDuration = {{ $subTest->duration ?? 15 }};
    const QUESTION_NUMBER_MAP = @json($questionIdToNumber ?? []);
    const ALL_RANGES = @json($allRanges->map(function($r){ return ['id'=>$r->id,'start'=>$r->start_number,'end'=>$r->end_number]; }));
    const TOTAL_ALL_QUESTIONS = {{ $totalAllQuestions ?? 0 }};
    const PER_QUESTION_SECONDS = TOTAL_ALL_QUESTIONS > 0 ? (subTestDuration * 60) / TOTAL_ALL_QUESTIONS : 60;
    const QUESTION_DATA_URL = "{{ route('user.questions.data', ['test' => $test->slug, 'typeTest' => $typeTest->slug, 'subTest' => $subTest->slug]) }}";

    function getExamStoragePrefix() {
        return `exam_{{ $test->id ?? 0 }}_{{ $subTest->id ?? 0 }}_`;
    }
    function getAnswerStorageKey(questionId) {
        return `${getExamStoragePrefix()}answer_${questionId}`;
    }
    function isExamStorageKey(key) {
        return typeof key === 'string' && key.startsWith(getExamStoragePrefix());
    }
    function clearExamAnswerStorage() {
        const answerPrefix = `${getExamStoragePrefix()}answer_`;
        for (let i = localStorage.length - 1; i >= 0; i--) {
            const key = localStorage.key(i);
            if (isExamStorageKey(key) && key.startsWith(answerPrefix)) {
                localStorage.removeItem(key);
            }
        }
        examState.answers = Object.create(null);
        examState.answeredQuestions.clear();
    }
    function loadExamStateFromStorage() {
        examState.answers = Object.create(null);
        examState.answeredQuestions.clear();
        const answerPrefix = `${getExamStoragePrefix()}answer_`;
        for (let i = localStorage.length - 1; i >= 0; i--) {
            const key = localStorage.key(i);
            if (!isExamStorageKey(key) || !key.startsWith(answerPrefix)) continue;
            try {
                const answerData = JSON.parse(localStorage.getItem(key));
                if (!answerData || !answerData.question_id) continue;
                examState.answers[answerData.question_id] = answerData;
                if (answerData.answer !== null && answerData.answer !== '') {
                    const questionNumber = getDisplayQuestionNumber(answerData.question_id);
                    if (questionNumber) examState.answeredQuestions.add(questionNumber);
                }
            } catch (e) {
                console.error('Error loading from localStorage:', e);
            }
        }
    }
    function getDisplayQuestionNumber(questionId) {
        const questionNumber = QUESTION_NUMBER_MAP[questionId];
        if (!questionNumber) return null;
        if (!currentRangeId) return questionNumber;
        const range = ALL_RANGES.find(item => item.id == currentRangeId);
        const rangeStartNumber = range ? QUESTION_NUMBER_MAP[range.start] : null;
        const rangeEndNumber = range ? QUESTION_NUMBER_MAP[range.end] : null;
        if (rangeStartNumber && rangeEndNumber && (questionNumber < rangeStartNumber || questionNumber > rangeEndNumber)) {
            return null;
        }
        return rangeStartNumber ? questionNumber - rangeStartNumber + 1 : questionNumber;
    }

    function countQuestionsInRange(startQId, endQId) {
        const startNum = QUESTION_NUMBER_MAP[startQId] ?? null;
        const endNum = QUESTION_NUMBER_MAP[endQId] ?? null;
        if (!startNum || !endNum) return 0;
        let cnt = 0;
        for (const qId in QUESTION_NUMBER_MAP) {
            const num = QUESTION_NUMBER_MAP[qId];
            if (num >= startNum && num <= endNum) cnt++;
        }
        return cnt;
    }

    function getRangeDurationSeconds(rangeId) {
        if (!rangeId) return subTestDuration * 60;
        const range = ALL_RANGES.find(item => item.id == rangeId);
        if (!range) return subTestDuration * 60;
        return Math.max(1, Math.round(countQuestionsInRange(range.start, range.end) * PER_QUESTION_SECONDS));
    }

    function getCurrentServerTimeMs() {
        return serverTimeAtSyncMs + (performance.now() - monotonicTimeAtSyncMs);
    }

    function getRemainingSeconds() {
        if (!Number.isFinite(deadlineMs) || !Number.isFinite(serverNowMs)) return 0;
        return Math.max(0, Math.ceil((deadlineMs - getCurrentServerTimeMs()) / 1000));
    }
    function setQuestionNavigationLoading(isLoading) {
        isQuestionNavigating = isLoading;
        const questionContent = document.getElementById('questionContent');
        const loadingIndicator = document.getElementById('questionNavigationLoading');
        const errorIndicator = document.getElementById('questionNavigationError');
        if (questionContent) {
            questionContent.classList.toggle('opacity-50', isLoading);
            questionContent.classList.toggle('pointer-events-none', isLoading);
            questionContent.setAttribute('aria-busy', isLoading ? 'true' : 'false');
        }
        if (loadingIndicator) loadingIndicator.classList.toggle('hidden', !isLoading);
        if (isLoading && errorIndicator) errorIndicator.classList.add('hidden');

        ['previousQuestionBtn', 'nextQuestionBtn', 'submitAllBtn'].forEach(id => {
            const button = document.getElementById(id);
            if (button) button.disabled = isLoading || isTimeExpired;
        });
        document.querySelectorAll('.question-dot').forEach(dot => {
            const disabled = isLoading || isTimeExpired;
            dot.setAttribute('aria-disabled', disabled ? 'true' : 'false');
            dot.classList.toggle('pointer-events-none', disabled);
            dot.classList.toggle('opacity-50', disabled);
        });
    }

    function updateQuestionNavigationControls() {
        const previousButton = document.getElementById('previousQuestionBtn');
        const nextButton = document.getElementById('nextQuestionBtn');
        const submitButton = document.getElementById('submitAllBtn');
        if (previousButton) previousButton.classList.toggle('hidden', currentQuestionNumber <= 1);
        if (nextButton) nextButton.classList.toggle('hidden', currentQuestionNumber >= totalQuestions);
        if (submitButton) submitButton.classList.toggle('hidden', currentQuestionNumber < totalQuestions);
        [previousButton, nextButton, submitButton].forEach(button => {
            if (button) button.disabled = isTimeExpired || isQuestionNavigating;
        });
        document.querySelectorAll('.question-dot').forEach(dot => {
            dot.setAttribute('aria-disabled', isTimeExpired ? 'true' : 'false');
            dot.classList.toggle('pointer-events-none', isTimeExpired);
            dot.classList.toggle('opacity-50', isTimeExpired);
        });
    }

    function updateQuestionUrl(questionNumber, rangeId, method = 'push') {
        const url = new URL(window.location.href);
        url.searchParams.set('question', questionNumber);
        if (rangeId) url.searchParams.set('range_id', rangeId);
        else url.searchParams.delete('range_id');
        const state = { question: questionNumber, rangeId: rangeId || null };
        if (method === 'replace') history.replaceState(state, '', url);
        else if (method === 'push') history.pushState(state, '', url);
    }

    async function navigateToQuestion(questionNumber, rangeId = currentRangeId, options = {}) {
        const requestedQuestion = Number(questionNumber);
        const normalizedRangeId = rangeId === '' || rangeId === null || typeof rangeId === 'undefined'
            ? null
            : Number(rangeId);
        if (!Number.isInteger(requestedQuestion) || requestedQuestion < 1) return false;
        if (isTimeExpired || getRemainingSeconds() <= 0) {
            handleTimeUp();
            if (options.history === 'none') {
                updateQuestionUrl(QUESTION_NUMBER_MAP[currentQuestionId], currentRangeId, 'replace');
            }
            return false;
        }
        if (isQuestionNavigating && !options.force) return false;

        if (isQuestionNavigating && questionNavigationController) {
            questionNavigationController.abort();
        }

        const currentGlobalQuestion = QUESTION_NUMBER_MAP[currentQuestionId];
        if (requestedQuestion === Number(currentGlobalQuestion)
            && String(normalizedRangeId ?? '') === String(currentRangeId ?? '')) {
            return true;
        }

        saveAnswerToLocalStorage(false);
        setQuestionNavigationLoading(true);

        const requestId = ++questionNavigationRequestId;
        const controller = new AbortController();
        questionNavigationController = controller;
        try {
            const requestUrl = new URL(QUESTION_DATA_URL, window.location.origin);
            requestUrl.searchParams.set('question', requestedQuestion);
            if (normalizedRangeId) requestUrl.searchParams.set('range_id', normalizedRangeId);

            const response = await fetch(requestUrl, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                signal: controller.signal,
            });
            if (response.status === 409) {
                handleTimeUp();
                return false;
            }
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            const questionData = await response.json();
            if (!questionData.html || !questionData.question_id || requestId !== questionNavigationRequestId) {
                throw new Error('Invalid question response');
            }
            if (!questionData.expires_at || Date.parse(questionData.expires_at) !== deadlineMs) {
                throw new Error('Server mengirim deadline subtest yang berbeda.');
            }

            const questionContent = document.getElementById('questionContent');
            questionContent.innerHTML = questionData.html;
            currentQuestionId = questionData.question_id;
            currentQuestionNumber = questionData.question_index;
            currentRangeId = normalizedRangeId;
            document.getElementById('currentQuestionIdInput').value = currentQuestionId;
            document.getElementById('currentQuestionHeading').textContent = `Pertanyaan #${currentQuestionNumber}`;
            const currentQuestionLabel = document.getElementById('currentQuestion');
            if (currentQuestionLabel) currentQuestionLabel.textContent = currentQuestionNumber;

            const answerData = examState.answers[currentQuestionId];
            const answerInputs = Array.from(questionContent.querySelectorAll('input[name="answer"]'));
            if (answerData) {
                answerInputs.forEach(input => {
                    input.checked = String(input.value) === String(answerData.answer);
                });
            }
            const selectedInput = answerInputs.find(input => input.checked);
            if (selectedInput) {
                if (!answerData) {
                    const restoredAnswer = {
                        question_id: currentQuestionId,
                        answer: selectedInput.value,
                        skipped: false,
                        timestamp: new Date().toISOString(),
                    };
                    examState.answers[currentQuestionId] = restoredAnswer;
                    localStorage.setItem(getAnswerStorageKey(currentQuestionId), JSON.stringify(restoredAnswer));
                }
                examState.answeredQuestions.add(currentQuestionNumber);
            } else {
                examState.answeredQuestions.delete(currentQuestionNumber);
            }

            bindQuestionAnswerEvents();
            updateQuestionDots();
            updateQuestionNavigationControls();
            updateSaveStatus(selectedInput ? 'saved' : '');
            updateTimerDisplay();
            if (options.history !== 'none') updateQuestionUrl(requestedQuestion, normalizedRangeId, options.history || 'push');
            return true;
        } catch (error) {
            if (error.name !== 'AbortError' && !isTimeExpired) {
                console.error('Question navigation error:', error);
                const errorIndicator = document.getElementById('questionNavigationError');
                if (errorIndicator) errorIndicator.classList.remove('hidden');
            }
            if (options.history === 'none') {
                updateQuestionUrl(currentGlobalQuestion, currentRangeId, 'replace');
            }
            return false;
        } finally {
            if (requestId === questionNavigationRequestId) {
                questionNavigationController = null;
                setQuestionNavigationLoading(false);
            }
        }
    }

    function formatTime(seconds) {
        seconds = Math.max(0, Math.floor(Number(seconds) || 0));
        const minutes = Math.floor(seconds / 60);
        const secs = seconds % 60;
        return `${minutes.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
    }
    function updateTimerDisplay() {
        timeLeft = getRemainingSeconds();
        const timerElement = document.getElementById('timer');
        const timeRemainingInput = document.getElementById('timeRemaining');
        
        if (timerElement) {
            timerElement.textContent = formatTime(timeLeft);
        }
        
        if (timeRemainingInput) {
            timeRemainingInput.value = timeLeft;
        }
    }
    function startTimer() {
        if (timerInterval || isTimeExpired) return;
        const tick = () => {
            const previousTimeLeft = timeLeft;
            updateTimerDisplay();
            if (timeLeft === 0) {
                clearInterval(timerInterval);
                timerInterval = null;
                handleTimeUp();
                return;
            }
            if (previousTimeLeft > timeLeft && [300, 60, 30, 10].includes(timeLeft)) {
                showTimeWarning(timeLeft);
            }
        };
        tick();
        if (!isTimeExpired) timerInterval = setInterval(tick, 1000);
    }
    function handleTimeUp() {
        if (isTimeExpired) return;
        saveAnswerToLocalStorage(false);
        isTimeExpired = true;
        if (timerInterval) clearInterval(timerInterval);
        timerInterval = null;
        timeLeft = 0;
        updateTimerDisplay();
        const navigationError = document.getElementById('questionNavigationError');
        if (navigationError) {
            navigationError.textContent = 'Waktu pengerjaan telah habis. Jawaban yang sudah tersimpan sedang difinalisasi.';
            navigationError.classList.remove('hidden');
        }
        document.querySelectorAll('#questionContent input[name="answer"]').forEach(input => input.disabled = true);
        document.getElementById('confirmSubmitModal')?.classList.add('hidden');
        updateQuestionNavigationControls();
        submitAllAnswersAuto();
    }
    async function submitAllAnswersAuto() {
        if (isSubmitting) return;
        
        isSubmitting = true;
        const pendingAnswers = {};
        Object.values(examState.answers).forEach(answerData => {
            if (answerData?.question_id && answerData.answer !== null && answerData.answer !== '') {
                pendingAnswers[answerData.question_id] = answerData.answer;
            }
        });
        const currentAnswer = document.querySelector('input[name="answer"]:checked');
        if (currentAnswer && !pendingAnswers[currentQuestionId]) {
            pendingAnswers[currentQuestionId] = currentAnswer.value;
        }
        try {
            const formData = new FormData();
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            formData.append('_token', csrfToken);
            formData.append('time_up', 'true');
            formData.append('auto_submit', 'true');
            
            Object.keys(pendingAnswers).forEach(questionId => {
                formData.append(`answers[${questionId}]`, pendingAnswers[questionId] || '');
            });
            
            const submitUrl = "{{ route('user.questions.submit-all', [$test->slug, $typeTest->slug, $subTest->slug]) }}";
            const response = await fetch(submitUrl, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            if (!response.ok) {
                const error = new Error(`HTTP error! status: ${response.status}`);
                error.status = response.status;
                throw error;
            }
            clearExamAnswerStorage();
            window.location.href = "{{ route('user.sub-tests', ['test' => $test->slug, 'typeTest' => $typeTest->slug]) }}?time_up=true";
            
        } catch (error) {
            console.error('Auto submit error:', error);
            isSubmitting = false;
            showErrorModal(error.status === 409
                ? 'Waktu telah habis. Jawaban lokal tetap tersimpan; coba kirim ulang untuk menyelesaikan subtest.'
                : 'Pengiriman otomatis gagal. Jawaban lokal tetap tersimpan; coba kirim ulang.');
        }
        isSubmitting = false;
    }
    
    function getUnansweredQuestions() {
        const unanswered = [];
        const allQuestionNumbers = Array.from({length: totalQuestions}, (_, i) => i + 1);
        allQuestionNumbers.forEach(questionNumber => {
            if (!examState.answeredQuestions.has(questionNumber)) {
                unanswered.push(questionNumber);
            }
        });
        return unanswered;
    }
    function showUnansweredQuestionsInModal() {
        const unansweredQuestions = getUnansweredQuestions();
        const unansweredList = document.getElementById('unansweredList');
        const unansweredNumbers = document.getElementById('unansweredNumbers');
        if (unansweredQuestions.length > 0) {
            unansweredList.classList.remove('hidden');
            let numbersHtml = '';
            const chunkSize = 10;
            const chunks = [];
            for (let i = 0; i < unansweredQuestions.length; i += chunkSize) {
                chunks.push(unansweredQuestions.slice(i, i + chunkSize));
            }
            chunks.forEach((chunk, index) => {
                numbersHtml += `<div class="mb-1">${chunk.join(', ')}</div>`;
            });
            const summary = `<div class="mt-2 pt-2 border-t border-yellow-200 text-xs font-medium">
                                Total: ${unansweredQuestions.length} pertanyaan belum dijawab
                            </div>`;
            unansweredNumbers.innerHTML = numbersHtml + summary;
            const modalText = document.getElementById('submitModalText');
            if (modalText && unansweredQuestions.length > 0) {
                modalText.innerHTML = `
                    Ada <span class="font-semibold text-red-600">${unansweredQuestions.length} pertanyaan</span> yang belum dijawab. 
                    Setelah dikirim, kamu tidak dapat mengubah jawaban. 
                    Apakah kamu yakin ingin melanjutkan?
                `;
            }
        } else {
            unansweredList.classList.add('hidden');
            const modalText = document.getElementById('submitModalText');
            if (modalText) {
                modalText.innerHTML = `
                    Semua pertanyaan telah dijawab. 
                    Setelah dikirim, kamu tidak dapat mengubah jawaban. 
                    Apakah kamu yakin ingin melanjutkan?
                `;
            }
        }
    }
    async function submitAllAnswers() {
        if (isSubmitting) return;

        isSubmitting = true;

        const confirmSubmitBtn = document.getElementById('confirmSubmitBtn');
        const originalButtonText = confirmSubmitBtn.innerHTML;
        confirmSubmitBtn.innerHTML = `
            <svg class="w-5 h-5 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Memproses...
        `;
        confirmSubmitBtn.disabled = true;

        const pendingAnswers = {};
        Object.values(examState.answers).forEach(answerData => {
            if (answerData?.question_id && answerData.answer !== null && answerData.answer !== '') {
                pendingAnswers[answerData.question_id] = answerData.answer;
            }
        });
        const currentAnswer = document.querySelector('input[name="answer"]:checked');
        if (currentAnswer && !pendingAnswers[currentQuestionId]) {
            pendingAnswers[currentQuestionId] = currentAnswer.value;
        }

        try {
            const formData = new FormData();
            const csrfToken =
                document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

            formData.append('_token', csrfToken);

            Object.entries(pendingAnswers).forEach(([questionId, answer]) => {
                formData.append(`answers[${questionId}]`, answer);
            });

            const unansweredQuestions = getUnansweredQuestions();
            formData.append('unanswered_count', unansweredQuestions.length);
            formData.append('unanswered_list', JSON.stringify(unansweredQuestions));

            const submitUrl = "{{ route('user.questions.submit-all', [$test->slug, $typeTest->slug, $subTest->slug]) }}";

            const response = await fetch(submitUrl, {
                method: 'POST',
                body: formData,
            });

            if (!response.ok) {
                const error = new Error(`HTTP error! status: ${response.status}`);
                error.status = response.status;
                throw error;
            }

            clearExamAnswerStorage();

            window.location.href =
                "{{ route('user.sub-tests', ['test' => $test->slug, 'typeTest' => $typeTest->slug]) }}?submitted=true";

        } catch (error) {
            console.error('Submit all error:', error);
            confirmSubmitBtn.innerHTML = originalButtonText;
            confirmSubmitBtn.disabled = false;
            isSubmitting = false;
            if (error.status === 409) {
                if (isTimeExpired) submitAllAnswersAuto();
                else handleTimeUp();
                return;
            }
            showErrorModal('Error mengirim jawaban: ' + error.message);
        }
    }

    function updateProgressBar() {
        const answeredCount = examState.answeredQuestions.size;
        const progressPercentage = totalQuestions > 0 ? (answeredCount / totalQuestions) * 100 : 0;
        const progressBar = document.getElementById('progressBar');
        if (progressBar) {
            progressBar.style.width = `${progressPercentage}%`;
        }
        const progressPercentageText = document.getElementById('progressPercentage');
        if (progressPercentageText) {
            progressPercentageText.textContent = `${Math.round(progressPercentage)}% Selesai`;
        }
        const answeredCountElement = document.getElementById('answeredCount');
        const unansweredCountElement = document.getElementById('unansweredCount');
        
        if (answeredCountElement) {
            answeredCountElement.textContent = answeredCount;
        }
        if (unansweredCountElement) {
            const unansweredCount = totalQuestions - answeredCount;
            unansweredCountElement.textContent = unansweredCount;
        }
    }
    
    function updateQuestionDots() {
        document.querySelectorAll('.question-dot').forEach(dot => {
            const questionNumber = parseInt(dot.getAttribute('data-question-number'));
            dot.classList.remove(
                'bg-gradient-to-r', 'from-red-500', 'to-red-600', 'text-white', 'border-red-500', 'shadow-md',
                'bg-green-100', 'text-green-700', 'border-green-200',
                'bg-white', 'text-gray-700', 'border-gray-300'
            );
            if (questionNumber === currentQuestionNumber) {
                dot.classList.add('bg-gradient-to-r', 'from-red-500', 'to-red-600', 'text-white', 'border-red-500', 'shadow-md');
            }
            else if (examState.answeredQuestions.has(questionNumber)) {
                dot.classList.add('bg-green-100', 'text-green-700', 'border-green-200');
            }
            else {
                dot.classList.add('bg-white', 'text-gray-700', 'border-gray-300');
            }
        });
        updateProgressBar();
    }

    function saveAnswerToLocalStorage(skipped = false) {
        if (isTimeExpired) return '';
        const selectedAnswer = document.querySelector('input[name="answer"]:checked');
        const answerData = {
            question_id: currentQuestionId,
            answer: selectedAnswer ? selectedAnswer.value : '',
            skipped: skipped,
            timestamp: new Date().toISOString()
        };
        
        examState.answers[currentQuestionId] = answerData;
        localStorage.setItem(getAnswerStorageKey(currentQuestionId), JSON.stringify(answerData));
        if (selectedAnswer && selectedAnswer.value !== '') {
            examState.answeredQuestions.add(currentQuestionNumber);
            console.log('Added to answeredQuestions:', currentQuestionNumber);
        } else {
            examState.answeredQuestions.delete(currentQuestionNumber);
            console.log('Removed from answeredQuestions:', currentQuestionNumber);
        }
        
        updateQuestionDots();
        updateSaveStatus('saved');
        
        return selectedAnswer ? selectedAnswer.value : '';
    }
    
    function navigateToNextQuestion() {
        if (isTimeExpired || getRemainingSeconds() <= 0) {
            handleTimeUp();
            return;
        }
        if (currentQuestionNumber >= totalQuestions) {
            const allAnsweredInRange = examState.answeredQuestions.size >= totalQuestions;

            if (allAnsweredInRange && !isLastRange) {
                showRangeCompleteModal();
                return;
            } else {
                showModalWithUnanswered();
                return;
            }
        }
        const currentGlobalNumber = QUESTION_NUMBER_MAP[currentQuestionId];
        if (!currentGlobalNumber) {
            console.error('Global question number not found');
            return;
        }

        const nextGlobalNumber = currentGlobalNumber + 1;

        const nextUrl =
            "{{ route('user.questions', [
                'test' => $test->slug,
                'typeTest' => $typeTest->slug,
                'subTest' => $subTest->slug
            ]) }}" +
            "?question=" + nextGlobalNumber +
            "&range_id=" + currentRangeId;

        window.location.href = nextUrl;
    }

    function showRangeCompleteModal() {
        const modal = document.getElementById('rangeCompleteModal');
        modal.classList.remove('hidden');
        setTimeout(() => {
            if (nextRangeId) {
                window.location.href = "{{ route('user.questions', ['test' => $test->slug, 'typeTest' => $typeTest->slug, 'subTest' => $subTest->slug]) }}?question=1&range_id=" + nextRangeId;
            } else {
                window.location.href = "{{ route('user.sub-tests', ['test' => $test->slug, 'typeTest' => $typeTest->slug]) }}";
            }
        }, 2000);
    }
    
    function updateSaveStatus(status) {
        const saveStatus = document.getElementById('saveStatus');
        if (!saveStatus) return;
        if (status === 'saving') {
            saveStatus.innerHTML = `
                <svg class="w-4 h-4 mr-2 text-yellow-500 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Menyimpan...
            `;
        } else if (status === 'saved') {
            saveStatus.innerHTML = `
                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Jawaban Tersimpan
            `;
        } else {
            saveStatus.textContent = 'Pilih jawaban untuk menyimpan';
        }
    }

    function bindQuestionAnswerEvents() {
        document.querySelectorAll('#questionContent input[name="answer"]').forEach(radio => {
            radio.addEventListener('change', function() {
                if (isTimeExpired || getRemainingSeconds() <= 0) {
                    handleTimeUp();
                    return;
                }
                hasUnsavedChanges = true;
                updateSaveStatus('saving');
                saveAnswerToLocalStorage(false);
                clearTimeout(window.autoSaveTimeout);
                window.autoSaveTimeout = setTimeout(() => updateSaveStatus('saved'), 500);
            });
        });
    }
    
    function showErrorModal(errorMessage = '') {
        const errorModal = document.getElementById('errorModal');
        const errorText = errorModal.querySelector('.text-gray-500');
        
        if (errorMessage) {
            errorText.innerHTML = `
                <p class="text-sm text-gray-500 mb-2 text-center">
                    ${errorMessage}
                </p>
                <p class="text-sm text-gray-500 mb-4 text-center">
                    Silakan coba lagi.
                </p>
            `;
        }
        errorModal.classList.remove('hidden');
    }

    function showTimeWarning(seconds) {
        let message = '';
        
        if (seconds === 300) {
            message = 'Waktu tersisa 5 menit!';
        } else if (seconds === 60) {
            message = 'Waktu tersisa 1 menit!';
        } else if (seconds === 30) {
            message = 'Waktu tersisa 30 detik!';
        } else if (seconds === 10) {
            message = 'Waktu tersisa 10 detik!';
        }

        let warningElement = document.getElementById('timeWarning');
        
        if (!warningElement) {
            warningElement = document.createElement('div');
            warningElement.id = 'timeWarning';
            warningElement.className = 'fixed top-4 right-4 z-50 px-4 py-2 rounded-md shadow-lg transition-all duration-300';
            warningElement.style.backgroundColor = seconds <= 30 ? '#ef4444' : '#f59e0b';
            warningElement.style.color = 'white';
            warningElement.style.fontWeight = '600';
            warningElement.style.fontSize = '14px';
            document.body.appendChild(warningElement);
        }
        
        warningElement.textContent = message;
        warningElement.classList.remove('hidden');
        
        if (seconds > 10) {
            setTimeout(() => {
                warningElement.classList.add('hidden');
            }, 3000);
        }
    }
    
    function showModalWithUnanswered() {
        showUnansweredQuestionsInModal();
        document.getElementById('confirmSubmitModal').classList.remove('hidden');
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        loadExamStateFromStorage();
        updateQuestionDots();
        updateProgressBar();
        updateQuestionNavigationControls();
        bindQuestionAnswerEvents();
        history.replaceState({
            question: QUESTION_NUMBER_MAP[currentQuestionId],
            rangeId: currentRangeId,
        }, '', window.location.href);
        (function showSelectedRangeDurationUI(){
            const selId = currentRangeId;
            const selSeconds = getRangeDurationSeconds(selId);
            const selMinutes = Math.ceil(selSeconds / 60);
            const selEl = document.getElementById('selectedRangeDuration');
            if (selEl) {
                let cnt = 0;
                if (selId) {
                    const r = ALL_RANGES.find(x => x.id == selId);
                    if (r) cnt = countQuestionsInRange(r.start, r.end);
                } else {
                    cnt = TOTAL_ALL_QUESTIONS;
                }
                selEl.textContent = cnt > 0 ? `Durasi range: ${selMinutes} menit (${cnt} soal, ≈ ${Math.round(PER_QUESTION_SECONDS)} detik/soal)` : '';
            }
        })();
        const savedAnswer = examState.answers[currentQuestionId];
        const initialAnswerInputs = Array.from(document.querySelectorAll('#questionContent input[name="answer"]'));
        if (savedAnswer) {
            initialAnswerInputs.forEach(input => {
                input.checked = String(input.value) === String(savedAnswer.answer);
            });
        }
        const initialSelectedAnswer = initialAnswerInputs.find(input => input.checked);
        if (initialSelectedAnswer) {
            if (!savedAnswer) {
                const restoredAnswer = {
                    question_id: currentQuestionId,
                    answer: initialSelectedAnswer.value,
                    skipped: false,
                    timestamp: new Date().toISOString(),
                };
                examState.answers[currentQuestionId] = restoredAnswer;
                localStorage.setItem(getAnswerStorageKey(currentQuestionId), JSON.stringify(restoredAnswer));
            }
            examState.answeredQuestions.add(currentQuestionNumber);
            updateSaveStatus('saved');
            updateQuestionDots();
        } else {
            examState.answeredQuestions.delete(currentQuestionNumber);
            if (savedAnswer) updateSaveStatus('');
        }
        const nextQuestionBtn = document.getElementById('nextQuestionBtn');
        if (nextQuestionBtn) {
            nextQuestionBtn.addEventListener('click', function() {
                if (isQuestionNavigating || isTimeExpired) return;
                if (!document.querySelector('#questionContent input[name="answer"]:checked')) {
                    document.getElementById('noAnswerModal').classList.remove('hidden');
                    return;
                }
                navigateToNextQuestion();
            });
        }
        const submitAllBtn = document.getElementById('submitAllBtn');
        if (submitAllBtn) {
            submitAllBtn.addEventListener('click', function() {
                if (isTimeExpired) return;
                saveAnswerToLocalStorage(false);
                showModalWithUnanswered();
            });
        }
        const skipQuestionBtn = document.getElementById('skipQuestionBtn');
        if (skipQuestionBtn) {
            skipQuestionBtn.addEventListener('click', function() {
                if (isTimeExpired) return;
                document.getElementById('noAnswerModal').classList.add('hidden');
                saveAnswerToLocalStorage(true);
                navigateToNextQuestion();
            });
        }
        const stayOnQuestionBtn = document.getElementById('stayOnQuestionBtn');
        if (stayOnQuestionBtn) {
            stayOnQuestionBtn.addEventListener('click', function() {
                document.getElementById('noAnswerModal').classList.add('hidden');
            });
        }
        const confirmSubmitBtn = document.getElementById('confirmSubmitBtn');
        if (confirmSubmitBtn) {
            confirmSubmitBtn.addEventListener('click', function() {
                if (isTimeExpired) return;
                if (isLastRange || !currentRangeId) {
                    submitAllAnswers();
                } else {
                    if (nextRangeId) {
                        window.location.href = "{{ route('user.questions', ['test' => $test->slug, 'typeTest' => $typeTest->slug, 'subTest' => $subTest->slug]) }}?question=1&range_id=" + nextRangeId;
                    } else {
                        submitAllAnswers();
                    }
                }
            });
        }
        const cancelSubmitBtn = document.getElementById('cancelSubmitBtn');
        if (cancelSubmitBtn) {
            cancelSubmitBtn.addEventListener('click', function() {
                document.getElementById('confirmSubmitModal').classList.add('hidden');
            });
        }
        const retryButton = document.getElementById('retryButton');
        if (retryButton) {
            retryButton.addEventListener('click', function() {
                document.getElementById('errorModal').classList.add('hidden');
                submitAllAnswers();
            });
        }
        
        const continueButton = document.getElementById('continueButton');
        if (continueButton) {
            continueButton.addEventListener('click', function() {
                document.getElementById('errorModal').classList.add('hidden');
                window.location.href = "{{ route('user.sub-tests', ['test' => $test->slug, 'typeTest' => $typeTest->slug]) }}";
            });
        }
        document.querySelectorAll('.question-dot').forEach(dot => {
            dot.addEventListener('click', function(e) {
                e.preventDefault();
                if (isQuestionNavigating || isTimeExpired) return;
                const targetUrl = new URL(this.href, window.location.href);
                navigateToQuestion(
                    targetUrl.searchParams.get('question'),
                    targetUrl.searchParams.get('range_id')
                );
            });
        });
        const previousQuestionBtn = document.getElementById('previousQuestionBtn');
        if (previousQuestionBtn) {
            previousQuestionBtn.addEventListener('click', function(event) {
                event.preventDefault();
                if (isQuestionNavigating || isTimeExpired) return;
                const currentGlobalQuestion = QUESTION_NUMBER_MAP[currentQuestionId];
                navigateToQuestion(currentGlobalQuestion - 1, currentRangeId);
            });
        }
        window.addEventListener('popstate', function(event) {
            const currentUrl = new URL(window.location.href);
            const targetRangeId = currentUrl.searchParams.get('range_id') ?? event.state?.rangeId ?? null;
            const range = ALL_RANGES.find(item => item.id == targetRangeId);
            const defaultQuestion = range ? QUESTION_NUMBER_MAP[range.start] : 1;
            const targetQuestion = currentUrl.searchParams.get('question') ?? event.state?.question ?? defaultQuestion;
            navigateToQuestion(targetQuestion, targetRangeId, { force: true, history: 'none' });
        });
        document.addEventListener('visibilitychange', function() {
            if (!document.hidden) {
                updateTimerDisplay();
                if (getRemainingSeconds() <= 0) handleTimeUp();
            }
        });
        updateTimerDisplay();
        if (getRemainingSeconds() > 0) startTimer();
        else handleTimeUp();
    });

    document.addEventListener('DOMContentLoaded', function() {
        ALL_RANGES.forEach(range => {
            const count = countQuestionsInRange(range.start, range.end);
            const durationMinutes = Math.ceil(getRangeDurationSeconds(range.id) / 60);
            const durationElement = document.querySelector(`.range-duration[data-range-id="${range.id}"]`);
            if (durationElement) {
                durationElement.textContent = count > 0 ? `• ${count} soal • ${durationMinutes} mnt` : '';
            }
        });
    });
</script>
@endsection
