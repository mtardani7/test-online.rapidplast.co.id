<?php

namespace App\Http\Controllers;

use App\Models\Attempt;
use App\Models\AttemptTypeTest;
use App\Models\AttemptSubTest;
use App\Models\AttemptAnswer;
use App\Models\User;
use App\Models\Tests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttemptController extends Controller
{
    public function index(Request $request)
    {
        $query = Attempt::with([
            'user',
            'typeTests.subAnswers.subTest',
            'typeTests.typeTest',
        ]);
        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->has('test_id') && $request->test_id) {
            $query->where('test_id', $request->test_id);
        }
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        if ($request->has('score_min') && $request->score_min) {
            $query->where('score', '>=', $request->score_min);
        }
        
        if ($request->has('score_max') && $request->score_max) {
            $query->where('score', '<=', $request->score_max);
        }
        $allowedSorts = ['created_at', 'score', 'total_correct', 'total_question', 'id'];
        $sortBy = $request->get('sort_by', 'created_at');
        if (!is_string($sortBy) || !in_array($sortBy, $allowedSorts, true)) {
            $sortBy = 'created_at';
        }

        $sortOrderInput = $request->get('sort_order', 'desc');
        $sortOrder = is_string($sortOrderInput) ? strtolower($sortOrderInput) : 'desc';
        if (!in_array($sortOrder, ['asc', 'desc'], true)) {
            $sortOrder = 'desc';
        }

        $query->orderBy($sortBy, $sortOrder);
        $perPageInput = $request->get('per_page', 10);
        $perPage = is_int($perPageInput)
            ? $perPageInput
            : (is_string($perPageInput) && ctype_digit($perPageInput) ? (int) $perPageInput : 10);
        if (!in_array($perPage, [5, 10, 20, 50], true)) {
            $perPage = 10;
        }

        $attempts = $query->paginate($perPage)->withQueryString();
        $users = User::orderBy('name')->get(['id', 'name', 'email']);
        $tests = Tests::orderBy('name')->get(['id', 'name']);

        return view('admin.attempts.index', compact('attempts', 'users', 'tests'));
    }

    public function destroy($id)
    {
        $attempt = Attempt::findOrFail($id);

        DB::transaction(function () use ($attempt) {
            AttemptSubTest::whereIn(
                'attempt_type_test_id',
                $attempt->typeTests->pluck('id')
            )->delete();
            $attempt->typeTests()->delete();
            $attempt->answers()->delete();
            $attempt->delete();
        });

        return redirect()
            ->route('admin.attempts.index')
            ->with('success', 'Attempt berhasil dihapus.');
    }

    public function destroyTypeTest($id)
    {
        $typeTest = AttemptTypeTest::findOrFail($id);

        DB::transaction(function () use ($typeTest) {
            $typeTest->subAnswers()->delete();
            $typeTest->delete();
        });

        return back()->with('success', 'Attempt Type Test berhasil dihapus.');
    }

    public function destroySubTest($id)
    {
        AttemptSubTest::findOrFail($id)->delete();

        return back()->with('success', 'Attempt Sub Test berhasil dihapus.');
    }
}
