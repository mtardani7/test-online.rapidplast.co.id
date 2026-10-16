<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Tests;
use App\Exports\TotalTestExport;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class TotalTestController extends Controller
{
    public function index(Request $request)
    {
        $query = User::whereDoesntHave('roles', function ($q) {
            $q->whereIn('name', ['admin', 'super_admin']);
        });
    
        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->q}%")
                  ->orWhere('email', 'like', "%{$request->q}%");
            });
        }
    
        if ($request->filled('test_type')) {
            $query->whereHas('attempts.tests', function ($q) use ($request) {
                $q->where('type', $request->test_type);
            });
        }
    
        if ($request->filled('start_date') || $request->filled('end_date')) {
            $startDate = $request->filled('start_date')
                ? Carbon::parse($request->start_date)->startOfDay()
                : null;
    
            $endDate = $request->filled('end_date')
                ? Carbon::parse($request->end_date)->endOfDay()
                : null;
    
            $query->whereHas('attempts', function ($q) use ($startDate, $endDate) {
                if ($startDate && $endDate) {
                    $q->whereBetween('created_at', [$startDate, $endDate]);
                } elseif ($startDate) {
                    $q->where('created_at', '>=', $startDate);
                } elseif ($endDate) {
                    $q->where('created_at', '<=', $endDate);
                }
            });
        }
    
        $perPage = $request->input('per_page', 10);
        
        $allowedPerPage = [5, 10, 25, 50, 100];
        if (!in_array((int)$perPage, $allowedPerPage)) {
            $perPage = 10;
        }
    
        $users = $query
            ->with([
                'attempts' => function ($q) use ($request) {
                    if ($request->filled('start_date') || $request->filled('end_date')) {
                        $start = $request->filled('start_date')
                            ? Carbon::parse($request->start_date)->startOfDay()
                            : Carbon::minValue();
    
                        $end = $request->filled('end_date')
                            ? Carbon::parse($request->end_date)->endOfDay()
                            : Carbon::now();
    
                        $q->whereBetween('created_at', [$start, $end]);
                    }
    
                    $q->with([
                        'tests',
                        'typeAttempts.subAnswers.subTest',
                        'typeAttempts.typeTest',
                    ])->latest();
                }
            ])
            ->withMax('attempts', 'created_at')
            ->orderByDesc('attempts_max_created_at')
            ->paginate($perPage)
            ->withQueryString();
    
        foreach ($users as $user) {
            $user->iqData = $this->calculateUserIQFromAttempts(
                $user,
                $request->start_date ?? null,
                $request->end_date ?? null
            );
        }
    
        return view('admin.total.index', [
            'users' => $users,
            'tests' => Tests::all(),
        ]);
    }

    public function calculateUserIQFromAttempts($user, $startDate = null, $endDate = null): array
    {
        $startDate = $startDate ? Carbon::parse($startDate)->startOfDay() : Carbon::today()->startOfDay();
        $endDate   = $endDate   ? Carbon::parse($endDate)->endOfDay()   : Carbon::today()->endOfDay();

        $filteredAttempts = $user->attempts->filter(function ($attempt) use ($startDate, $endDate) {
            return $attempt->created_at->between($startDate, $endDate);
        });

        if ($filteredAttempts->isEmpty()) {
            return [
                'results' => [],
                'average_iq' => 0,
                'category' => '-',
                'test_count' => 0,
            ];
        }

        $results = [];

        foreach ($filteredAttempts as $attempt) {
            // Ringkasan IQ hanya menggunakan hasil TPA.
            $testType = strtoupper(trim($attempt->tests->type ?? ''));
            $testLabel = ($attempt->tests->name ?? '') . ' ' . ($attempt->tests->slug ?? '');
            $isTpa = $testType !== ''
                ? $testType === 'TPA'
                : (bool) preg_match('/\btpa\b|potensi[\s-]+akademik/i', $testLabel);

            if (!$isTpa) continue;
            if (!$attempt->iq_score) continue;

            $results[] = [
                'attempt_id' => $attempt->id,
                'test_id' => $attempt->test_id,
                'test_name' => $attempt->tests->name ?? '-',
                'test_type' => 'TPA',
                'score' => (int) $attempt->score,
                'iq' => $attempt->iq_score,
                'category' => $attempt->iq_category,
                'date' => $attempt->created_at,
            ];
        }

        $averageIQ = $results ? round(collect($results)->avg('iq')) : 0;
        $dominantCategory = $results ? collect($results)->countBy('category')->sortDesc()->keys()->first() : '-';

        return [
            'results' => $results,
            'average_iq' => $averageIQ,
            'category' => $dominantCategory,
            'test_count' => count($results),
        ];
    }

    public static function getIQCategoryColor($category)
    {
        return match ($category) {
            'Very Superior' => 'from-purple-600 to-purple-700',
            'Superior' => 'from-blue-600 to-blue-700',
            'High Average' => 'from-green-600 to-green-700',
            'Average' => 'from-yellow-500 to-yellow-600',
            'Low Average' => 'from-orange-500 to-orange-600',
            'Low' => 'from-red-500 to-red-600',
            'Borderline' => 'from-red-700 to-red-800',
            default => 'from-gray-500 to-gray-600',
        };
    }

    public function exportExcel(Request $request)
    {
        $rules = [
            'q' => ['nullable', 'string', 'max:255'],
            'test_type' => ['nullable', 'string', 'max:100'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
        ];
        if ($request->filled('start_date')) {
            $rules['end_date'][] = 'after_or_equal:start_date';
        }
        $request->validate($rules);

        $startDate = $request->filled('start_date')
            ? Carbon::parse($request->start_date)->startOfDay()
            : null;
        $endDate = $request->filled('end_date')
            ? Carbon::parse($request->end_date)->endOfDay()
            : null;

        $query = User::whereDoesntHave('roles', function ($q) {
            $q->whereIn('name', ['admin', 'super_admin']);
        });

        if ($startDate || $endDate) {
            $query->whereHas('attempts', function ($q) use ($startDate, $endDate) {
                if ($startDate && $endDate) {
                    $q->whereBetween('created_at', [$startDate, $endDate]);
                } elseif ($startDate) {
                    $q->where('created_at', '>=', $startDate);
                } else {
                    $q->where('created_at', '<=', $endDate);
                }
            });
        }
    
        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->q}%")
                  ->orWhere('email', 'like', "%{$request->q}%");
            });
        }
    
        if ($request->filled('test_type')) {
            $query->whereHas('attempts.tests', function ($q) use ($request) {
                $q->where('type', $request->test_type);
            });
        }
    
        $users = $query->with([
            'attempts' => function ($q) use ($startDate, $endDate) {
                if ($startDate || $endDate) {
                    $q->whereBetween('created_at', [
                        $startDate ?? Carbon::minValue(),
                        $endDate ?? Carbon::now(),
                    ]);
                }

                $q->with('tests')->latest();
            },
        ])->get();
    
        $fileName = 'Laporan_Hasil_Tes_' .
            ($startDate?->format('Y-m-d') ?? 'semua') .
            '_sd_' .
            ($endDate?->format('Y-m-d') ?? 'semua') .
            '.xlsx';
    
        return Excel::download(
            new TotalTestExport($users),
            $fileName
        );
    }
}
