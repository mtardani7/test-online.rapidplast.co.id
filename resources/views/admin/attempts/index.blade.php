@extends('layouts.sidebar')

@section('page-title', 'Reset Jawaban')
@section('current-page', 'Reset Jawaban')

@section('content')
@php
    $pageAverageScore = $attempts->getCollection()->isNotEmpty()
        ? round($attempts->getCollection()->avg('score'), 1)
        : null;
    $activeFilters = collect(['search', 'user_id', 'test_id', 'date_from', 'date_to', 'score_min', 'score_max'])
        ->filter(fn ($key) => request()->filled($key))
        ->count();
@endphp
<div class="mx-auto max-w-7xl space-y-6 px-3 py-5 sm:px-6 sm:py-7 lg:px-8">
    <section class="overflow-hidden rounded-2xl bg-gradient-to-br from-rose-800 via-rose-700 to-red-600 p-5 text-white shadow-lg sm:p-7">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm font-semibold text-rose-100">Administrasi data ujian</p>
                <h1 class="mt-1 text-2xl font-bold tracking-tight sm:text-3xl">Reset jawaban</h1>
                <p class="mt-2 max-w-2xl text-sm leading-relaxed text-rose-100 sm:text-base">Cari hasil tes peserta, periksa rinciannya, lalu pilih data yang ingin dihapus.</p>
            </div>
            <div class="rounded-xl border border-white/20 bg-white/10 p-3 text-sm leading-relaxed text-rose-50 sm:max-w-sm">
                <strong class="block text-white">Perhatian</strong>
                Penghapusan tidak dapat dibatalkan. Tombol pada setiap tingkat hanya menghapus data pada tingkat tersebut.
            </div>
        </div>
        <div id="attemptMetrics" class="mt-6 grid grid-cols-2 gap-3 xl:grid-cols-4">
            <div class="rounded-xl border border-white/15 bg-white/10 p-4"><p class="text-xs font-medium text-rose-100">Hasil ditemukan</p><p class="mt-1 text-2xl font-bold">{{ number_format($attempts->total(), 0, ',', '.') }}</p></div>
            <div class="rounded-xl border border-white/15 bg-white/10 p-4"><p class="text-xs font-medium text-rose-100">Di halaman ini</p><p class="mt-1 text-2xl font-bold">{{ $attempts->count() }}</p></div>
            <div class="rounded-xl border border-white/15 bg-white/10 p-4"><p class="text-xs font-medium text-rose-100">Rata-rata skor halaman ini</p><p class="mt-1 text-2xl font-bold">{{ $pageAverageScore ?? '—' }}</p></div>
            <div class="rounded-xl border border-white/15 bg-white/10 p-4"><p class="text-xs font-medium text-rose-100">Filter aktif</p><p class="mt-1 text-2xl font-bold">{{ $activeFilters }}</p></div>
        </div>
    </section>

    <section class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-6">
        <div class="mb-5">
            <h2 class="text-lg font-bold text-slate-900">Cari hasil tes</h2>
            <p class="mt-1 text-sm text-slate-500">Cari nama atau email peserta. Filter tes, tanggal, dan nilai tersedia di bagian filter detail.</p>
        </div>
        
        <form method="GET" action="{{ route('admin.attempts.index') }}" id="filterForm" class="space-y-3">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-end">
            <div class="min-w-0 flex-1">
                <label for="attemptSearch" class="mb-1 block text-sm font-semibold text-slate-700">Cari kandidat</label>
                <div class="relative">
                    <input id="attemptSearch" type="search" name="search" value="{{ request('search') }}" autocomplete="off"
                           placeholder="Ketik nama atau email kandidat..."
                           class="min-h-12 w-full rounded-xl border border-slate-200 py-3 pl-11 pr-12 text-sm text-slate-800 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-rose-400 focus:ring-4 focus:ring-rose-100">
                    <svg class="absolute left-3.5 top-3.5 h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <button type="button" id="clearAttemptSearch" aria-label="Hapus teks pencarian" class="{{ request('search') ? '' : 'hidden' }} absolute right-2 top-2 inline-flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-100 hover:text-slate-700">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m6 6 12 12M18 6 6 18" /></svg>
                    </button>
                </div>
            </div>
            </div>
            <details class="group rounded-xl border border-slate-200 bg-slate-50/70 p-3 sm:p-4" {{ request()->hasAny(['test_id', 'date_from', 'date_to', 'score_min', 'score_max', 'per_page']) ? 'open' : '' }}>
                <summary class="flex cursor-pointer list-none items-center justify-between gap-3 font-semibold text-slate-700">
                    <span class="flex items-center gap-2">
                        <svg class="h-4 w-4 text-rose-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.5 6.75h9m3 0h3m-15 10.5h3m3 0h12M13.5 4.5v4.5m-3 6v4.5" /><circle cx="15" cy="6.75" r="1.5" stroke-width="1.8" /><circle cx="9" cy="17.25" r="1.5" stroke-width="1.8" /></svg>
                        Filter detail
                        <span id="activeFilterCount" class="{{ $activeFilters > 0 ? '' : 'hidden' }} rounded-full bg-rose-100 px-2 py-0.5 text-xs text-rose-800">{{ $activeFilters }} aktif</span>
                    </span>
                    <svg class="h-4 w-4 shrink-0 text-slate-400 transition-transform group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m6 9 6 6 6-6" /></svg>
                </summary>
                <div class="mt-4 grid grid-cols-1 gap-3 border-t border-slate-200 pt-4 sm:grid-cols-2 xl:grid-cols-4">
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Tes</label>
                <select name="test_id" class="min-h-11 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm focus:border-rose-400 focus:outline-none focus:ring-4 focus:ring-rose-100">
                    <option value="">Semua tes</option>
                    @foreach($tests as $test)
                        <option value="{{ $test->id }}" {{ request('test_id') == $test->id ? 'selected' : '' }}>
                            {{ $test->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Urutan</label>
                <select name="sort_order" class="min-h-11 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm focus:border-rose-400 focus:outline-none focus:ring-4 focus:ring-rose-100">
                    <option value="desc" {{ request('sort_order', 'desc') == 'desc' ? 'selected' : '' }}>Terbaru</option>
                    <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Terlama</option>
                </select>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Tanggal mulai</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" 
                       class="min-h-11 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm focus:border-rose-400 focus:outline-none focus:ring-4 focus:ring-rose-100">
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Tanggal akhir</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" 
                       class="min-h-11 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm focus:border-rose-400 focus:outline-none focus:ring-4 focus:ring-rose-100">
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Skor minimum</label>
                <input type="number" name="score_min" value="{{ request('score_min') }}" 
                       placeholder="0"
                       class="min-h-11 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm focus:border-rose-400 focus:outline-none focus:ring-4 focus:ring-rose-100">
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Skor maksimum</label>
                <input type="number" name="score_max" value="{{ request('score_max') }}" 
                       placeholder="100"
                       class="min-h-11 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm focus:border-rose-400 focus:outline-none focus:ring-4 focus:ring-rose-100">
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Hasil per halaman</label>
                <select name="per_page" class="min-h-11 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm focus:border-rose-400 focus:outline-none focus:ring-4 focus:ring-rose-100">
                    <option value="5" {{ request('per_page', 10) == 5 ? 'selected' : '' }}>5</option>
                    <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                    <option value="20" {{ request('per_page', 10) == 20 ? 'selected' : '' }}>20</option>
                    <option value="50" {{ request('per_page', 10) == 50 ? 'selected' : '' }}>50</option>
                </select>
            </div>
                <div class="flex items-center justify-between gap-3 sm:col-span-2 xl:col-span-4">
                    <p class="text-xs text-slate-500">Filter akan diterapkan otomatis saat nilainya diubah.</p>
                    <a href="{{ route('admin.attempts.index') }}" class="inline-flex min-h-10 items-center justify-center rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-100">Reset filter</a>
                </div>
                </div>
            </details>
        </form>
    </section>

    @if(session('success'))
        <div role="status" class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div role="alert" class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">{{ $errors->first() }}</div>
    @endif

    <div id="confirmModal" role="dialog" aria-modal="true" aria-labelledby="modal-title" aria-describedby="modal-message" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-950/65 p-0 backdrop-blur-sm sm:p-4">
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center sm:items-center">
                <div data-dialog-panel class="relative my-auto w-full max-w-md transform overflow-hidden rounded-t-3xl bg-white shadow-2xl transition-all sm:rounded-2xl">
                    <div class="border-b border-rose-100 bg-gradient-to-br from-rose-50 to-white px-5 pb-5 pt-6 sm:px-6">
                        <div class="flex items-start gap-4">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-rose-100 text-rose-700">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <h3 class="text-lg font-bold text-slate-900" id="modal-title">Konfirmasi penghapusan</h3>
                                <p class="mt-1 text-sm leading-relaxed text-slate-600" id="modal-message">Tinjau data yang akan dihapus sebelum melanjutkan.</p>
                            </div>
                        </div>
                        <div class="mt-4 rounded-xl border border-rose-200 bg-white px-4 py-3">
                            <p class="text-[11px] font-bold uppercase tracking-wider text-rose-700">Cakupan penghapusan</p>
                            <p id="modal-subject" class="mt-1 break-words text-sm font-semibold text-slate-800">Belum dipilih</p>
                        </div>
                        <p class="mt-3 flex items-start gap-2 text-xs leading-relaxed text-rose-800">
                            <svg class="mt-0.5 h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>
                            <span>Data yang dihapus tidak dapat dipulihkan. Pastikan cakupan di atas sudah benar.</span>
                        </p>
                    </div>
                    <div class="flex flex-col gap-2 p-4 sm:flex-row sm:justify-end sm:p-5">
                        <button id="cancelDelete" type="button" onclick="closeDeleteModal()" class="inline-flex min-h-11 w-full items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-400 sm:w-auto">Kembali, periksa lagi</button>
                        <form id="delete-form" method="POST" class="w-full sm:w-auto">
                            @csrf
                            @method('DELETE')
                            <button type="submit" id="confirmDelete"
                                class="inline-flex min-h-11 w-full items-center justify-center gap-2 rounded-xl bg-rose-700 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-rose-800 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2 sm:w-auto">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                Ya, lanjutkan reset
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="attemptResults" aria-live="polite" aria-busy="false">
    @if($attempts->count() > 0)
        @foreach($attempts as $attempt)
        <article class="mb-5 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition-shadow hover:shadow-md">
            <div role="button" tabindex="0" class="group w-full cursor-pointer border-b border-slate-100 bg-gradient-to-r from-rose-50/80 to-white p-4 text-left transition hover:from-rose-100/70 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-rose-500 sm:p-6" aria-expanded="false" aria-controls="attempt-details-{{ $attempt->id }}" onclick="toggleAttemptDetails('attempt-details-{{ $attempt->id }}', this)" onkeydown="if (event.key === 'Enter' || event.key === ' ') { event.preventDefault(); toggleAttemptDetails('attempt-details-{{ $attempt->id }}', this); }">
                <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                    <div class="min-w-0">
                        <div class="mb-3 flex flex-wrap items-center gap-2">
                            <span class="rounded-full bg-rose-100 px-3 py-1 text-xs font-semibold text-rose-800">ID sesi tes: {{ $attempt->id }}</span>
                            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-700">{{ $attempt->tests->name ?? 'Tes tidak ditemukan' }}</span>
                        </div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Kandidat</p>
                        <h2 class="mt-1 break-words text-lg font-bold text-slate-900 sm:text-xl">{{ $attempt->user->name ?? '-' }}</h2>
                        <p class="mt-0.5 break-all text-sm text-slate-500">{{ $attempt->user->email ?? '-' }}</p>
                    </div>
                    <div class="grid grid-cols-2 gap-2 sm:grid-cols-3 lg:min-w-[24rem]">
                        <div class="rounded-xl border border-rose-100 bg-white p-3">
                            <p class="text-xs font-medium text-slate-500">Nilai akhir</p>
                            <p class="mt-1 text-xl font-bold text-rose-700">{{ $attempt->score ?? 0 }}</p>
                        </div>
                        <div class="rounded-xl border border-slate-100 bg-white p-3">
                            <p class="text-xs font-medium text-slate-500">Jawaban benar</p>
                            <p class="mt-1 text-xl font-bold text-emerald-700">{{ $attempt->total_correct ?? 0 }} <span class="text-sm font-medium text-slate-500">dari {{ $attempt->total_question ?? 0 }} soal</span></p>
                        </div>
                        <div class="col-span-2 rounded-xl border border-slate-100 bg-white p-3 sm:col-span-1">
                            <p class="text-xs font-medium text-slate-500">Waktu tes</p>
                            <p class="mt-1 text-sm font-semibold text-slate-800">{{ $attempt->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                </div>
                <div class="mt-4 flex items-center gap-2 text-sm font-semibold text-rose-700">
                    <span>Klik untuk melihat rincian dan opsi reset</span>
                    <svg class="h-4 w-4 shrink-0 transition-transform group-aria-expanded:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m6 9 6 6 6-6" /></svg>
                </div>
            </div>
            <div id="attempt-details-{{ $attempt->id }}" class="hidden space-y-4 p-4 sm:p-6">
                @forelse($attempt->typeTests as $typeTest)
                    <section class="rounded-2xl border border-slate-200 bg-slate-50/70 p-4 transition-colors hover:border-rose-200 sm:p-5">
                        <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="p-2 bg-red-100 rounded-lg">
                                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Bagian tes</p>
                                        <p class="text-lg font-bold text-slate-900">
                                            {{ $typeTest->typeTest->name ?? 'Unknown Type Test' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="mt-4 grid grid-cols-2 gap-3">
                                    <div class="rounded-xl border border-slate-100 bg-white p-3">
                                        <p class="text-xs font-medium text-slate-500">Jawaban benar</p>
                                        <p class="mt-1 text-xl font-bold text-emerald-700">
                                            {{ $typeTest->correct }} dari {{ $typeTest->total }} soal
                                        </p>
                                    </div>
                                    <div class="rounded-xl border border-slate-100 bg-white p-3">
                                        <p class="text-xs font-medium text-slate-500">Tingkat ketepatan</p>
                                        <p class="mt-1 text-xl font-bold text-blue-700">
                                            @php
                                                $accuracy = $typeTest->total > 0 ? ($typeTest->correct / $typeTest->total) * 100 : 0;
                                            @endphp
                                            {{ number_format($accuracy, 1) }}%
                                        </p>
                                    </div>
                                </div>
                                @if($typeTest->subAnswers->count() > 0)
                                    <div class="mt-6">
                                        <p class="mb-3 text-sm font-semibold text-slate-700">Hasil setiap subtes</p>
                                        <div class="grid grid-cols-1 gap-3 md:grid-cols-2 2xl:grid-cols-3">
                                            @foreach($typeTest->subAnswers as $subTest)
                                                <div class="rounded-xl border border-slate-200 bg-white p-4 transition hover:border-rose-200 hover:shadow-sm">
                                                    <div class="flex min-w-0 items-start justify-between gap-3">
                                                        <div class="min-w-0 flex-1">
                                                            <p class="break-words font-semibold leading-snug text-slate-900">
                                                                {{ $subTest->subTest->name ?? 'Unknown Sub Test' }}
                                                            </p>
                                                            <p class="mt-3 inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-sm text-slate-600">
                                                                Skor <span class="font-bold text-emerald-700">{{ $subTest->score }}</span>
                                                            </p>
                                                        </div>
                                                        <button type="button" data-delete-trigger data-delete-url="{{ route('admin.attempts.sub-test.destroy', $subTest->id) }}" data-delete-label="jawaban subtes {{ $subTest->subTest->name ?? 'tidak diketahui' }}" aria-label="Hapus jawaban subtes {{ $subTest->subTest->name ?? 'tidak diketahui' }}"
                                                            class="inline-flex min-h-9 shrink-0 items-center justify-center gap-1.5 rounded-lg border border-rose-200 bg-rose-50 px-2.5 py-1.5 text-xs font-semibold text-rose-700 transition hover:bg-rose-100 focus:outline-none focus:ring-2 focus:ring-rose-500 sm:px-3 sm:text-sm"
                                                            title="Hapus jawaban subtes ini">
                                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                            <span>Hapus</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="flex flex-col gap-2 sm:flex-row lg:flex-col">
                                <button type="button" data-delete-trigger data-delete-url="{{ route('admin.attempts.type-test.destroy', $typeTest->id) }}" data-delete-label="semua jawaban pada bagian tes {{ $typeTest->typeTest->name ?? 'tidak diketahui' }}" aria-label="Hapus semua jawaban pada bagian tes {{ $typeTest->typeTest->name ?? 'tidak diketahui' }}"
                                    class="inline-flex min-h-10 items-center justify-center gap-2 rounded-xl border border-rose-200 bg-white px-4 py-2.5 text-sm font-semibold text-rose-700 transition hover:bg-rose-50 focus:outline-none focus:ring-2 focus:ring-rose-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Hapus semua jawaban di bagian ini
                                </button>
                            </div>
                        </div>
                    </section>
                @empty
                    <div class="text-center py-8 border-2 border-dashed border-gray-300 rounded-xl">
                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p class="text-gray-500 font-medium">Tidak ada type test</p>
                                        <p class="text-sm text-gray-400 mt-1">Belum ada hasil bagian tes untuk sesi ini.</p>
                    </div>
                @endforelse
            </div>
            <div class="flex flex-col gap-3 border-t border-slate-100 bg-white p-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                    <p class="text-xs leading-relaxed text-slate-500">Menghapus seluruh hasil tes akan menghapus nilai akhir dan semua jawaban kandidat untuk tes ini.</p>
                    <button type="button" data-delete-trigger data-delete-url="{{ route('admin.attempts.destroy', $attempt->id) }}" data-delete-label="semua hasil tes {{ $attempt->tests->name ?? 'ini' }} milik {{ $attempt->user->name ?? 'kandidat ini' }}"
                        aria-label="Hapus semua hasil tes untuk {{ $attempt->user->name ?? 'kandidat ini' }}"
                        class="inline-flex min-h-11 shrink-0 items-center justify-center gap-2 rounded-xl bg-rose-700 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-rose-800 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Hapus seluruh hasil tes
                    </button>
            </div>
        </article>
        @endforeach
        @if($attempts->hasPages())
            @php
                $currentPage = $attempts->currentPage();
                $lastPage = $attempts->lastPage();
                $pageStart = max(1, $currentPage - 1);
                $pageEnd = min($lastPage, $currentPage + 1);
            @endphp
            <section class="mt-6 flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5 lg:flex-row lg:items-center lg:justify-between">
                <p class="text-center text-sm text-slate-600 lg:text-left">Menampilkan hasil <strong>{{ $attempts->firstItem() }}</strong>–<strong>{{ $attempts->lastItem() }}</strong> dari <strong>{{ number_format($attempts->total(), 0, ',', '.') }}</strong>.</p>
                <nav aria-label="Navigasi halaman hasil tes" class="min-w-0">
                    <div class="flex items-center justify-between gap-3 sm:hidden">
                        @if($attempts->onFirstPage())
                            <span class="inline-flex min-h-10 items-center rounded-lg border border-slate-200 px-3 text-sm text-slate-300" aria-disabled="true">Sebelumnya</span>
                        @else
                            <a href="{{ $attempts->previousPageUrl() }}" rel="prev" class="inline-flex min-h-10 items-center rounded-lg border border-slate-200 px-3 text-sm font-medium text-slate-700 hover:bg-slate-50">Sebelumnya</a>
                        @endif
                        <span class="whitespace-nowrap text-sm font-semibold text-slate-700">Halaman {{ $currentPage }} dari {{ $lastPage }}</span>
                        @if($attempts->hasMorePages())
                            <a href="{{ $attempts->nextPageUrl() }}" rel="next" class="inline-flex min-h-10 items-center rounded-lg border border-slate-200 px-3 text-sm font-medium text-slate-700 hover:bg-slate-50">Berikutnya</a>
                        @else
                            <span class="inline-flex min-h-10 items-center rounded-lg border border-slate-200 px-3 text-sm text-slate-300" aria-disabled="true">Berikutnya</span>
                        @endif
                    </div>
                    <div class="hidden items-center justify-end gap-1 sm:flex">
                        @if($attempts->onFirstPage())
                            <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-300" aria-disabled="true" aria-label="Halaman sebelumnya"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 18-6-6 6-6" /></svg></span>
                        @else
                            <a href="{{ $attempts->previousPageUrl() }}" rel="prev" class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-600 transition hover:bg-slate-50" aria-label="Halaman sebelumnya"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 18-6-6 6-6" /></svg></a>
                        @endif
                        @if($pageStart > 1)
                            <a href="{{ $attempts->url(1) }}" class="inline-flex h-9 min-w-9 items-center justify-center rounded-lg px-2.5 text-sm font-medium text-slate-600 hover:bg-slate-100">1</a>
                            @if($pageStart > 2)<span class="px-1 text-slate-400" aria-hidden="true">…</span>@endif
                        @endif
                        @for($page = $pageStart; $page <= $pageEnd; $page++)
                            @if($page === $currentPage)
                                <span class="inline-flex h-9 min-w-9 items-center justify-center rounded-lg bg-rose-700 px-2.5 text-sm font-semibold text-white" aria-current="page">{{ $page }}</span>
                            @else
                                <a href="{{ $attempts->url($page) }}" class="inline-flex h-9 min-w-9 items-center justify-center rounded-lg px-2.5 text-sm font-medium text-slate-600 hover:bg-slate-100">{{ $page }}</a>
                            @endif
                        @endfor
                        @if($pageEnd < $lastPage)
                            @if($pageEnd < $lastPage - 1)<span class="px-1 text-slate-400" aria-hidden="true">…</span>@endif
                            <a href="{{ $attempts->url($lastPage) }}" class="inline-flex h-9 min-w-9 items-center justify-center rounded-lg px-2.5 text-sm font-medium text-slate-600 hover:bg-slate-100">{{ $lastPage }}</a>
                        @endif
                        @if($attempts->hasMorePages())
                            <a href="{{ $attempts->nextPageUrl() }}" rel="next" class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-600 transition hover:bg-slate-50" aria-label="Halaman berikutnya"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 18 6-6-6-6" /></svg></a>
                        @else
                            <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-300" aria-disabled="true" aria-label="Halaman berikutnya"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 18 6-6-6-6" /></svg></span>
                        @endif
                    </div>
                </nav>
            </section>
        @endif
    @else
        <section class="rounded-2xl border border-dashed border-slate-300 bg-white px-5 py-12 text-center shadow-sm">
            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-500">
                <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8.25 6.75h7.5m-7.5 4.5h7.5m-7.5 4.5h4.5M6 3.75h12A2.25 2.25 0 0 1 20.25 6v12A2.25 2.25 0 0 1 18 20.25H6A2.25 2.25 0 0 1 3.75 18V6A2.25 2.25 0 0 1 6 3.75Z" /></svg>
            </div>
            <h2 class="mt-4 text-lg font-bold text-slate-900">Hasil tes tidak ditemukan</h2>
            <p class="mx-auto mt-1 max-w-md text-sm text-slate-500">Coba ubah kriteria pencarian atau hapus filter untuk melihat data lainnya.</p>
            <a href="{{ route('admin.attempts.index') }}" class="mt-5 inline-flex min-h-10 items-center justify-center rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Hapus semua filter</a>
        </section>
    @endif
    </div>
</div>

<script>
let lastDeleteTrigger = null;

function showDeleteModal(url, itemName = 'data ini') {
    const modal = document.getElementById('confirmModal');
    const form = document.getElementById('delete-form');
    const message = document.getElementById('modal-message');
    const subject = document.getElementById('modal-subject');
    
    form.action = url;
    subject.textContent = itemName;
    message.textContent = 'Anda akan menghapus data pada cakupan berikut. Tindakan ini tidak dapat dibatalkan.';
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    document.getElementById('cancelDelete')?.focus();
}

function closeDeleteModal() {
    const modal = document.getElementById('confirmModal');
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
    lastDeleteTrigger?.focus();
}

function toggleAttemptDetails(detailsId, trigger) {
    const details = document.getElementById(detailsId);
    if (!details) return;
    const expanded = trigger.getAttribute('aria-expanded') === 'true';
    trigger.setAttribute('aria-expanded', String(!expanded));
    details.classList.toggle('hidden', expanded);
}

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('confirmModal').addEventListener('click', function(e) {
        const dialog = this.querySelector('[data-dialog-panel]');
        if (dialog && !dialog.contains(e.target)) {
            closeDeleteModal();
        }
    });

    document.addEventListener('keydown', function(e) {
        const modal = document.getElementById('confirmModal');
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeDeleteModal();
        }
        if (e.key === 'Tab' && !modal.classList.contains('hidden')) {
            const focusable = [...modal.querySelectorAll('button:not([disabled]), input:not([disabled]), a[href], [tabindex]:not([tabindex="-1"])')];
            const first = focusable[0];
            const last = focusable[focusable.length - 1];
            if (e.shiftKey && document.activeElement === first) {
                e.preventDefault();
                last?.focus();
            } else if (!e.shiftKey && document.activeElement === last) {
                e.preventDefault();
                first?.focus();
            }
        }
    });

    const filterForm = document.getElementById('filterForm');
    const searchInput = document.getElementById('attemptSearch');
    let searchTimer;
    let activeRequest;

    function syncFilters(url) {
        const params = new URL(url, window.location.href).searchParams;
        filterForm.querySelectorAll('[name]').forEach(field => {
            if (field.name === 'page') return;
            field.value = params.get(field.name) ?? (field.name === 'per_page' ? '10' : field.name === 'sort_order' ? 'desc' : '');
        });
        document.getElementById('clearAttemptSearch')?.classList.toggle('hidden', !searchInput.value);
        const activeCount = ['search', 'test_id', 'date_from', 'date_to', 'score_min', 'score_max']
            .filter(name => (params.get(name) ?? '').trim() !== '').length;
        const badge = document.getElementById('activeFilterCount');
        badge.textContent = `${activeCount} aktif`;
        badge.classList.toggle('hidden', activeCount === 0);
        const details = filterForm.querySelector('details');
        if (details) details.open = ['test_id', 'date_from', 'date_to', 'score_min', 'score_max', 'per_page']
            .some(name => (params.get(name) ?? '').trim() !== '');
    }

    async function loadAttemptResults(url, addHistory = true, useFormFilters = true) {
        const target = new URL(url, window.location.href);
        if (useFormFilters) {
            target.searchParams.delete('page');
            new URLSearchParams(new FormData(filterForm)).forEach((value, key) => {
                if (value) target.searchParams.set(key, value);
                else target.searchParams.delete(key);
            });
        }
        activeRequest?.abort();
        activeRequest = new AbortController();
        document.getElementById('attemptResults')?.setAttribute('aria-busy', 'true');
        try {
            const response = await fetch(target, {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'text/html' },
                signal: activeRequest.signal,
            });
            if (!response.ok) throw new Error('Gagal memuat hasil');
            const html = await response.text();
            const responseDocument = new DOMParser().parseFromString(html, 'text/html');
            const nextResults = responseDocument.getElementById('attemptResults');
            const nextMetrics = responseDocument.getElementById('attemptMetrics');
            if (!nextResults) throw new Error('Bagian hasil tidak ditemukan');
            document.getElementById('attemptResults').replaceWith(nextResults);
            if (nextMetrics) document.getElementById('attemptMetrics')?.replaceWith(nextMetrics);
            if (addHistory) history.pushState({}, '', target);
            syncFilters(target);
        } catch (error) {
            if (error.name !== 'AbortError') window.location.assign(target);
        } finally {
            document.getElementById('attemptResults')?.setAttribute('aria-busy', 'false');
        }
    }

    filterForm?.addEventListener('submit', event => {
        event.preventDefault();
        loadAttemptResults(new URL(filterForm.action, window.location.href));
    });
    searchInput?.addEventListener('input', () => {
        document.getElementById('clearAttemptSearch')?.classList.toggle('hidden', !searchInput.value);
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => filterForm.requestSubmit(), 350);
    });
    filterForm?.querySelectorAll('select, input[type="date"], input[type="number"]').forEach(field => {
        field.addEventListener('change', () => filterForm.requestSubmit());
    });
    document.getElementById('clearAttemptSearch')?.addEventListener('click', () => {
        searchInput.value = '';
        filterForm.requestSubmit();
        searchInput.focus();
    });

    document.addEventListener('click', event => {
        const deleteTrigger = event.target.closest('[data-delete-trigger]');
        if (deleteTrigger) {
            lastDeleteTrigger = deleteTrigger;
            showDeleteModal(deleteTrigger.dataset.deleteUrl, deleteTrigger.dataset.deleteLabel);
            return;
        }
        const link = event.target.closest('#filterForm a, #attemptResults a');
        if (!link || link.origin !== location.origin || event.ctrlKey || event.metaKey || event.shiftKey || event.altKey) return;
        event.preventDefault();
        const target = new URL(link.href);
        if (link.closest('#filterForm')) {
            filterForm.reset();
            loadAttemptResults(target, true, false);
        } else {
            loadAttemptResults(target, true, false);
        }
    });

    window.addEventListener('popstate', () => {
        syncFilters(location.href);
        loadAttemptResults(location.href, false, false);
    });
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
