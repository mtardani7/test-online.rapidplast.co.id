@extends('layouts.sidebar')

@section('page-title', 'Hasil Tes')
@section('current-page', 'Hasil Tes')
@section('content')
@php
    $pageAttempts = $users->getCollection()->sum(fn ($user) => $user->attempts->count());
    $iqCandidates = $users->getCollection()->filter(fn ($user) => $user->iqData['test_count'] > 0);
    $pageAverageIq = $iqCandidates->isNotEmpty()
        ? round($iqCandidates->avg(fn ($user) => $user->iqData['average_iq']))
        : null;
@endphp
<div class="space-y-6">
    <section class="overflow-hidden rounded-2xl bg-gradient-to-br from-rose-800 via-rose-700 to-red-600 p-5 text-white shadow-lg sm:p-7">
        <div class="flex flex-col gap-6 xl:flex-row xl:items-end xl:justify-between">
            <div>
                <p class="text-sm font-semibold text-rose-100">Pemantauan kandidat</p>
                <h1 class="mt-1 text-2xl font-bold tracking-tight sm:text-3xl">Hasil tes</h1>
                <p class="mt-2 max-w-2xl text-sm text-rose-100 sm:text-base">Cari kandidat, tinjau skor dan detail attempt, lalu unduh laporan sesuai filter yang aktif.</p>
            </div>
            <a href="{{ route('admin.total-test.export') }}?{{ http_build_query(request()->except(['page', 'per_page'])) }}"
               class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl bg-white px-4 py-2.5 text-sm font-semibold text-rose-800 shadow-sm transition hover:bg-rose-50 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-rose-700">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v12m0 0 4-4m-4 4-4-4m-3 8h14" /></svg>
                Unduh Excel
            </a>
        </div>
        <div class="mt-6 grid grid-cols-2 gap-3 xl:grid-cols-4">
            <div class="rounded-xl border border-white/15 bg-white/10 p-4 backdrop-blur-sm">
                <p class="text-xs font-medium text-rose-100">Total kandidat</p>
                <p class="mt-1 text-2xl font-bold">{{ number_format($users->total(), 0, ',', '.') }}</p>
            </div>
            <div class="rounded-xl border border-white/15 bg-white/10 p-4 backdrop-blur-sm">
                <p class="text-xs font-medium text-rose-100">Attempt di halaman ini</p>
                <p class="mt-1 text-2xl font-bold">{{ number_format($pageAttempts, 0, ',', '.') }}</p>
            </div>
            <div class="rounded-xl border border-white/15 bg-white/10 p-4 backdrop-blur-sm">
                <p class="text-xs font-medium text-rose-100">Rata-rata IQ TPA di halaman ini</p>
                <p class="mt-1 text-2xl font-bold">{{ $pageAverageIq ?? '—' }}</p>
            </div>
            <div class="rounded-xl border border-white/15 bg-white/10 p-4 backdrop-blur-sm">
                <p class="text-xs font-medium text-rose-100">Halaman</p>
                <p class="mt-1 text-2xl font-bold">{{ $users->currentPage() }} <span class="text-sm font-medium text-rose-100">/ {{ $users->lastPage() }}</span></p>
            </div>
        </div>
    </section>
    <form method="GET" action="{{ route('admin.total-test.index') }}" id="filterForm" class="mb-4">
        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div class="flex-1">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input
                            type="text"
                            name="q"
                            value="{{ request('q') }}"
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all"
                            placeholder="Cari kandidat berdasarkan nama atau email..."
                        >
                    </div>
                </div>

                <div class="flex w-full flex-wrap items-center gap-2 sm:w-auto sm:gap-3">
                    <button type="button" id="filterBtn" aria-controls="filterOptions" aria-expanded="{{ request()->hasAny(['q','test_type','per_page','start_date','end_date']) ? 'true' : 'false' }}" class="min-h-11 rounded-xl border border-slate-200 px-4 py-3 text-slate-700 transition hover:bg-slate-50 flex items-center">
                        <i class="fas fa-filter mr-2"></i> Filter
                    </button>
                    <button type="submit" class="min-h-11 rounded-xl bg-rose-700 px-4 py-3 text-white transition hover:bg-rose-800 flex items-center">
                        <i class="fas fa-search mr-2"></i> Cari
                    </button>
                    <a href="{{ route('admin.total-test.index') }}" class="min-h-11 rounded-xl border border-slate-200 px-4 py-3 text-slate-700 transition hover:bg-slate-50 flex items-center">
                        <i class="fas fa-redo mr-2"></i> Reset
                    </a>
                </div>
            </div>

            <div id="filterOptions" class="{{ request()->hasAny(['q','test_type','per_page','start_date','end_date']) ? '' : 'hidden' }} mt-4 pt-4 border-t border-gray-200">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
                    @php
                        $types = $tests->pluck('type')->filter()->unique()->values();
                    @endphp
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-2">Tipe Tes</label>
                        <select name="test_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500">
                            <option value="">Semua Tipe Tes</option>
                            @foreach($types as $t)
                                <option value="{{ $t }}" @if(request('test_type') == $t) selected @endif>{{ $t }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-2">Tanggal Mulai</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500">
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-2">Tanggal Akhir</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500">
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-2">Tampilkan per halaman</label>
                        <select name="per_page" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500">
                            @foreach([10,25,50,100] as $n)
                                <option value="{{ $n }}" @if(request('per_page', 10) == $n) selected @endif>{{ $n }} / halaman</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex justify-end gap-2 md:col-span-2 xl:col-span-4">
                        <button type="button" onclick="resetFilters()" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                            Reset Filter
                        </button>
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            Terapkan Filter
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    
    @if(request()->hasAny(['q','test_type','per_page','start_date','end_date']))
    <div class="mb-4">
        <div class="flex flex-wrap gap-2">
            @if(request('q'))
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-blue-100 text-blue-800">
                Pencarian: {{ request('q') }}
                <a href="{{ request()->fullUrlWithQuery(['q' => null]) }}" class="ml-2 text-blue-600 hover:text-blue-800">
                    <i class="fas fa-times"></i>
                </a>
            </span>
            @endif

            @if(request('test_type'))
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-purple-100 text-purple-800">
                Tipe Tes: {{ request('test_type') }}
                <a href="{{ request()->fullUrlWithQuery(['test_type' => null]) }}" class="ml-2 text-purple-600 hover:text-purple-800">
                    <i class="fas fa-times"></i>
                </a>
            </span>
            @endif

            @if(request('start_date') || request('end_date'))
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-green-100 text-green-800">
                Tanggal: 
                {{ request('start_date') ? \Carbon\Carbon::parse(request('start_date'))->format('d M Y') : 'Awal' }}
                -
                {{ request('end_date') ? \Carbon\Carbon::parse(request('end_date'))->format('d M Y') : 'Akhir' }}
                <a href="{{ request()->fullUrlWithQuery(['start_date' => null, 'end_date' => null]) }}" class="ml-2 text-green-600 hover:text-green-800">
                    <i class="fas fa-times"></i>
                </a>
            </span>
            @endif

            @if(request('per_page'))
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-yellow-100 text-yellow-800">
                Per halaman: {{ request('per_page') }}
                <a href="{{ request()->fullUrlWithQuery(['per_page' => null]) }}" class="ml-2 text-yellow-600 hover:text-yellow-800">
                    <i class="fas fa-times"></i>
                </a>
            </span>
            @endif

            <a href="{{ route('admin.total-test.index') }}" class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-gray-100 text-gray-800 hover:bg-gray-200">
                Hapus Semua Filter
            </a>
        </div>
    </div>
    @endif

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-col gap-1 border-b border-slate-100 px-4 py-4 sm:flex-row sm:items-end sm:justify-between sm:px-6">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Daftar kandidat</h2>
                <p class="mt-1 text-sm text-slate-500">Pilih satu kandidat untuk membuka ringkasan dan detail hasil tes.</p>
            </div>
            <span class="text-sm font-medium text-slate-500">{{ number_format($users->total(), 0, ',', '.') }} kandidat ditemukan</span>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($users as $user)
            <div class="accordion-item">
                <button type="button" class="accordion-header w-full p-4 text-left transition-colors duration-200 hover:bg-slate-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-rose-500 sm:p-5"
                     aria-controls="user-{{ $user->id }}" aria-expanded="false" onclick="toggleAccordion('user-{{ $user->id }}')">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex min-w-0 items-center gap-3">
                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-rose-100 font-semibold text-rose-800">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <h3 class="truncate font-semibold text-slate-900">{{ $user->name }}</h3>
                                <p class="truncate text-sm text-slate-500">{{ $user->email }}</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between gap-3 sm:justify-end sm:gap-5">
                            @if($user->iqData['test_count'] > 0)
                            <div class="min-w-0 text-left sm:text-right">
                                <div class="flex flex-wrap items-center gap-2 sm:justify-end">
                                    <span class="text-lg font-bold text-slate-900">{{ $user->iqData['average_iq'] }}</span>
                                    <span class="rounded-full bg-gradient-to-r px-2.5 py-1 text-xs font-semibold text-white {{ App\Http\Controllers\Admin\TotalTestController::getIQCategoryColor($user->iqData['category']) }}">
                                        {{ $user->iqData['category'] }}
                                    </span>
                                </div>
                                <p class="mt-0.5 text-xs text-slate-500">Rata-rata IQ TPA</p>
                            </div>
                            @endif
                            <div class="min-w-[4.5rem] text-right sm:text-center">
                                <div class="text-lg font-bold text-rose-700">
                                    {{ $user->attempts->count() }}
                                </div>
                                <p class="mt-0.5 text-xs text-slate-500">Attempt</p>
                            </div>
                            <div class="ml-1 flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-slate-500">
                                <svg class="accordion-icon h-4 w-4 transition-transform duration-300" id="icon-user-{{ $user->id }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m6 9 6 6 6-6" /></svg>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-slate-500 sm:ml-14 sm:text-sm">
                        <div class="flex items-center gap-1.5">
                            <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 3.75v2.5m8-2.5v2.5M4.75 9h14.5M6.5 5.25h11A1.75 1.75 0 0 1 19.25 7v11A1.75 1.75 0 0 1 17.5 19.75h-11A1.75 1.75 0 0 1 4.75 18V7A1.75 1.75 0 0 1 6.5 5.25Z" /></svg>
                            <span>Bergabung {{ $user->created_at->format('d M Y') }}</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.5 19.5V12m5 7.5V5m5 14.5v-4m5 4V8" /></svg>
                            <span class="text-slate-600">
                            @php
                                $score = $user->attempts
                                    ->sortByDesc('created_at')
                                    ->pluck('score')
                                    ->first(fn ($s) => $s > 0);
                            @endphp

                            Score: {{ $score ?? '-' }}
                            </span>
                        </div>
                    </div>
                </button>
                <div id="user-{{ $user->id }}" class="accordion-content hidden">
                    <div class="border-t border-slate-200 bg-slate-50 p-4 sm:p-6">
                        @if($user->iqData['test_count'] > 0)
                        <div class="mb-5 rounded-2xl border border-rose-100 bg-white p-4 shadow-sm sm:p-5">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <h3 class="font-semibold text-slate-900">Ringkasan IQ TPA</h3>
                                    <p class="mt-1 text-xs text-slate-500">Dihitung dari attempt TPA dalam rentang filter.</p>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="text-lg font-bold text-gray-900">{{ $user->iqData['average_iq'] }}</span>
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold text-white bg-gradient-to-r {{ App\Http\Controllers\Admin\TotalTestController::getIQCategoryColor($user->iqData['category']) }}">
                                        {{ $user->iqData['category'] }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        @if($user->attempts->count() > 0)
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-3">Detail Hasil Test</h3>
                            
                            @foreach($tests as $test)
                                @php
                                    $attempt = $user->attempts
                                        ->where('test_id', $test->id)
                                        ->sortByDesc('created_at')
                                        ->first();
                                @endphp
                                
                                @if($attempt)
                                <div class="mb-4 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                                    <div class="border-b border-slate-100 bg-white px-4 py-4 sm:px-5">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <h4 class="font-semibold text-slate-900">{{ $test->name }}</h4>
                                                <div class="mt-2 flex flex-wrap items-center gap-2">
                                                    <span class="inline-flex items-center gap-1.5 rounded-lg bg-rose-50 px-2.5 py-1 text-sm font-semibold text-rose-800">
                                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m12 3 2.75 5.58 6.16.9-4.45 4.33 1.05 6.12L12 17.04l-5.51 2.89 1.05-6.12L3.09 9.48l6.16-.9L12 3Z" /></svg>
                                                        Skor {{ $attempt->score ?? '0' }}
                                                    </span>
                                                    <span class="inline-flex items-center gap-1.5 text-sm text-slate-500">
                                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="8.25" stroke-width="1.8" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 7.5v5l3 1.75" /></svg>
                                                        {{ $attempt->created_at->format('d M Y') }}
                                                    </span>
                                                </div>
                                            </div>
                                            @php
                                                $testIQ = collect($user->iqData['results'])
                                                    ->firstWhere('attempt_id', $attempt->id);
                                            @endphp
                                            @if($testIQ)
                                            <div class="rounded-xl bg-violet-50 px-3 py-2 text-left sm:text-right">
                                                <div class="text-lg font-bold text-violet-900">{{ $testIQ['iq'] }}</div>
                                                <div class="text-xs font-medium text-violet-700">IQ TPA</div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="divide-y divide-gray-100">
                                        @foreach($attempt->typeAttempts ?? [] as $typeAttempt)
                                            @php
                                                $typeCorrect = $typeAttempt->subAnswers->sum('correct_count');
                                                $typeWrong = $typeAttempt->subAnswers->sum('wrong_count');
                                                $typeTotal = $typeCorrect + $typeWrong;
                                                $typePercentage = $typeTotal > 0 ? round(($typeCorrect / $typeTotal) * 100) : 0;
                                                $subGroups = $typeAttempt->subAnswers->groupBy(function($s) {
                                                    return $s->subTest->name ?? ($s->subTest->title ?? 'Subtest');
                                                });
                                            @endphp
                                            
                                            <div class="px-4 py-4 transition-colors hover:bg-slate-50 sm:px-5">
                                                <div class="flex items-center justify-between mb-2">
                                                    <h5 class="font-medium text-gray-900 text-sm">{{ $typeAttempt->typeTest->name ?? 'Type Test' }}</h5>
                                                    <div class="flex items-center space-x-2">
                                                        <span class="text-xs text-green-600 font-medium">
                                                            ✓ {{ $typeCorrect }}
                                                        </span>
                                                        <span class="text-xs text-red-600 font-medium">
                                                            ✗ {{ $typeWrong }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="w-full bg-gray-200 rounded-full h-1.5 mb-2">
                                                    <div class="bg-gradient-to-r from-green-500 to-green-600 h-1.5 rounded-full" 
                                                         style="width: {{ $typePercentage }}%"></div>
                                                </div>
                                                <div class="flex justify-between text-xs text-gray-500">
                                                    <span>Keakuratan</span>
                                                    <span class="font-medium">{{ $typePercentage }}%</span>
                                                </div>
                                                <div class="mt-3 space-y-2">
                                                    @foreach($subGroups as $subName => $subs)
                                                        @php
                                                            $subTotal = $subs->count();
                                                            $subCorrect = $subs->sum('correct_count');
                                                            $subWrong = $subs->sum('wrong_count');
                                                            $subPercentage = $subTotal > 0 ? round(($subCorrect / $subTotal) * 100) : 0;
                                                        @endphp

                                                        <div class="flex flex-col gap-2 rounded-xl border border-slate-200 bg-white p-3 sm:flex-row sm:items-center sm:justify-between">
                                                            <div>
                                                                <div class="text-sm font-medium text-gray-800">{{ $subName }}</div>
                                                            </div>
                                                            <div class="text-right">
                                                                <div class="text-sm font-semibold text-gray-900">{{ $subCorrect }} benar • {{ $subWrong }} salah</div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-8">
                            <div class="w-16 h-16 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                <i class="fas fa-clipboard-list text-gray-400 text-xl"></i>
                            </div>
                            <h4 class="text-lg font-medium text-gray-700 mb-2">Belum Ada Hasil Test</h4>
                            <p class="text-gray-500 text-sm">User ini belum mengerjakan test apapun</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-12">
                <div class="w-16 h-16 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-users text-gray-400 text-xl"></i>
                </div>
                <h4 class="text-lg font-medium text-gray-700 mb-2">Belum Ada Kandidat</h4>
                <p class="text-gray-500 text-sm">Tidak ada data kandidat yang ditemukan</p>
            </div>
            @endforelse
        </div>
    </div>

    <div class="flex flex-col gap-1 rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-600 sm:flex-row sm:items-center sm:justify-between">
        <span>Menampilkan {{ $users->count() }} kandidat pada halaman ini dari {{ number_format($users->total(), 0, ',', '.') }} kandidat.</span>
        <span>Export Excel di bagian atas mengikuti pencarian, tipe tes, dan tanggal yang dipilih.</span>
    </div>
    
    @if ($users->hasPages())
    <div class="mt-8 bg-white rounded-xl shadow-lg border border-gray-200 p-6">
        <div class="flex flex-col lg:flex-row justify-between items-center gap-6">
            <div class="text-sm text-gray-600">
                Menampilkan <span class="font-semibold">{{ $users->firstItem() ?? 0 }}</span> 
                sampai <span class="font-semibold">{{ $users->lastItem() ?? 0 }}</span> 
                dari <span class="font-semibold">{{ $users->total() }}</span> kandidat
            </div>
            
            <div class="flex flex-col sm:flex-row items-center gap-4 w-full sm:w-auto">
                {{-- Mobile: Simplified navigation --}}
                <div class="sm:hidden flex items-center justify-between w-full mb-4">
                    <div class="text-sm text-gray-600">
                        Halaman {{ $users->currentPage() }} dari {{ $users->lastPage() }}
                    </div>
                    <div class="flex items-center gap-2">
                        @if ($users->onFirstPage())
                            <span class="px-3 py-2 border border-gray-300 rounded-lg text-gray-300 cursor-not-allowed flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                            </span>
                        @else
                            <a href="{{ $users->previousPageUrl() }}" 
                            class="px-3 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                            </a>
                        @endif
                        
                        @if ($users->hasMorePages())
                            <a href="{{ $users->nextPageUrl() }}" 
                            class="px-3 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        @else
                            <span class="px-3 py-2 border border-gray-300 rounded-lg text-gray-300 cursor-not-allowed flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="hidden sm:flex items-center gap-2">
                    {{-- Previous Button --}}
                    @if ($users->onFirstPage())
                        <span class="px-4 py-2 border border-gray-300 rounded-lg text-gray-300 cursor-not-allowed">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </span>
                    @else
                        <a href="{{ $users->previousPageUrl() }}" 
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </a>
                    @endif
                    @php
                        $current = $users->currentPage();
                        $last = $users->lastPage();
                        $start = max(1, $current - 2);
                        $end = min($last, $current + 2);
                    @endphp
                    
                    @if($start > 1)
                        <a href="{{ $users->url(1) }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
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
                            <a href="{{ $users->url($page) }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                                {{ $page }}
                            </a>
                        @endif
                    @endfor
                    
                    @if($end < $last)
                        @if($end < $last - 1)
                            <span class="px-2 py-2 text-gray-400">...</span>
                        @endif
                        <a href="{{ $users->url($last) }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                            {{ $last }}
                        </a>
                    @endif
                    @if ($users->hasMorePages())
                        <a href="{{ $users->nextPageUrl() }}" 
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    @else
                        <span class="px-4 py-2 border border-gray-300 rounded-lg text-gray-300 cursor-not-allowed">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </span>
                    @endif
                </div>
                <div class="flex items-center justify-center sm:justify-end gap-2 text-sm w-full sm:w-auto">
                    <span class="text-gray-600 whitespace-nowrap">Lompat ke:</span>
                    <form method="GET" action="{{ request()->url() }}" class="flex items-center gap-2">
                        @foreach(request()->all() as $key => $value)
                            @if($key !== 'page')
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endif
                        @endforeach
                        <input type="number" name="page" min="1" max="{{ $users->lastPage() }}" 
                            value="{{ $users->currentPage() }}"
                            class="w-20 px-3 py-2 border border-gray-300 rounded-lg text-center focus:ring-2 focus:ring-red-500 focus:border-red-500">
                        <button type="submit" class="px-3 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors whitespace-nowrap">
                            Go
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
    function toggleAccordion(id) {
        const content = document.getElementById(id);
        const icon = document.getElementById('icon-' + id);
        const trigger = document.querySelector(`[aria-controls="${id}"]`);
        content.classList.toggle('hidden');
        const isExpanded = !content.classList.contains('hidden');
        if (trigger) trigger.setAttribute('aria-expanded', String(isExpanded));
        if (content.classList.contains('hidden')) {
            icon.style.transform = 'rotate(0deg)';
            icon.classList.remove('text-rose-600');
            icon.classList.add('text-slate-400');
        } else {
            icon.style.transform = 'rotate(180deg)';
            icon.classList.remove('text-slate-400');
            icon.classList.add('text-rose-600');
        }
    }
    document.addEventListener("DOMContentLoaded", function() {
        const filterBtn = document.getElementById('filterBtn');
        const filterOptions = document.getElementById('filterOptions');
        const filterForm = document.getElementById('filterForm');

        if (filterBtn && filterOptions) {
            filterBtn.addEventListener('click', function() {
                filterOptions.classList.toggle('hidden');
                filterBtn.setAttribute('aria-expanded', String(!filterOptions.classList.contains('hidden')));
            });
        }

        const perPageSelect = filterForm ? filterForm.querySelector('select[name="per_page"]') : null;
        if (perPageSelect) {
            perPageSelect.addEventListener('change', function() {
                filterForm.submit();
            });
        }
    });

    function resetFilters() {
        const form = document.getElementById('filterForm');
        if (!form) return;
        const inputs = form.querySelectorAll('input, select');
        inputs.forEach(input => {
            if (input.type === 'text' || input.type === 'number' || input.type === 'date') {
                input.value = '';
            } else if (input.tagName === 'SELECT') {
                input.selectedIndex = 0;
            }
        });
        form.submit();
    }
</script>

<style>
    .accordion-item { border-bottom: 1px solid #e5e7eb; }
    .accordion-header:hover { background-color: #f9fafb; }
    .accordion-icon { transition: transform 0.3s ease; }
    .accordion-content { animation: slideDown 0.2s ease-out; }
    @keyframes slideDown { from { opacity: 0; transform: translateY(-5px); } to { opacity: 1; transform: translateY(0); } }
</style>
@endsection
