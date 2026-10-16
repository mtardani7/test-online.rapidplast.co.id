@extends('layouts.sidebar')

@section('page-title', 'Manajemen Range')
@section('current-page', 'Range Instruksi')
@section('content')
<div class="space-y-5 sm:space-y-7">
    @php
        $questionPositions = $numberedQuestions->mapWithKeys(function ($question) {
            return [$question->id => $question->display_number];
        });
    @endphp
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <details class="group rounded-2xl border border-blue-100 bg-blue-50/70 p-4 sm:p-5">
        <summary class="flex cursor-pointer list-none items-center justify-between gap-3 text-blue-900">
            <span class="flex items-center gap-2 font-semibold">
                <svg class="h-5 w-5 shrink-0 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 4h.01M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z" />
                </svg>
                Panduan mengatur range
            </span>
            <span class="text-sm text-blue-700 group-open:hidden">Lihat aturan</span>
            <span class="hidden text-sm text-blue-700 group-open:inline">Tutup</span>
        </summary>
        <div class="mt-4 grid gap-3 border-t border-blue-100 pt-4 text-sm text-blue-900 sm:grid-cols-2 lg:grid-cols-3">
            <p><strong>1.</strong> Range menentukan kelompok soal yang menerima instruksi.</p>
            <p><strong>2.</strong> Soal yang sudah dipakai tidak bisa dipilih lagi.</p>
            <p><strong>3.</strong> Soal awal harus sama dengan atau sebelum soal akhir.</p>
            <p><strong>4.</strong> Nomor urut range harus unik.</p>
            <p><strong>5.</strong> Range nonaktif tidak tampil kepada peserta.</p>
        </div>
    </details>

    <section class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-6">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
            <div class="min-w-0">
                <nav class="mb-3 flex flex-wrap items-center gap-2 text-xs font-medium text-slate-500 sm:text-sm" aria-label="Breadcrumb">
                    <a href="{{ route('test.index') }}" class="hover:text-blue-700">Tes</a>
                    <span aria-hidden="true">/</span>
                    <a href="{{ route('type-test.index', $test->slug) }}" class="hover:text-blue-700">{{ $typeTest->name }}</a>
                    <span aria-hidden="true">/</span>
                    <a href="{{ route('sub-test.index', [$test->slug, $typeTest->slug]) }}" class="hover:text-blue-700">{{ $subTest->name }}</a>
                    <span aria-hidden="true">/</span>
                    <span class="text-slate-700">Range</span>
                </nav>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Range instruksi</h1>
                <p class="mt-1 text-sm text-slate-600 sm:text-base">Atur kelompok soal dan urutan instruksi untuk {{ $subTest->name }}.</p>
            </div>
            <div class="grid grid-cols-2 gap-2 sm:flex sm:flex-wrap">
                <a href="{{ route('sub-test.ranges.preview', [$test->slug, $typeTest->slug, $subTest->slug]) }}"
                   class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7Z" />
                        <circle cx="12" cy="12" r="3" stroke-width="2" />
                    </svg>
                    Preview
                </a>
                <button type="button" data-modal-open="addRangeModal"
                    class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m7-7H5" />
                    </svg>
                    Tambah range
                </button>
            </div>
        </div>
        <div class="mt-6 grid gap-3 sm:grid-cols-3">
            <div class="rounded-xl bg-slate-50 p-4">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Total soal</p>
                <p class="mt-1 text-2xl font-bold text-slate-900">{{ $numberedQuestions->count() }}</p>
            </div>
            <div class="rounded-xl bg-blue-50 p-4">
                <p class="text-xs font-semibold uppercase tracking-wide text-blue-700">Soal terpakai</p>
                <p class="mt-1 text-2xl font-bold text-blue-900">{{ count($usedQuestionNumbers) }} <span class="text-sm font-medium text-blue-700">/ {{ $numberedQuestions->count() }}</span></p>
            </div>
            <div class="rounded-xl bg-violet-50 p-4">
                <p class="text-xs font-semibold uppercase tracking-wide text-violet-700">Jumlah range</p>
                <p class="mt-1 text-2xl font-bold text-violet-900">{{ $ranges->count() }}</p>
            </div>
        </div>
    </section>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[340px]">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-2 py-3 text-left text-xs font-semibold text-slate-600 sm:px-4 sm:text-sm">#</th>
                        <th class="px-2 py-3 text-left text-xs font-semibold text-slate-600 sm:px-4 sm:text-sm">Rentang</th>
                        <th class="hidden px-4 py-3 text-left text-sm font-semibold text-slate-600 sm:table-cell">Jumlah</th>
                        <th class="hidden px-4 py-3 text-left text-sm font-semibold text-slate-600 sm:table-cell">Urutan</th>
                        <th class="px-2 py-3 text-left text-xs font-semibold text-slate-600 sm:px-4 sm:text-sm">Status</th>
                        <th class="px-2 py-3 text-right text-xs font-semibold text-slate-600 sm:px-4 sm:text-sm">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($ranges as $range)
                    @php
                        $startIndex = ($questionPositions->get($range->start_number) ?? 1) - 1;
                        $endIndex = ($questionPositions->get($range->end_number) ?? 1) - 1;
                        $questionCount = ($endIndex !== false && $startIndex !== false) ? ($endIndex - $startIndex + 1) : 0;
                    @endphp
                    <tr class="transition-colors hover:bg-slate-50">
                        <td class="px-2 py-3 font-semibold text-slate-500 sm:px-4 sm:py-4">{{ $loop->iteration }}</td>
                        <td class="px-2 py-3 sm:px-4 sm:py-4">
                            <div class="flex items-center gap-1.5 sm:gap-2">
                                <span class="rounded-lg bg-blue-50 px-2 py-1.5 text-xs font-semibold text-blue-700 sm:px-3 sm:py-2 sm:text-sm">{{ $startIndex + 1 }}</span>
                                <svg class="h-3.5 w-3.5 shrink-0 text-slate-400 sm:h-4 sm:w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-6-6 6 6-6 6" /></svg>
                                <span class="rounded-lg bg-blue-50 px-2 py-1.5 text-xs font-semibold text-blue-700 sm:px-3 sm:py-2 sm:text-sm">{{ $endIndex + 1 }}</span>
                            </div>
                        </td>
                        <td class="hidden px-4 py-4 sm:table-cell">
                            <span class="px-3 py-1 bg-gray-100 text-gray-700 text-sm rounded-full font-medium">
                                {{ $questionCount }} soal
                            </span>
                        </td>
                        <td class="hidden px-4 py-4 sm:table-cell">
                            <span class="px-3 py-1 bg-purple-100 text-purple-700 text-sm rounded-full font-medium">
                                #{{ $range->order_number }}
                            </span>
                        </td>
                        <td class="px-2 py-3 sm:px-4 sm:py-4">
                            @if ($range->is_active)
                                <span class="px-2 py-1 text-[11px] bg-green-100 text-green-700 rounded-full font-medium flex items-center gap-1 w-fit sm:px-3 sm:text-xs">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    Aktif
                                </span>
                            @else
                                <span class="px-2 py-1 text-[11px] bg-gray-100 text-gray-500 rounded-full font-medium flex items-center gap-1 w-fit sm:px-3 sm:text-xs">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                    Nonaktif
                                </span>
                            @endif
                        </td>
                        <td class="px-2 py-3 text-right sm:px-4 sm:py-4 sm:space-x-2">
                            <button type="button" aria-label="Edit range {{ $range->order_number }}" title="Edit range"
                                class="inline-flex min-h-10 items-center justify-center gap-2 rounded-xl border border-blue-200 bg-blue-50 px-3 py-2 text-sm font-semibold text-blue-700 transition hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                data-modal-open="editRangeModal{{ $range->id }}"
                                onclick="loadEditModalData({{ $range->id }})">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                <span class="hidden sm:inline">Edit</span>
                            </button>

                            <form action="{{ route('instruction-range.destroy', [$test->slug, $typeTest->slug, $subTest->slug, $range->id]) }}"
                                  method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" aria-label="Hapus range {{ $range->order_number }}" title="Hapus range"
                                    onclick="return confirm('Yakin ingin menghapus range ini?')"
                                    class="inline-flex min-h-10 items-center justify-center gap-2 rounded-xl border border-rose-200 bg-rose-50 px-2.5 py-2 text-sm font-semibold text-rose-700 transition hover:bg-rose-100 focus:outline-none focus:ring-2 focus:ring-rose-500 sm:px-3">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    <span class="hidden sm:inline">Hapus</span>
                                </button>
                            </form>
                        </td>
                    </tr>

                    <div id="editRangeModal{{ $range->id }}" 
                         class="hidden fixed inset-0 z-50 flex items-center justify-center overflow-y-auto bg-slate-950/60 p-2 backdrop-blur-sm sm:p-4">
                        <div class="my-auto max-h-[96vh] w-full max-w-lg overflow-y-auto rounded-2xl bg-white shadow-2xl sm:max-h-[90vh]">
                            <form action="{{ route('instruction-range.update', [$test->slug, $typeTest->slug, $subTest->slug, $range->id]) }}" method="POST" id="editForm{{ $range->id }}">
                                @csrf
                                @method('PUT')
                                <div class="px-6 py-4 border-b bg-gray-50 rounded-t-xl">
                                    <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Edit Range Instruksi
                                    </h2>
                                    <p class="text-gray-600 text-sm mt-1">ID Range: {{ $range->id }} • Order: {{ $range->order_number }}</p>
                                </div>
                                <div class="space-y-5 p-4 sm:space-y-6 sm:p-6">
                                    <div class="p-3 bg-blue-50 rounded-lg border border-blue-200">
                                        <h4 class="text-sm font-semibold text-blue-800 mb-2">Range Saat Ini:</h4>
                                        <div class="grid grid-cols-2 gap-3">
                                            <div>
                                                <span class="text-xs text-gray-600">Start Soal</span>
                                                <p class="font-medium">Soal {{ $startIndex + 1 }}</p>
                                            </div>
                                            <div>
                                                <span class="text-xs text-gray-600">End Soal</span>
                                                <p class="font-medium">Soal {{ $endIndex + 1 }}</p>
                                            </div>
                                            <div>
                                                <span class="text-xs text-gray-600">Jumlah Soal</span>
                                                <p class="font-medium">{{ $questionCount }} soal</p>
                                            </div>
                                            <div>
                                                <span class="text-xs text-gray-600">Status</span>
                                                <p class="font-medium {{ $range->is_active ? 'text-green-600' : 'text-gray-600' }}">
                                                    {{ $range->is_active ? 'Aktif' : 'Nonaktif' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                Start Soal (Nomor Urut)
                                                <span class="text-xs text-gray-500 ml-1">(wajib)</span>
                                            </label>
                                            <select name="start_number" 
                                                    class="w-full border border-gray-300 px-4 py-3 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                                    required
                                                    id="editStartNumber{{ $range->id }}"
                                                    onchange="validateEditRange({{ $range->id }})">
                                                <option value="">-- Pilih Soal Awal --</option>
                                                @foreach($numberedQuestions as $q)
                                                    @php
                                                        $isUsed = in_array($q->display_number, $usedQuestionNumbers);
                                                        $isCurrent = ($startIndex + 1) == $q->display_number;
                                                    @endphp
                                                    <option value="{{ $q->display_number }}" 
                                                        {{ ($startIndex + 1) == $q->display_number ? 'selected' : '' }}
                                                        @if($isUsed && !$isCurrent) disabled class="bg-gray-100 text-gray-400" @endif>
                                                        {{ $q->display_number }}. 
                                                        {{ $q->question_text ? Str::limit(strip_tags($q->question_text), 40) : '[Gambar Soal]' }}
                                                        @if($isUsed && !$isCurrent) (Sudah digunakan) @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                End Soal (Nomor Urut)
                                                <span class="text-xs text-gray-500 ml-1">(wajib)</span>
                                            </label>
                                            <select name="end_number" 
                                                    class="w-full border border-gray-300 px-4 py-3 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                                    required
                                                    id="editEndNumber{{ $range->id }}"
                                                    onchange="validateEditRange({{ $range->id }})">
                                                <option value="">-- Pilih Soal Akhir --</option>
                                                @foreach($numberedQuestions as $q)
                                                    @php
                                                        $isUsed = in_array($q->display_number, $usedQuestionNumbers);
                                                        $isCurrent = ($endIndex + 1) == $q->display_number;
                                                    @endphp
                                                    <option value="{{ $q->display_number }}"
                                                        {{ ($endIndex + 1) == $q->display_number ? 'selected' : '' }}
                                                        @if($isUsed && !$isCurrent) disabled class="bg-gray-100 text-gray-400" @endif>
                                                        {{ $q->display_number }}. 
                                                        {{ $q->question_text ? Str::limit(strip_tags($q->question_text), 40) : '[Gambar Soal]' }}
                                                        @if($isUsed && !$isCurrent) (Sudah digunakan) @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                Order Number
                                                <span class="text-xs text-gray-500 ml-1">(harus unik)</span>
                                            </label>
                                            <input type="number" name="order_number" 
                                                   class="w-full border border-gray-300 px-4 py-3 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                                   value="{{ $range->order_number }}" 
                                                   min="1" required>
                                        </div>
                                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                                    Status Range
                                                </label>
                                                <p class="text-xs text-gray-600">
                                                    Nonaktifkan untuk menyembunyikan instruksi dari peserta
                                                </p>
                                            </div>
                                            <label class="relative inline-flex items-center cursor-pointer">
                                                <input type="checkbox" name="is_active" 
                                                       class="sr-only peer" value="1"
                                                       {{ $range->is_active ? 'checked' : '' }}>
                                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                                <span class="ml-3 text-sm font-medium text-gray-700">
                                                    {{ $range->is_active ? 'Aktif' : 'Nonaktif' }}
                                                </span>
                                            </label>
                                        </div>
                                        <div id="validationMessage{{ $range->id }}" class="hidden p-3 bg-red-50 border border-red-200 rounded-lg">
                                            <p class="text-red-600 text-sm flex items-center gap-2">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                </svg>
                                                <span id="validationText{{ $range->id }}"></span>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex flex-col-reverse gap-2 border-t bg-gray-50 p-4 sm:flex-row sm:justify-end sm:gap-3 sm:px-6 sm:py-4">
                                    <button type="button" 
                                        class="min-h-11 rounded-xl border border-gray-300 px-5 py-2 font-medium text-gray-700 transition hover:bg-gray-50"
                                        data-modal-close="editRangeModal{{ $range->id }}">
                                        Batal
                                    </button>

                                    <button type="submit" 
                                        class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-2 font-medium text-white transition hover:bg-blue-700">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Simpan Perubahan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-400">
                                <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p class="text-lg font-medium text-gray-500 mb-1">Belum ada range instruksi</p>
                                <p class="text-sm text-gray-400">Klik "Tambah Range" untuk membuat range instruksi pertama</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="addRangeModal"
     class="hidden fixed inset-0 z-50 flex items-center justify-center overflow-y-auto bg-slate-950/60 p-2 backdrop-blur-sm sm:p-4">
    <div class="my-auto max-h-[96vh] w-full max-w-lg overflow-y-auto rounded-2xl bg-white shadow-2xl sm:max-h-[90vh]">
        <form action="{{ route('instruction-range.store', [$test->slug, $typeTest->slug, $subTest->slug]) }}" method="POST" id="addRangeForm">
            @csrf
            <div class="px-6 py-4 border-b bg-gray-50 rounded-t-xl">
                <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Range Instruksi Baru
                </h2>
                <p class="text-gray-600 text-sm mt-1">Buat range instruksi baru untuk subtest {{ $subTest->name }}</p>
            </div>
            <div class="space-y-5 p-4 sm:space-y-6 sm:p-6">
                <div class="p-3 bg-blue-50 rounded-lg border border-blue-200">
                    <h4 class="text-sm font-semibold text-blue-800 mb-2">Ketersediaan Soal:</h4>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <span class="text-xs text-gray-600">Total Soal</span>
                            <p class="font-medium">{{ $numberedQuestions->count() }} soal</p>
                        </div>
                        <div>
                            <span class="text-xs text-gray-600">Sudah Digunakan</span>
                            <p class="font-medium {{ count($usedQuestionNumbers) == $numberedQuestions->count() ? 'text-red-600' : 'text-blue-600' }}">
                                {{ count($usedQuestionNumbers) }} soal
                            </p>
                        </div>
                        <div class="col-span-2">
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" 
                                     style="width: {{ $numberedQuestions->count() > 0 ? (count($usedQuestionNumbers) / $numberedQuestions->count() * 100) : 0 }}%"></div>
                            </div>
                            <p class="text-xs text-gray-600 mt-1">
                                {{ $numberedQuestions->count() > 0 ? round(count($usedQuestionNumbers) / $numberedQuestions->count() * 100, 1) : 0 }}% soal sudah digunakan
                            </p>
                        </div>
                    </div>
                </div>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Start Soal (Nomor Urut)
                            <span class="text-xs text-gray-500 ml-1">(wajib)</span>
                        </label>
                        <select name="start_number" 
                                class="w-full border border-gray-300 px-4 py-3 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition"
                                required 
                                id="startNumberSelect"
                                onchange="validateAddRange()">
                            <option value="">-- Pilih Soal Awal --</option>
                            @foreach($numberedQuestions as $q)
                                @php
                                    $isUsed = in_array($q->display_number, $usedQuestionNumbers);
                                @endphp
                                <option value="{{ $q->display_number }}" 
                                    {{ old('start_number') == $q->display_number ? 'selected' : '' }}
                                    @if($isUsed) disabled class="bg-gray-100 text-gray-400" @endif>
                                    {{ $q->display_number }}. 
                                    {{ $q->question_text ? Str::limit(strip_tags($q->question_text), 50) : '[Gambar Soal]' }}
                                    @if($isUsed) (Sudah digunakan) @endif
                                </option>
                            @endforeach
                        </select>
                        @error('start_number')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            End Soal (Nomor Urut)
                            <span class="text-xs text-gray-500 ml-1">(wajib)</span>
                        </label>
                        <select name="end_number" 
                                class="w-full border border-gray-300 px-4 py-3 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition"
                                required 
                                id="endNumberSelect"
                                onchange="validateAddRange()">
                            <option value="">-- Pilih Soal Akhir --</option>
                            @foreach($numberedQuestions as $q)
                                @php
                                    $isUsed = in_array($q->display_number, $usedQuestionNumbers);
                                @endphp
                                <option value="{{ $q->display_number }}"
                                    {{ old('end_number') == $q->display_number ? 'selected' : '' }}
                                    @if($isUsed) disabled class="bg-gray-100 text-gray-400" @endif>
                                    {{ $q->display_number }}. 
                                    {{ $q->question_text ? Str::limit(strip_tags($q->question_text), 50) : '[Gambar Soal]' }}
                                    @if($isUsed) (Sudah digunakan) @endif
                                </option>
                            @endforeach
                        </select>
                        @error('end_number')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Order Number
                            <span class="text-xs text-gray-500 ml-1">(harus unik, angka lebih kecil muncul lebih dulu)</span>
                        </label>
                        <input type="number" name="order_number" 
                               class="w-full border border-gray-300 px-4 py-3 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition"
                               value="{{ old('order_number') }}" 
                               min="1" required>
                        @error('order_number')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Status Range
                            </label>
                            <p class="text-xs text-gray-600">
                                Nonaktifkan untuk menyembunyikan instruksi dari peserta
                            </p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" 
                                   class="sr-only peer" value="1"
                                   {{ old('is_active') ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-red-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-600"></div>
                            <span class="ml-3 text-sm font-medium text-gray-700">
                                {{ old('is_active') ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </label>
                    </div>

                    <div id="addValidationMessage" class="hidden p-3 bg-red-50 border border-red-200 rounded-lg">
                        <p class="text-red-600 text-sm flex items-center gap-2">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            <span id="addValidationText"></span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex flex-col-reverse gap-2 border-t bg-gray-50 p-4 sm:flex-row sm:justify-end sm:gap-3 sm:px-6 sm:py-4">
                <button type="button" 
                    class="min-h-11 rounded-xl border border-gray-300 px-5 py-2 font-medium text-gray-700 transition hover:bg-gray-50"
                    data-modal-close="addRangeModal">
                    Batal
                </button>

                <button type="submit" 
                    class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-2 font-medium text-white transition hover:bg-blue-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Tambah Range
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.querySelectorAll("[data-modal-open]").forEach(btn => {
    btn.addEventListener("click", () => {
        const modalId = btn.getAttribute("data-modal-open");
        document.getElementById(modalId).classList.remove("hidden");
        document.body.style.overflow = 'hidden';
    });
});

document.querySelectorAll("[data-modal-close]").forEach(btn => {
    btn.addEventListener("click", () => {
        const modalId = btn.getAttribute("data-modal-close");
        document.getElementById(modalId).classList.add("hidden");
        document.body.style.overflow = 'auto';
    });
});

document.querySelectorAll('[id*="Modal"]').forEach(modal => {
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    });
});

document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        document.querySelectorAll('[id*="Modal"]').forEach(modal => {
            if (!modal.classList.contains('hidden')) {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        });
    }
});

function validateAddRange() {
    const startSelect = document.getElementById('startNumberSelect');
    const endSelect = document.getElementById('endNumberSelect');
    const validationMessage = document.getElementById('addValidationMessage');
    const validationText = document.getElementById('addValidationText');
    
    if (!startSelect || !endSelect) return;
    
    const startValue = parseInt(startSelect.value);
    const endValue = parseInt(endSelect.value);
    
    validationMessage.classList.add('hidden');
    
    if (!isNaN(startValue) && !isNaN(endValue)) {
        if (startValue > endValue) {
            validationText.textContent = 'Start soal harus lebih kecil atau sama dengan end soal';
            validationMessage.classList.remove('hidden');
            endSelect.value = '';
        }
    }
}

function validateEditRange(rangeId) {
    const startSelect = document.getElementById('editStartNumber' + rangeId);
    const endSelect = document.getElementById('editEndNumber' + rangeId);
    const validationMessage = document.getElementById('validationMessage' + rangeId);
    const validationText = document.getElementById('validationText' + rangeId);
    
    if (!startSelect || !endSelect) return;
    
    const startValue = parseInt(startSelect.value);
    const endValue = parseInt(endSelect.value);
    
    validationMessage.classList.add('hidden');
    
    if (!isNaN(startValue) && !isNaN(endValue)) {
        if (startValue > endValue) {
            validationText.textContent = 'Start soal harus lebih kecil atau sama dengan end soal';
            validationMessage.classList.remove('hidden');
            endSelect.value = '';
        }
    }
}

document.querySelectorAll('input[name="is_active"]').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        const span = this.closest('label').querySelector('span:last-child');
        if (span) {
            span.textContent = this.checked ? 'Aktif' : 'Nonaktif';
        }
    });
});

