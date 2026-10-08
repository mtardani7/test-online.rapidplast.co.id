<?php

namespace App\Http\Controllers;

use App\Models\SubTests;
use App\Models\Tests;
use App\Models\TypeTests;
use App\Models\SubtestInstructionRange;
use Illuminate\Http\Request;
use App\Models\Questions;

class SubtestInstructionRangeController extends Controller
{
    private function assertHierarchy(Tests $test, TypeTests $typeTest, SubTests $subTest): void
    {
        abort_unless(
            (int) $typeTest->tests_id === (int) $test->id,
            404
        );

        abort_unless(
            (int) $subTest->type_tests_id === (int) $typeTest->id,
            404
        );
    }

    public function index(Tests $test, TypeTests $typeTest, SubTests $subTest)
    {
        $this->assertHierarchy($test, $typeTest, $subTest);

        $ranges = SubtestInstructionRange::where('sub_test_id', $subTest->id)->orderBy('order_number')->get();

        $questions = Questions::where('sub_tests_id', $subTest->id)->orderBy('id', 'asc')->get();

        $numberedQuestions = $questions->map(function ($question, $index) {
            $question->display_number = $index + 1;
            return $question;
        });

        $usedQuestionNumbers = $this->getUsedQuestionNumbers($subTest->id);

        return view(
            'admin.range.index',
            compact('test', 'typeTest', 'subTest', 'ranges', 'numberedQuestions', 'usedQuestionNumbers')
        );
    }

    private function getUsedQuestionNumbers($subTestId, $excludeRangeId = null)
    {
        $ranges = SubtestInstructionRange::where('sub_test_id', $subTestId);
        
        if ($excludeRangeId) {
            $ranges->where('id', '!=', $excludeRangeId);
        }
        
        $ranges = $ranges->get();
        
        $usedNumbers = [];
        
        foreach ($ranges as $range) {
            $startQuestion = Questions::find($range->start_number);
            $endQuestion = Questions::find($range->end_number);
            
            if ($startQuestion && $endQuestion) {
                $questions = Questions::where('sub_tests_id', $subTestId)
                    ->orderBy('id', 'asc')
                    ->get();
                
                $startIndex = $questions->search(function($item) use ($startQuestion) {
                    return $item->id == $startQuestion->id;
                });
                
                $endIndex = $questions->search(function($item) use ($endQuestion) {
                    return $item->id == $endQuestion->id;
                });
                
                if ($startIndex !== false && $endIndex !== false) {
                    for ($i = $startIndex; $i <= $endIndex; $i++) {
                        $usedNumbers[] = $i + 1; 
                    }
                }
            }
        }
        
        return array_unique($usedNumbers);
    }

    public function edit(Tests $test, TypeTests $typeTest, SubTests $subTest, SubtestInstructionRange $range)
    {
        $this->assertHierarchy($test, $typeTest, $subTest);
        $range = $subTest->instructionRanges()->whereKey($range->id)->firstOrFail();

        $questions = Questions::where('sub_tests_id', $subTest->id)->orderBy('id', 'asc')->get();

        $numberedQuestions = $questions->map(function ($question, $index) {
            $question->display_number = $index + 1;
            return $question;
        });
        $usedQuestionNumbers = $this->getUsedQuestionNumbers($subTest->id, $range->id);

        return view('admin.range.edit', compact(
            'test', 'typeTest', 'subTest', 'range', 'numberedQuestions', 'usedQuestionNumbers'
        ));
    }

