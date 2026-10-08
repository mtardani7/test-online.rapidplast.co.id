@extends('layouts.sidebar')

@section('content')
<div class="p-6">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Laporan Hasil Test</h1>
                <p class="text-gray-600 mt-1">Ringkasan hasil test semua kandidat</p>
            </div>
        </div>
    </div>
    <form method="GET" action="{{ route('admin.total-test.index') }}" id="filterForm" class="mb-4">
        <div class="bg-white rounded-2xl shadow-md p-5 mb-6">
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

                <div class="flex items-center space-x-3">
                    <button type="button" id="filterBtn" class="px-4 py-3 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-all flex items-center">
                        <i class="fas fa-filter mr-2"></i> Filter
                    </button>
                    <button type="submit" class="px-4 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl hover:from-red-700 hover:to-red-800 transition-all flex items-center">
                        <i class="fas fa-search mr-2"></i> Cari
                    </button>
                    <a href="{{ route('admin.total-test.index') }}" class="px-4 py-3 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-all flex items-center">
                        <i class="fas fa-redo mr-2"></i> Reset
                    </a>
                </div>
            </div>

            <div id="filterOptions" class="{{ request()->hasAny(['q','test_type','per_page','start_date','end_date']) ? '' : 'hidden' }} mt-4 pt-4 border-t border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
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

                    <div class="md:col-span-4 flex justify-end space-x-2">
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

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
        <div class="px-6 py-4 border-b bg-gray-50">
            <h2 class="text-lg font-semibold text-gray-900">Daftar Kandidat</h2>
            <p class="text-gray-500 text-sm mt-1">Klik nama kandidat untuk melihat detail</p>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($users as $user)
            <div class="accordion-item">
                <div class="accordion-header p-5 hover:bg-gray-50 cursor-pointer transition-colors duration-200" 
                     onclick="toggleAccordion('user-{{ $user->id }}')">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-r from-red-500 to-red-600 flex items-center justify-center text-white font-semibold">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">{{ $user->name }}</h3>
                                <p class="text-gray-500 text-sm">{{ $user->email }}</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            @if($user->iqData['test_count'] > 0)
                            <div class="text-center">
                                <div class="flex items-center space-x-2">
                                    <span class="text-lg font-bold text-gray-900">{{ $user->iqData['average_iq'] }}</span>
                                    <span class="px-2 py-1 rounded text-xs font-semibold text-white bg-gradient-to-r {{ App\Http\Controllers\Admin\TotalTestController::getIQCategoryColor($user->iqData['category']) }}">
                                        {{ $user->iqData['category'] }}
                                    </span>
                                </div>
                                <p class="text-gray-500 text-xs mt-1">IQ TPA</p>
                            </div>
                            @endif
                            <div class="text-center">
                                <div class="text-lg font-bold text-red-600">
                                    {{ $user->attempts->count() }}
                                </div>
                                <p class="text-gray-500 text-xs mt-1">Test</p>
                            </div>
                            <div class="text-gray-400 ml-2">
                                <i class="fas fa-chevron-down accordion-icon transition-transform duration-300" id="icon-user-{{ $user->id }}"></i>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4 mt-3 text-sm">
                        <div class="flex items-center">
                            <i class="fas fa-calendar-alt text-gray-400 mr-1"></i>
                            <span class="text-gray-600">Bergabung {{ $user->created_at->format('d M Y') }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-chart-bar text-gray-400 mr-1"></i>
                            <span class="text-gray-600">
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
                </div>
                <div id="user-{{ $user->id }}" class="accordion-content hidden">
                    <div class="p-5 bg-gray-50 border-t">
                        @if($user->iqData['test_count'] > 0)
                        <div class="mb-6">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="font-semibold text-gray-900">Analisis IQ TPA</h3>
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
                                    $attempt = $user->attempts()
                                        ->where('test_id', $test->id)
                                        ->with(['typeAttempts.subAnswers.subTest', 'typeAttempts.typeTest'])
                                        ->latest()
                                        ->first();
                                @endphp
                                
                                @if($attempt)
                                <div class="mb-4 bg-white rounded-lg border border-gray-200 overflow-hidden">
                                    <div class="px-4 py-3 bg-gray-50 border-b">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <h4 class="font-medium text-gray-900">{{ $test->name }}</h4>
                                                <div class="flex items-center space-x-3 mt-1">
                                                    <span class="text-sm text-gray-600">
                                                        <i class="fas fa-star text-yellow-500 mr-1"></i>
                                                        Score: {{ $attempt->score ?? '0' }}
                                                    </span>
                                                    <span class="text-sm text-gray-600">
                                                        <i class="fas fa-clock text-gray-400 mr-1"></i>
                                                        {{ $attempt->created_at->format('d M Y') }}
                                                    </span>
                                                </div>
                                            </div>
                                            @php
                                                $testIQ = collect($user->iqData['results'])
                                                    ->firstWhere('attempt_id', $attempt->id);
                                            @endphp
                                            @if($testIQ)
                                            <div class="text-right">
                                                <div class="text-sm font-semibold text-gray-900">{{ $testIQ['iq'] }}</div>
                                                <div class="text-xs text-gray-500">Skor IQ</div>
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
                                            
                                            <div class="px-4 py-3 hover:bg-gray-50">
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

                                                        <div class="flex items-center justify-between bg-white border rounded-md p-3">
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

    <div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-3">
        <div class="text-gray-600 text-sm">
            Menampilkan {{ $users->count() }} dari {{ $users->total() }} kandidat
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('admin.total-test.export') }}?{{ http_build_query(request()->except(['page'])) }}"
            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium text-sm flex items-center">
                <i class="fas fa-download mr-2 text-xs"></i> Export Excel
            </a>
        </div>
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
        content.classList.toggle('hidden');
        if (content.classList.contains('hidden')) {
            icon.style.transform = 'rotate(0deg)';
            icon.classList.remove('text-red-600');
            icon.classList.add('text-gray-400');
        } else {
            icon.style.transform = 'rotate(180deg)';
            icon.classList.remove('text-gray-400');
            icon.classList.add('text-red-600');
        }
    }
    document.addEventListener("DOMContentLoaded", function() {
        const filterBtn = document.getElementById('filterBtn');
        const filterOptions = document.getElementById('filterOptions');
        const filterForm = document.getElementById('filterForm');

        if (filterBtn && filterOptions) {
            filterBtn.addEventListener('click', function() {
                filterOptions.classList.toggle('hidden');
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