document.getElementById('startNumberSelect')?.addEventListener('change', function() {
    const endSelect = document.getElementById('endNumberSelect');
    if (endSelect && !endSelect.value && this.value) {
        endSelect.value = this.value;
    }
});

document.getElementById('addRangeForm')?.addEventListener('submit', function(e) {
    const startSelect = document.getElementById('startNumberSelect');
    const endSelect = document.getElementById('endNumberSelect');
    
    if (!startSelect.value || !endSelect.value) {
        e.preventDefault();
        alert('Harap pilih start dan end soal');
        return;
    }
    
    const startValue = parseInt(startSelect.value);
    const endValue = parseInt(endSelect.value);
    
    if (startValue > endValue) {
        e.preventDefault();
        alert('Start soal harus lebih kecil atau sama dengan end soal');
        return;
    }
});

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('input[name="is_active"]').forEach(checkbox => {
        const span = checkbox.closest('label')?.querySelector('span:last-child');
        if (span) {
            span.textContent = checkbox.checked ? 'Aktif' : 'Nonaktif';
        }
    });
});
</script>

<style>
#addRangeModal > div, [id*="editRangeModal"] > div {
    scrollbar-width: thin;
    scrollbar-color: #cbd5e1 #f1f5f9;
}

#addRangeModal > div::-webkit-scrollbar, 
[id*="editRangeModal"] > div::-webkit-scrollbar {
    width: 6px;
}

#addRangeModal > div::-webkit-scrollbar-track, 
[id*="editRangeModal"] > div::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 3px;
}

#addRangeModal > div::-webkit-scrollbar-thumb, 
[id*="editRangeModal"] > div::-webkit-scrollbar-thumb {
    background-color: #cbd5e1;
    border-radius: 3px;
}

.toggle-switch {
    position: relative;
    display: inline-block;
    width: 60px;
    height: 34px;
}

.toggle-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.toggle-slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    transition: .4s;
    border-radius: 34px;
}

.toggle-slider:before {
    position: absolute;
    content: "";
    height: 26px;
    width: 26px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    transition: .4s;
    border-radius: 50%;
}

input:checked + .toggle-slider {
    background-color: #2196F3;
}

input:checked + .toggle-slider:before {
    transform: translateX(26px);
}
</style>
@endsection