    public function store(Request $request, Tests $test, TypeTests $typeTest, SubTests $subTest)
    {
        $this->assertHierarchy($test, $typeTest, $subTest);

        $request->validate([
            'start_number' => 'required|integer|min:1',
            'end_number'   => 'required|integer|min:1',
            'order_number' => 'required|integer|min:1',
        ]);

        $questions = Questions::where('sub_tests_id', $subTest->id)
            ->orderBy('id', 'asc')
            ->get();

        if ($questions->count() == 0) {
            return back()->withErrors(['start_number' => 'Tidak ada soal untuk subtest ini']);
        }

        if (
            $request->start_number > $questions->count() ||
            $request->end_number > $questions->count()
        ) {
            return back()->withErrors([
                'start_number' => 'Nomor soal melebihi jumlah soal yang tersedia'
            ]);
        }

        if ($request->start_number > $request->end_number) {
            return back()->withErrors([
                'start_number' => 'Nomor awal harus lebih kecil atau sama dengan nomor akhir'
            ]);
        }
        $usedQuestionNumbers = $this->getUsedQuestionNumbers($subTest->id);
        
        for ($i = $request->start_number; $i <= $request->end_number; $i++) {
            if (in_array($i, $usedQuestionNumbers)) {
                return back()->withErrors([
                    'start_number' => "Soal nomor $i sudah digunakan dalam range lain"
                ]);
            }
        }

        $startQuestion = $questions->values()->get($request->start_number - 1);
        $endQuestion = $questions->values()->get($request->end_number - 1);

        if (!$startQuestion || !$endQuestion) {
            return back()->withErrors(['start_number' => 'Nomor soal tidak valid']);
        }

        $existingOrder = SubtestInstructionRange::where('sub_test_id', $subTest->id)->where('order_number', $request->order_number)->exists();

        if ($existingOrder) {
            return back()->withErrors([
                'order_number' => 'Order number sudah digunakan'
            ]);
        }

        try {
            SubtestInstructionRange::create([
                'sub_test_id'  => $subTest->id,
                'start_number' => $startQuestion->id,
                'end_number'   => $endQuestion->id,
                'order_number' => $request->order_number,
                'is_active'    => $request->boolean('is_active'),
            ]);

            return redirect()
                ->route('sub-test.ranges.index', [$test->slug, $typeTest->slug, $subTest->slug])
                ->with('success', 'Range instruksi berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->withErrors([
                'error' => 'Gagal menyimpan data: ' . $e->getMessage()
            ]);
        }
    }

    public function update(
        Request $request,
        Tests $test,
        TypeTests $typeTest,
        SubTests $subTest,
        SubtestInstructionRange $range
    ) {
        $this->assertHierarchy($test, $typeTest, $subTest);
        $range = $subTest->instructionRanges()->whereKey($range->id)->firstOrFail();

        $request->validate([
            'start_number' => 'required|integer|min:1',
            'end_number'   => 'required|integer|min:1',
            'order_number' => 'required|integer|min:1',
        ]);

        $questions = Questions::where('sub_tests_id', $subTest->id)
            ->orderBy('id', 'asc')
            ->get();

        if ($questions->count() == 0) {
            return back()->withErrors(['start_number' => 'Tidak ada soal untuk subtest ini']);
        }

        if (
            $request->start_number > $questions->count() ||
            $request->end_number > $questions->count()
        ) {
            return back()->withErrors([
                'start_number' => 'Nomor soal melebihi jumlah soal yang tersedia'
            ]);
        }

        if ($request->start_number > $request->end_number) {
            return back()->withErrors([
                'start_number' => 'Nomor awal harus lebih kecil atau sama dengan nomor akhir'
            ]);
        }
        $usedQuestionNumbers = $this->getUsedQuestionNumbers($subTest->id, $range->id);
        
        for ($i = $request->start_number; $i <= $request->end_number; $i++) {
            if (in_array($i, $usedQuestionNumbers)) {
                return back()->withErrors([
                    'start_number' => "Soal nomor $i sudah digunakan dalam range lain"
                ]);
            }
        }

        $startQuestion = $questions->values()->get($request->start_number - 1);
        $endQuestion = $questions->values()->get($request->end_number - 1);

        if (!$startQuestion || !$endQuestion) {
            return back()->withErrors(['start_number' => 'Nomor soal tidak valid']);
        }

        $existingOrder = SubtestInstructionRange::where('sub_test_id', $subTest->id)->where('order_number', $request->order_number)->where('id', '!=', $range->id)->exists();

        if ($existingOrder) {
            return back()->withErrors([
                'order_number' => 'Order number sudah digunakan'
            ]);
        }

        try {
            $range->update([
                'start_number' => $startQuestion->id,
                'end_number'   => $endQuestion->id,
                'order_number' => $request->order_number,
                'is_active'    => $request->boolean('is_active'),
            ]);

            return redirect()->route('sub-test.ranges.index', [$test->slug, $typeTest->slug, $subTest->slug])->with('success', 'Range instruksi berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->withErrors([
                'error' => 'Gagal memperbarui data: ' . $e->getMessage()
            ]);
        }
    }

    public function destroy(Tests $test, TypeTests $typeTest, SubTests $subTest, SubtestInstructionRange $range)
    {
        $this->assertHierarchy($test, $typeTest, $subTest);
        $range = $subTest->instructionRanges()->whereKey($range->id)->firstOrFail();

        try {
            $range->delete();
            return back()->with('success', 'Range instruksi berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->withErrors([
                'error' => 'Gagal menghapus data: ' . $e->getMessage()
            ]);
        }
    }

    public function preview(Tests $test, TypeTests $typeTest, SubTests $subTest)
    {
        $this->assertHierarchy($test, $typeTest, $subTest);

        $ranges = SubtestInstructionRange::where('sub_test_id', $subTest->id)->orderBy('order_number')->get();
        $questions = Questions::where('sub_tests_id', $subTest->id)->orderBy('id', 'asc')->get();
        $questionsWithNumber = $questions->map(function ($question, $index) {
            $question->display_number = $index + 1;
            return $question;
        });
        $questionIdToNumber = [];
        foreach ($questionsWithNumber as $index => $question) {
            $questionIdToNumber[$question->id] = $index + 1;
        }

        return view(
            'admin.range.preview',
            compact('test', 'typeTest', 'subTest', 'ranges', 'questionsWithNumber', 'questionIdToNumber')
        );
    }
}
