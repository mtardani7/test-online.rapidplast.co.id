@extends('layouts.sidebar')

@section('page-title', 'Kelola Soal')
@section('current-page', 'Kelola Soal')
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
                <nav aria-label="Breadcrumb" class="mb-3 flex flex-wrap items-center gap-x-2 gap-y-1 text-xs font-medium text-rose-100 sm:text-sm">
                    <a href="{{ route('test.index') }}" class="hover:text-white">Tes</a>
                    <i class="fas fa-chevron-right text-[9px] opacity-70"></i>
                    <a href="{{ route('type-test.index', $test->slug) }}" class="max-w-[10rem] truncate hover:text-white">{{ $test->name }}</a>
                    <i class="fas fa-chevron-right text-[9px] opacity-70"></i>
                    <a href="{{ route('sub-test.index', [$test->slug, $typeTest->slug]) }}" class="max-w-[10rem] truncate hover:text-white">{{ $typeTest->name }}</a>
                    <i class="fas fa-chevron-right text-[9px] opacity-70"></i>
                    <span class="max-w-[12rem] truncate text-white">{{ $subTest->name }}</span>
                </nav>
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-rose-100">Bank soal</p>
                <h1 class="mt-2 text-2xl font-black tracking-tight sm:text-3xl lg:text-4xl">Kelola Soal</h1>
                <p class="mt-2 text-sm leading-6 text-rose-50 sm:text-base">{{ $subTest->name }} <span class="px-1.5 opacity-70">·</span> Kelola pertanyaan dan pilihan jawaban.</p>
            </div>
            <div class="flex w-full flex-col gap-2 sm:w-auto sm:flex-row">
            <a href="{{ route('sub-test.index', [$test->slug, $typeTest->slug]) }}"
               class="inline-flex min-h-11 items-center justify-center rounded-xl border border-white/30 bg-white/10 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-white/70">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
            <button type="button"
                class="inline-flex min-h-11 items-center justify-center rounded-xl bg-white px-5 py-2.5 text-sm font-bold text-rose-700 shadow-md transition hover:-translate-y-0.5 hover:bg-rose-50 focus:outline-none focus:ring-2 focus:ring-white/80"
                data-modal-toggle="addModal">
                <i class="fas fa-plus mr-2"></i> Tambah Soal
            </button>
            </div>
        </div>
    </div>
    <div class="grid grid-cols-1 gap-3 sm:grid-cols-3 sm:gap-4">
        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition hover:shadow-md sm:p-5">
            <div class="flex items-center gap-4">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600 ring-1 ring-blue-100 sm:h-12 sm:w-12">
                    <i class="fas fa-question-circle text-blue-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Total Pertanyaan</p>
                    <p class="mt-0.5 text-2xl font-black tracking-tight text-slate-900">{{ $totalQuestions ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition hover:shadow-md sm:p-5">
            <div class="flex items-center gap-4">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 ring-1 ring-emerald-100 sm:h-12 sm:w-12">
                    <i class="fas fa-image text-green-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Dengan Gambar</p>
                    <p class="mt-0.5 text-2xl font-black tracking-tight text-slate-900">{{ $questionsWithImage ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition hover:shadow-md sm:p-5">
            <div class="flex items-center gap-4">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-violet-50 text-violet-600 ring-1 ring-violet-100 sm:h-12 sm:w-12">
                    <i class="fas fa-list-alt text-purple-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Rata-rata Opsi</p>
                    <p class="mt-0.5 text-2xl font-black tracking-tight text-slate-900">{{ $avgChoices ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 px-4 py-4 sm:px-6 sm:py-5">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-lg font-extrabold text-slate-900 sm:text-xl">Daftar Soal</h2>
                    <p class="mt-1 text-sm text-slate-500">Cari soal, periksa jawaban, atau ubah detailnya.</p>
                </div>
                <div class="flex w-full items-center justify-between gap-3 sm:w-auto sm:justify-end">
                    <span class="rounded-full bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-600 sm:text-sm">
                        {{ $questions->total() }} soal
                    </span>
                    <button type="button"
                        id="filterToggle" 
                        aria-controls="filterForm"
                        aria-expanded="{{ request()->filled('search') || request()->filled('has_image') || request()->filled('has_correct') ? 'true' : 'false' }}"
                        class="inline-flex min-h-10 items-center rounded-xl border border-slate-200 px-3.5 py-2 text-sm font-semibold text-slate-700 transition hover:border-rose-200 hover:bg-rose-50 hover:text-rose-700 focus:outline-none focus:ring-2 focus:ring-rose-200 sm:px-4"
                    >
                        <i class="fas fa-filter mr-2"></i> Cari & Filter
                    </button>
                </div>
            </div>
            <div id="filterForm" class="{{ request()->filled('search') || request()->filled('has_image') || request()->filled('has_correct') ? '' : 'hidden' }} mt-4 border-t border-slate-100 pt-4 sm:mt-5 sm:pt-5">
                <form method="GET" action="{{ route('question.index', [$test->slug, $typeTest->slug, $subTest->slug]) }}" class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
                    <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">Cari soal</label>
                        <div class="relative">
                            <input 
                                type="text" 
                                name="search" 
                                value="{{ request('search') }}"
                                class="w-full rounded-xl border border-slate-200 py-2.5 pl-10 pr-4 text-sm transition focus:border-rose-400 focus:outline-none focus:ring-2 focus:ring-rose-100"
                                placeholder="Ketik teks soal atau opsi..."
                            >
                            <i class="fas fa-search absolute left-3 top-3.5 text-gray-400"></i>
                        </div>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Gambar</label>
                        <select name="has_image" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm transition focus:border-rose-400 focus:outline-none focus:ring-2 focus:ring-rose-100">
                            <option value="">Semua</option>
                            <option value="yes" {{ request('has_image') == 'yes' ? 'selected' : '' }}>Dengan Gambar</option>
                            <option value="no" {{ request('has_image') == 'no' ? 'selected' : '' }}>Tanpa Gambar</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Status jawaban benar</label>
                        <select name="has_correct" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm transition focus:border-rose-400 focus:outline-none focus:ring-2 focus:ring-rose-100">
                            <option value="">Semua</option>
                            <option value="yes" {{ request('has_correct') == 'yes' ? 'selected' : '' }}>Sudah ada jawaban</option>
                            <option value="no" {{ request('has_correct') == 'no' ? 'selected' : '' }}>Belum ada jawaban</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Urutkan</label>
                        <select name="sort_by" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm transition focus:border-rose-400 focus:outline-none focus:ring-2 focus:ring-rose-100">
                            <option value="created_at" {{ request('sort_by', 'created_at') == 'created_at' ? 'selected' : '' }}>Tanggal Dibuat</option>
                            <option value="question_text" {{ request('sort_by') == 'question_text' ? 'selected' : '' }}>Pertanyaan A-Z</option>
                        </select>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex flex-col-reverse gap-2 pt-1 sm:col-span-2 sm:flex-row sm:justify-end xl:col-span-4">
                        <a href="{{ route('question.index', [$test->slug, $typeTest->slug, $subTest->slug]) }}" 
                           class="inline-flex min-h-10 items-center justify-center rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                            Reset Filter
                        </a>
                        <button type="submit" class="inline-flex min-h-10 items-center justify-center rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-rose-700">
                            Terapkan Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @if($questions->isEmpty())
        <div class="px-5 py-10 text-center sm:py-14">
            <div class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-2xl bg-rose-50 text-rose-600 ring-1 ring-rose-100 sm:h-20 sm:w-20">
                <i class="fas fa-question-circle text-2xl sm:text-3xl"></i>
            </div>
            <h4 class="text-xl font-extrabold text-slate-900">Belum ada soal</h4>
            <p class="mb-6 mt-2 text-sm text-slate-500">Tambahkan soal pertama untuk subtest ini.</p>
            <button type="button"
                class="mx-auto inline-flex min-h-11 items-center justify-center rounded-xl bg-gradient-to-r from-rose-600 to-rose-700 px-6 py-3 font-bold text-white shadow-md transition hover:-translate-y-0.5 hover:from-rose-700 hover:to-rose-800"
                data-modal-toggle="addModal">
                <i class="fas fa-plus mr-2"></i> Tambah Soal
            </button>
        </div>
        @else
        <div class="space-y-3 p-3 md:hidden">
            @foreach ($questions as $q)
            @php
                $hasImage = !empty($q->image);
                $optionsCount = $q->choices->count();
                $questionNumber = ($questions->currentPage() - 1) * $questions->perPage() + $loop->iteration;
            @endphp
            <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <div class="flex items-start gap-3">
                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-xs font-bold text-slate-600">{{ $questionNumber }}</span>
                    <div class="min-w-0 flex-1">
                        <p class="whitespace-pre-line break-words text-sm font-semibold leading-6 text-slate-900">{{ $q->question_text ?? '-' }}</p>
                        @if($q->correct_option_id)
                            <span class="mt-2 inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                                <i class="fas fa-check-circle"></i> Jawaban benar tersedia
                            </span>
                        @else
                            <span class="mt-2 inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700">
                                <i class="fas fa-exclamation-circle"></i> Jawaban belum ditandai
                            </span>
                        @endif
                    </div>
                </div>
                @if($q->question_optional)
                    <p class="mt-3 whitespace-pre-line break-words border-l-2 border-slate-200 pl-3 text-xs leading-5 text-slate-500">{{ $q->question_optional }}</p>
                @endif
                <div class="mt-4 flex flex-wrap items-center gap-2 border-t border-slate-100 pt-3">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-violet-50 px-2.5 py-1 text-xs font-semibold text-violet-700">
                        <i class="fas fa-list-ol"></i> {{ $optionsCount }} opsi
                    </span>
                    @if($hasImage)
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700">
                            <i class="fas fa-image"></i> Gambar
                        </span>
                    @endif
                    <div class="ml-auto flex items-center gap-1">
                        <button type="button" class="flex h-10 w-10 items-center justify-center rounded-xl text-slate-600 transition hover:bg-violet-50 hover:text-violet-700" data-modal-toggle="detailModal{{ $q->id }}" aria-label="Lihat opsi soal {{ $questionNumber }}" title="Lihat detail soal">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.5-7 9.75-7 9.75 7 9.75 7-3.5 7-9.75 7-9.75-7-9.75-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                        <button type="button" class="flex h-10 w-10 items-center justify-center rounded-xl text-slate-600 transition hover:bg-rose-50 hover:text-rose-700" data-modal-toggle="editModal{{ $q->id }}" aria-label="Edit soal {{ $questionNumber }}" title="Edit soal">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 2.651 2.651M4.5 19.5l4.125-.825L19.02 8.28a1.875 1.875 0 0 0-2.652-2.652L5.973 16.02 5.25 19.5Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6.75 17.25 10.5"/></svg>
                        </button>
                        <form action="{{ route('question.destroy', [$test->slug, $typeTest->slug, $subTest->slug, $q->id]) }}" method="POST" class="delete-form" data-confirm-delete="true" data-item-name="{{ Str::limit($q->question_text, 50) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="flex h-10 w-10 items-center justify-center rounded-xl text-slate-500 transition hover:bg-red-50 hover:text-red-600" title="Hapus soal" aria-label="Hapus soal {{ $questionNumber }}">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M9.75 6.75V4.5h4.5v2.25m-8.25 0 .75 13.5h10.5l.75-13.5M10.125 10.5v6m3.75-6v6"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </article>
            @endforeach
        </div>
        <div class="hidden overflow-x-auto md:block">
            <table class="w-full min-w-[980px]">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/80">
                        <th class="w-16 px-5 py-4 text-left text-xs font-bold uppercase tracking-wide text-slate-500">#</th>
                        <th class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wide text-slate-500">Pertanyaan</th>
                        <th class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wide text-slate-500">Keterangan</th>
                        <th class="w-32 px-5 py-4 text-left text-xs font-bold uppercase tracking-wide text-slate-500">Gambar</th>
                        <th class="w-40 px-5 py-4 text-left text-xs font-bold uppercase tracking-wide text-slate-500">Opsi Jawaban</th>
                        <th class="w-40 px-5 py-4 text-left text-xs font-bold uppercase tracking-wide text-slate-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($questions as $q)
                    @php
                        $hasImage = !empty($q->image);
                        $optionsCount = $q->choices->count();
                    @endphp
                    <tr class="align-top transition-colors hover:bg-rose-50/30">
                        <td class="px-4 py-4 sm:px-5">
                            <div class="flex items-center justify-center w-8 h-8 bg-gray-100 rounded-lg font-semibold text-gray-700">
                                {{ ($questions->currentPage() - 1) * $questions->perPage() + $loop->iteration }}
                            </div>
                        </td>
                        <td class="px-4 py-4 sm:px-5">
                            <div class="max-w-lg">
                                <p class="whitespace-pre-line break-words text-sm font-semibold leading-6 text-slate-900">{{ $q->question_text ?? '-' }}</p>
                                @if($q->correct_option_id)
                                <div class="mt-2 inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Kunci jawaban sudah dipilih</span>
                                </div>
                                @else
                                <div class="mt-2 inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700">
                                    <i class="fas fa-exclamation-circle"></i>
                                    <span>Kunci jawaban belum dipilih</span>
                                </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-4 sm:px-5">
                            <p class="max-w-56 whitespace-pre-line break-words text-sm leading-5 text-slate-600">{{ $q->question_optional ?: 'Tidak ada keterangan' }}</p>
                        </td>
                        <td class="px-4 py-4 sm:px-5">
                            @if($hasImage)
                            <span role="img" aria-label="Soal memiliki gambar" title="Soal memiliki gambar" class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-sky-50 text-sky-600 ring-1 ring-sky-100 transition hover:bg-sky-100">
                                <i class="far fa-image text-lg" aria-hidden="true"></i>
                            </span>
                            @else
                            <span class="text-xs text-slate-400" title="Soal tanpa gambar">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-4 sm:px-5">
                            <button type="button"
                                    class="inline-flex min-h-9 items-center gap-2 rounded-xl border border-violet-100 bg-violet-50 px-3 py-2 text-xs font-bold text-violet-700 transition hover:border-violet-200 hover:bg-violet-100"
                                    data-modal-toggle="detailModal{{ $q->id }}"
                                    aria-label="Lihat detail soal {{ ($questions->currentPage() - 1) * $questions->perPage() + $loop->iteration }}"
                                    title="Lihat detail soal dan opsi jawaban">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 6h11M9 12h11M9 18h11M4.5 6h.01M4.5 12h.01M4.5 18h.01"/></svg>
                                    <span>{{ $optionsCount }} opsi</span>
                                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12h15m-6-6 6 6-6 6"/></svg>
                                </button>
                        </td>
                        <td class="px-4 py-4 sm:px-5">
                            <div class="flex flex-wrap items-center gap-1.5">
                                <button type="button"
                                    class="inline-flex min-h-9 items-center gap-2 rounded-xl px-3 py-2 text-xs font-bold text-rose-700 transition hover:bg-rose-50"
                                    data-modal-toggle="editModal{{ $q->id }}"
                                    aria-label="Edit soal {{ ($questions->currentPage() - 1) * $questions->perPage() + $loop->iteration }}"
                                    title="Edit soal">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 2.651 2.651M4.5 19.5l4.125-.825L19.02 8.28a1.875 1.875 0 0 0-2.652-2.652L5.973 16.02 5.25 19.5Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6.75 17.25 10.5"/></svg><span>Edit</span>
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
                                        class="inline-flex min-h-9 items-center gap-2 rounded-xl px-3 py-2 text-xs font-bold text-red-600 transition hover:bg-red-50 delete-btn"
                                        title="Hapus soal">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M9.75 6.75V4.5h4.5v2.25m-8.25 0 .75 13.5h10.5l.75-13.5M10.125 10.5v6m3.75-6v6"/></svg><span>Hapus</span>
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
        <div class="border-t border-slate-100 px-4 py-4 sm:px-6">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div class="text-center text-xs text-slate-600 sm:text-left sm:text-sm">
                    Menampilkan <span class="font-medium">{{ $questions->firstItem() }}</span> - 
                    <span class="font-medium">{{ $questions->lastItem() }}</span> dari 
                    <span class="font-medium">{{ $questions->total() }}</span> pertanyaan
                </div>
                
                <nav aria-label="Navigasi halaman soal" class="flex max-w-full items-center justify-center overflow-x-auto sm:justify-end">
                    {{ $questions->onEachSide(1)->links('vendor.pagination.custom') }}
                </nav>
            </div>
        </div>
        @endif
        @endif
    </div>
</div>

<div id="addModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60 p-2 backdrop-blur-sm sm:p-4">
    <div class="modal-animation flex max-h-[94vh] w-full max-w-3xl flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl sm:rounded-3xl">
        <form action="{{ route('question.store', [$test->slug, $typeTest->slug, $subTest->slug]) }}" 
              method="POST" enctype="multipart/form-data" id="createQuestionForm" class="flex min-h-0 flex-1 flex-col" novalidate>
            @csrf
            <div class="flex shrink-0 items-center justify-between border-b border-slate-100 bg-white px-4 py-4 sm:px-6">
                <div class="min-w-0">
                    <p class="text-xs font-bold uppercase tracking-wider text-rose-600">Subtest: {{ $subTest->name }}</p>
                    <h3 class="mt-1 truncate text-lg font-extrabold text-slate-900 sm:text-xl">Tambah Soal Baru</h3>
                </div>
                <button type="button" aria-label="Tutup form tambah soal" class="ml-3 flex h-10 w-10 shrink-0 items-center justify-center rounded-xl text-slate-500 transition hover:bg-slate-100 hover:text-slate-900" data-modal-close="addModal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="min-h-0 flex-1 space-y-4 overflow-y-auto overscroll-contain p-4 sm:space-y-5 sm:p-6" style="max-height: calc(94vh - 140px);">
                <div class="rounded-xl border border-blue-100 bg-blue-50/70 p-4">
                    <p class="text-sm font-bold text-slate-800">Cara mengisi</p>
                    <ol class="mt-2 grid gap-2 text-xs leading-5 text-slate-600 sm:grid-cols-3 sm:text-sm">
                        <li><span class="mr-1 font-bold text-blue-700">1.</span> Tulis pertanyaan.</li>
                        <li><span class="mr-1 font-bold text-blue-700">2.</span> Isi minimal dua pilihan.</li>
                        <li><span class="mr-1 font-bold text-blue-700">3.</span> Pilih satu jawaban benar.</li>
                    </ol>
                </div>
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
                <div class="rounded-2xl border border-slate-200 bg-white p-4 sm:p-5">
                    <div class="mb-4">
                        <h4 class="font-extrabold text-slate-900">1. Isi pertanyaan</h4>
                        <p class="mt-1 text-xs leading-5 text-slate-500 sm:text-sm">Tuliskan soal yang akan dibaca peserta. Keterangan dan gambar bersifat opsional.</p>
                    </div>
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
                <div class="rounded-2xl border border-slate-200 bg-white p-4 sm:p-5">
                    <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h4 class="font-extrabold text-slate-900">2. Buat pilihan jawaban</h4>
                            <p class="mt-1 text-xs leading-5 text-slate-500 sm:text-sm">Isi minimal 2 dan maksimal 6 pilihan, lalu tandai satu jawaban yang benar.</p>
                        </div>
                        <button 
                            type="button" 
                            id="add-option"
                            class="inline-flex min-h-10 shrink-0 items-center justify-center rounded-xl bg-rose-600 px-4 py-2 text-sm font-bold text-white transition hover:bg-rose-700"
                        >
                            <i class="fas fa-plus mr-2"></i> Tambah Opsi
                        </button>
                    </div>
                    
                    <div id="options-container" class="space-y-4">
                    </div>
                    
                    <div class="mt-4 rounded-xl border border-amber-100 bg-amber-50/70 p-4">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-yellow-500 mt-1 mr-3"></i>
                            <div>
                                <p class="text-sm font-bold text-amber-900">Sebelum disimpan</p>
                                <p class="mt-1 text-xs leading-5 text-amber-800 sm:text-sm">
                                    Pastikan teks setiap pilihan sudah terisi dan hanya satu pilihan yang ditandai sebagai jawaban benar.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex shrink-0 flex-col-reverse gap-2 border-t border-slate-100 bg-white px-4 py-3 sm:flex-row sm:justify-end sm:gap-3 sm:px-6 sm:py-4">
                <button 
                    type="button" 
                    class="inline-flex min-h-11 items-center justify-center rounded-xl border border-slate-200 px-5 py-2.5 font-semibold text-slate-600 transition hover:bg-slate-50"
                    data-modal-close="addModal"
                >
                    Batal
                </button>
                <button 
                    type="submit" 
                    class="inline-flex min-h-11 items-center justify-center rounded-xl bg-gradient-to-r from-rose-600 to-rose-700 px-5 py-2.5 font-bold text-white shadow-sm transition hover:from-rose-700 hover:to-rose-800 disabled:cursor-not-allowed disabled:opacity-50"
                    id="submitBtn"
                >
                    <span class="flex items-center">
                        <i class="fas fa-save mr-2"></i>
                        <span>Simpan Soal</span>
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
<div id="detailModal{{ $q->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60 p-2 backdrop-blur-sm sm:p-4">
    <div class="modal-animation flex max-h-[94vh] w-full max-w-2xl flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl sm:rounded-3xl">
        <div class="flex shrink-0 items-center justify-between border-b border-slate-100 px-4 py-4 sm:px-6">
            <div class="min-w-0">
                <p class="text-xs font-bold uppercase tracking-wider text-violet-600">Subtest: {{ $subTest->name }}</p>
                <h3 class="mt-1 text-lg font-extrabold text-slate-900 sm:text-xl">Detail Soal #{{ ($questions->currentPage() - 1) * $questions->perPage() + $loop->iteration }}</h3>
            </div>
            <button type="button" aria-label="Tutup detail soal" class="ml-3 flex h-10 w-10 shrink-0 items-center justify-center rounded-xl text-slate-500 transition hover:bg-slate-100 hover:text-slate-900" data-modal-close="detailModal{{ $q->id }}">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="min-h-0 flex-1 space-y-5 overflow-y-auto overscroll-contain p-4 sm:p-6" style="max-height: calc(94vh - 140px);">
            <section class="rounded-2xl border border-slate-200 bg-slate-50/70 p-4 sm:p-5">
                <div class="mb-3 flex items-center justify-between gap-3">
                    <h4 class="text-sm font-extrabold uppercase tracking-wide text-slate-500">Pertanyaan</h4>
                    @if($q->correct_option_id)
                        <span class="inline-flex shrink-0 items-center gap-1.5 rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-bold text-emerald-800"><i class="fas fa-check-circle"></i> Kunci tersedia</span>
                    @else
                        <span class="inline-flex shrink-0 items-center gap-1.5 rounded-full bg-amber-100 px-2.5 py-1 text-xs font-bold text-amber-800"><i class="fas fa-exclamation-circle"></i> Belum ada kunci</span>
                    @endif
                </div>
                <div class="rounded-xl border border-slate-200 bg-white p-4 sm:p-5">
                    <p class="whitespace-pre-line break-words text-base font-medium leading-7 text-slate-900 sm:text-lg">{{ $q->question_text ?? '-' }}</p>
                    @if($q->question_optional)
                    <p class="mt-4 whitespace-pre-line break-words border-t border-slate-100 pt-3 text-sm leading-6 text-slate-600">
                        <span class="mb-1 block text-xs font-bold uppercase tracking-wide text-slate-400">Keterangan</span>{{ $q->question_optional }}
                    </p>
                    @endif
                </div>
            </section>
            
            @if($q->image)
            <section class="rounded-2xl border border-slate-200 p-4 sm:p-5">
                <h4 class="mb-3 text-sm font-extrabold uppercase tracking-wide text-slate-500">Gambar soal</h4>
                <div class="flex max-h-80 items-center justify-center overflow-hidden rounded-xl bg-slate-50 p-2">
                    <img src="{{ $q->image }}" alt="Gambar pada soal {{ ($questions->currentPage() - 1) * $questions->perPage() + $loop->iteration }}" class="max-h-72 w-auto max-w-full object-contain">
                </div>
            </section>
            @endif
            
            <section class="rounded-2xl border border-slate-200 p-4 sm:p-5">
                <div class="mb-3 flex items-center justify-between gap-3">
                    <div>
                        <h4 class="text-sm font-extrabold uppercase tracking-wide text-slate-500">Pilihan jawaban</h4>
                        <p class="mt-1 text-xs text-slate-400">Jawaban yang benar ditandai warna hijau.</p>
                    </div>
                    <span class="rounded-full bg-violet-50 px-2.5 py-1 text-xs font-bold text-violet-700">{{ $q->choices->count() }} opsi</span>
                </div>
                <div class="space-y-3">
                    @foreach ($q->choices as $opt)
                    <div class="flex items-start gap-3 rounded-xl border p-3 sm:p-4 {{ $q->correct_option_id == $opt->id ? 'border-emerald-200 bg-emerald-50/70' : 'border-slate-200 bg-white' }}">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl {{ $q->correct_option_id == $opt->id ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-600' }}">
                            <span class="font-extrabold">{{ $opt->option_label }}</span>
                        </div>
                        <span class="min-w-0 flex-1 break-words pt-1 text-sm leading-6 text-slate-800 sm:text-base">{{ $opt->option_text }}</span>
                        @if($q->correct_option_id == $opt->id)
                            <span class="inline-flex shrink-0 items-center gap-1 rounded-full bg-emerald-100 px-2 py-1 text-[10px] font-extrabold text-emerald-800 sm:px-2.5 sm:text-xs">
                                <i class="fas fa-check"></i><span class="hidden sm:inline">Jawaban benar</span><span class="sm:hidden">Benar</span>
                            </span>
                        @endif
                    </div>
                    @endforeach
                </div>
            </section>
        </div>
        
        <div class="flex shrink-0 justify-end border-t border-slate-100 bg-white px-4 py-3 sm:px-6 sm:py-4">
            <button 
                type="button" 
                class="inline-flex min-h-11 w-full items-center justify-center rounded-xl bg-slate-100 px-5 py-2.5 font-bold text-slate-700 transition hover:bg-slate-200 sm:w-auto"
                data-modal-close="detailModal{{ $q->id }}"
            >
                Tutup
            </button>
        </div>
    </div>
</div>
<div id="editModal{{ $q->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60 p-2 backdrop-blur-sm sm:p-4">
    <div class="modal-animation flex max-h-[94vh] w-full max-w-3xl flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl sm:rounded-3xl">
        <form action="{{ route('question.update', [$test->slug, $typeTest->slug, $subTest->slug, $q->id]) }}" 
              method="POST" enctype="multipart/form-data" id="editQuestionForm{{ $q->id }}" class="flex min-h-0 flex-1 flex-col" novalidate>
            @csrf
            @method('PUT')
            
            <div class="flex shrink-0 items-center justify-between border-b border-slate-100 bg-white px-4 py-4 sm:px-6">
                <div class="min-w-0">
                    <p class="text-xs font-bold uppercase tracking-wider text-rose-600">Subtest: {{ $subTest->name }}</p>
                    <h3 class="mt-1 truncate text-lg font-extrabold text-slate-900 sm:text-xl">Edit Soal</h3>
                </div>
                <button type="button" aria-label="Tutup form edit soal" class="ml-3 flex h-10 w-10 shrink-0 items-center justify-center rounded-xl text-slate-500 transition hover:bg-slate-100 hover:text-slate-900" data-modal-close="editModal{{ $q->id }}">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="min-h-0 flex-1 space-y-4 overflow-y-auto overscroll-contain p-4 sm:space-y-5 sm:p-6" style="max-height: calc(94vh - 140px);">
                <div class="rounded-xl border border-blue-100 bg-blue-50/70 p-4">
                    <p class="text-sm font-bold text-slate-800">Periksa tiga bagian sebelum menyimpan</p>
                    <p class="mt-1 text-xs leading-5 text-slate-600 sm:text-sm">Ubah teks soal, perbarui gambar bila perlu, lalu pastikan pilihan jawaban yang benar sudah ditandai.</p>
                </div>
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
                <div class="rounded-2xl border border-slate-200 bg-white p-4 sm:p-5">
                    <div class="mb-4">
                        <h4 class="font-extrabold text-slate-900">1. Pertanyaan</h4>
                        <p class="mt-1 text-xs leading-5 text-slate-500 sm:text-sm">Perbarui teks soal dan keterangan tambahannya.</p>
                    </div>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 md:gap-6">
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
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-4 sm:p-5">
                    <div class="mb-4">
                        <h4 class="font-extrabold text-slate-900">2. Gambar <span class="font-medium text-slate-400">(opsional)</span></h4>
                        <p class="mt-1 text-xs leading-5 text-slate-500 sm:text-sm">Gambar lama tetap digunakan jika kamu tidak menggantinya.</p>
                    </div>
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
                <div class="rounded-2xl border border-slate-200 bg-white p-4 sm:p-5">
                    <div class="mb-4">
                        <h4 class="font-extrabold text-slate-900">3. Pilihan jawaban</h4>
                        <p class="mt-1 text-xs leading-5 text-slate-500 sm:text-sm">Pilih lingkaran di samping satu pilihan untuk menentukan kunci jawaban.</p>
                    </div>
                    <div class="space-y-4">
                        @foreach ($q->choices as $opt)
                        <div class="edit-option-item rounded-xl border {{ $q->correct_option_id == $opt->id ? 'border-emerald-300 bg-emerald-50/60' : 'border-slate-200 bg-slate-50/60' }} p-4 transition-colors">
                            <div class="mb-3 flex flex-wrap items-center justify-between gap-2">
                                <label class="font-bold text-slate-800">Pilihan {{ $opt->option_label }}</label>
                            </div>
                            <input 
                                type="text" 
                                class="mb-3 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm transition focus:border-rose-400 focus:outline-none focus:ring-2 focus:ring-rose-100"
                                name="options[{{ $opt->option_label }}]"
                                value="{{ old('options.'.$opt->option_label, session('error_type') == 'edit_' . $q->id ? (session('old_input.options.'.$opt->option_label) ?? $opt->option_text) : $opt->option_text) }}" 
                                required
                                maxlength="255"
                            >
                            <label class="flex min-h-11 cursor-pointer items-center rounded-xl border border-slate-200 bg-white px-3 py-2 transition hover:border-emerald-300 hover:bg-emerald-50/60">
                                <input 
                                    type="radio" 
                                    name="correct" 
                                    id="correct_{{ $q->id }}_{{ $opt->option_label }}"
                                    value="{{ $opt->option_label }}"
                                    class="edit-correct-choice h-4 w-4 border-slate-300 text-emerald-600 focus:ring-emerald-500"
                                    onchange="highlightCorrectChoice(this)"
                                    {{ $q->correct_option_id == $opt->id ? 'checked' : '' }}
                                    required
                                >
                                <span class="ml-2 text-sm font-semibold text-slate-700">Jadikan jawaban benar</span>
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="flex shrink-0 flex-col-reverse gap-2 border-t border-slate-100 bg-white px-4 py-3 sm:flex-row sm:justify-end sm:gap-3 sm:px-6 sm:py-4">
                <button 
                    type="button" 
                    class="inline-flex min-h-11 items-center justify-center rounded-xl border border-slate-200 px-5 py-2.5 font-semibold text-slate-600 transition hover:bg-slate-50"
                    data-modal-close="editModal{{ $q->id }}"
                >
                    Batal
                </button>
                <button 
                    type="submit" 
                    class="inline-flex min-h-11 items-center justify-center rounded-xl bg-gradient-to-r from-rose-600 to-rose-700 px-5 py-2.5 font-bold text-white shadow-sm transition hover:from-rose-700 hover:to-rose-800 disabled:cursor-not-allowed disabled:opacity-50"
                    id="editSubmitBtn{{ $q->id }}"
                >
                    <span class="flex items-center">
                        <i class="fas fa-save mr-2"></i>
                        <span>Simpan Perubahan</span>
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
            this.setAttribute('aria-expanded', String(!filterForm.classList.contains('hidden')));
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

        const updateOptionSelection = () => {
            optionsContainer.querySelectorAll('.option-item').forEach(item => {
                const selected = item.querySelector('input[type="radio"]')?.checked;
                item.classList.toggle('border-emerald-300', selected);
                item.classList.toggle('bg-emerald-50/60', selected);
                item.classList.toggle('border-slate-200', !selected);
                item.classList.toggle('bg-slate-50/60', !selected);
            });
        };
        updateOptionSelection();
        optionsContainer.addEventListener('change', event => {
            if (event.target.matches('input[type="radio"][name="correct"]')) updateOptionSelection();
        });
        
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
                updateOptionSelection();
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
    optionItem.className = 'option-item rounded-xl border border-slate-200 bg-slate-50/60 p-4 transition-colors';
    optionItem.dataset.label = label;
    optionItem.innerHTML = `
        <div class="mb-3 flex items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-white text-sm font-extrabold text-slate-700 shadow-sm ring-1 ring-slate-200">${label}</span>
                <label class="font-bold text-slate-800">Pilihan ${label}</label>
            </div>
            <button type="button" aria-label="Hapus pilihan ${label}" title="Hapus pilihan ${label}" class="remove-option flex h-9 w-9 items-center justify-center rounded-lg text-slate-400 transition hover:bg-red-50 hover:text-red-600">
                <i class="fas fa-trash-alt"></i>
            </button>
        </div>
        <input 
            type="text" 
            class="mb-3 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm transition focus:border-rose-400 focus:outline-none focus:ring-2 focus:ring-rose-100"
            name="options[${label}]"
            placeholder="Masukkan teks opsi ${label}"
            required
            maxlength="255"
        >
        <label class="flex min-h-11 cursor-pointer items-center rounded-xl border border-slate-200 bg-white px-3 py-2 transition hover:border-emerald-300 hover:bg-emerald-50/60">
            <input 
                type="radio" 
                name="correct" 
                value="${label}"
                class="h-4 w-4 border-slate-300 text-emerald-600 focus:ring-emerald-500"
                ${label === 'A' ? 'checked' : ''}
                required
            >
            <span class="ml-2 text-sm font-semibold text-slate-700">Jadikan jawaban benar</span>
        </label>
    `;
    
    optionsContainer.appendChild(optionItem);
}

function highlightCorrectChoice(radio) {
    const form = radio.closest('form');
    if (!form) return;

    form.querySelectorAll('.edit-option-item').forEach(item => {
        const selected = item.querySelector('input[type="radio"]')?.checked;
        item.classList.toggle('border-emerald-300', selected);
        item.classList.toggle('bg-emerald-50/60', selected);
        item.classList.toggle('border-slate-200', !selected);
        item.classList.toggle('bg-slate-50/60', !selected);
    });
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
