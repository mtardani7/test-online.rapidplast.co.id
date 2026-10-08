<?php

namespace App\Http\Controllers;

use App\Models\Tests;
use Illuminate\Support\Facades\Auth;
use App\Models\TypeTests;
use App\Models\SubTests;
use App\Models\Questions;
use App\Models\AttemptAnswer;
use App\Models\QuestionChoices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Models\AttemptTypeTest;
use App\Models\AttemptSubTest;
use App\Models\Attempt;
use App\Models\SubtestInstructionRange;
use App\Models\DetailUser;
use App\Services\AttemptService;
use App\Services\RangeService;
use App\Services\ShuffleService;
use App\Services\QuestionNavigationService;

class UserTestController extends Controller
{
    private $attemptService;
    private $rangeService;
    private $shuffleService;
    private $questionNavigationService;

    private function assertTestHierarchy(Tests $test, TypeTests $typeTest, SubTests $subTest): void
    {
        abort_unless((int) $typeTest->tests_id === (int) $test->id, 404);
        abort_unless((int) $subTest->type_tests_id === (int) $typeTest->id, 404);
    }

    /**
     * Validate the existing browser payload shape: answers[question_id] = option_id.
     */
    private function validatedAnswers(Request $request, SubTests $subTest, $questions): array
    {
        $validated = $request->validate([
            'answers' => ['sometimes', 'array', 'max:' . $questions->count()],
            'answers.*' => ['nullable', 'integer', 'min:1'],
        ]);

        $answers = $validated['answers'] ?? [];
        $questionsById = $questions->keyBy('id');

        foreach (array_keys($answers) as $questionId) {
            $parsedQuestionId = filter_var($questionId, FILTER_VALIDATE_INT);
            if ($parsedQuestionId === false || !$questionsById->has((int) $parsedQuestionId)) {
                throw ValidationException::withMessages([
                    'answers' => 'Ada pertanyaan yang tidak termasuk dalam subtest ini.',
                ]);
            }
        }

        $submittedOptionIds = collect($answers)->filter(fn ($answer) => $answer !== null)->unique()->values();
        if ($submittedOptionIds->isNotEmpty()) {
            $questionForeignKey = QuestionChoices::questionForeignKey();
            $optionsById = QuestionChoices::query()
                ->whereIn('id', $submittedOptionIds)
                ->whereIn($questionForeignKey, array_keys($answers))
                ->pluck($questionForeignKey, 'id');

            foreach ($answers as $questionId => $optionId) {
                if ($optionId === null) {
                    continue;
                }

                if ((int) ($optionsById->get((int) $optionId) ?? 0) !== (int) $questionId) {
                    throw ValidationException::withMessages([
                        "answers.{$questionId}" => 'Pilihan jawaban tidak dimiliki oleh pertanyaan tersebut.',
                    ]);
                }
            }
        }

        return $answers;
    }

    public function __construct(
        AttemptService $attemptService,
        RangeService $rangeService,
        ShuffleService $shuffleService,
        QuestionNavigationService $questionNavigationService
    ) {
        $this->attemptService = $attemptService;
        $this->rangeService = $rangeService;
        $this->shuffleService = $shuffleService;
        $this->questionNavigationService = $questionNavigationService;
    }

    private function checkRememberToken()
    {
        if (empty(Auth::user()->email_verified_at)) {
            return redirect()->route('user.tests')->with('error', 'Session expired. Please login again.');
        }

        return null;
    }

    private function isProfileComplete($userId)
    {
        $profile = DetailUser::where('user_id', $userId)->first();
        return $profile && !empty($profile->no_id);
    }

    public function tests()
    {
        $tests = Tests::query()
            ->where('is_active', 1)
            ->with(['subtests', 'typeTests.subTests'])
            ->get();
        $userId = Auth::id();

        $attempts = Attempt::query()
            ->where('user_id', $userId)
            ->whereIn('test_id', $tests->pluck('id'))
            ->get(['id', 'test_id'])
            ->keyBy('test_id');

        $attemptTypeTestsByAttempt = AttemptTypeTest::query()
            ->whereIn('attempt_id', $attempts->pluck('id'))
            ->get(['id', 'attempt_id', 'type_test_id'])
            ->groupBy('attempt_id')
            ->map(fn ($typeAttempts) => $typeAttempts->keyBy('type_test_id'));

        $attemptTypeTestIds = $attemptTypeTestsByAttempt->flatten(1)->pluck('id');
        $completedSubTestCounts = AttemptSubTest::query()
            ->whereIn('attempt_type_test_id', $attemptTypeTestIds)
            ->where('is_finished', 1)
            ->get(['attempt_type_test_id'])
            ->groupBy('attempt_type_test_id')
            ->map(fn ($subAttempts) => $subAttempts->count());
        
        foreach ($tests as $test) {
            $test->total_duration = $test->subtests->sum('duration');
            $attempt = $attempts->get($test->id);
            
            if ($attempt) {
                $completedTypeTests = 0;
                $totalTypeTests = $test->typeTests->count();
                $typeAttempts = $attemptTypeTestsByAttempt->get($attempt->id, collect());
                
                foreach ($test->typeTests as $typeTest) {
                    $typeAttempt = $typeAttempts->get($typeTest->id);
                    
                    if ($typeAttempt) {
                        $completedSubTests = $completedSubTestCounts->get($typeAttempt->id, 0);
                        
                        $totalSubTests = $typeTest->subTests->count();
                        
                        if ($totalSubTests > 0 && $completedSubTests >= $totalSubTests) {
                            $completedTypeTests++;
                        }
                    }
                }
                $test->all_type_tests_completed = ($completedTypeTests >= $totalTypeTests) && ($totalTypeTests > 0);
            } else {
                $test->all_type_tests_completed = false;
            }
        }
        
        $totalDurationAllTests = $tests->sum(fn (Tests $test) => $test->subtests->sum('duration'));
        return view('user.tests', compact('tests', 'totalDurationAllTests'));
    }

    public function startTest(Tests $test)
    {
        $tokenCheck = $this->checkRememberToken();
        if ($tokenCheck) {
            return $tokenCheck;
        }

        if (!$this->isProfileComplete(Auth::id())) {
            return redirect()->route('profile')->with('warning', 'Lengkapi profil Anda sebelum memulai test.');
        }

        $this->attemptService->initializeTestAttempt($test);

        return redirect()->route('user.type-tests', $test->slug);
    }

    public function typeTests(Tests $test)
    {
        $tokenCheck = $this->checkRememberToken();
        if ($tokenCheck) {
            return $tokenCheck;
        }

        $userId = Auth::id();
        if (!$this->isProfileComplete($userId)) {
            return redirect()->route('profile')->with('warning', 'Lengkapi profil Anda sebelum memasuki test.');
        }

        $attempt = $this->attemptService->getOrCreateAttempt($test);

        foreach ($test->typeTests as $typeTest) {
            $this->attemptService->getOrCreateTypeAttempt($attempt, $typeTest);
        }

        $typeTests = $test->typeTests;
        $typeTests->each(function ($typeTest) use ($attempt) {
            $typeAttempt = AttemptTypeTest::where('attempt_id', $attempt->id)
                ->where('type_test_id', $typeTest->id)
                ->first();
            
            if ($typeAttempt) {
                $completedSubTests = AttemptSubTest::where('attempt_type_test_id', $typeAttempt->id)
                    ->where('is_finished', 1)
                    ->count();
                
                $totalSubTests = $typeTest->subTests()->count();
                
                $typeTest->all_subtests_completed = ($completedSubTests >= $totalSubTests) && ($totalSubTests > 0);
            } else {
                $typeTest->all_subtests_completed = false;
            }
        });

        return view('user.type-tests', [
            'test' => $test,
            'typeTests' => $typeTests,
            'attempt' => $attempt
        ]);
    }

    public function subTests(Tests $test, TypeTests $typeTest)
    {
        $tokenCheck = $this->checkRememberToken();
        if ($tokenCheck) {
            return $tokenCheck;
        }
        abort_unless((int) $typeTest->tests_id === (int) $test->id, 404);
        $attempt = $this->attemptService->getOrCreateAttempt($test);
        $typeAttempt = $this->attemptService->getOrCreateTypeAttempt($attempt, $typeTest);

        foreach ($typeTest->subTests as $subTest) {
            $this->attemptService->getOrCreateSubAttempt($typeAttempt, $subTest);
        }

        $subTests = $typeTest->subTests;
        $subTestIds = $subTests->pluck('id');

        $questions = Questions::whereIn('sub_tests_id', $subTestIds)
            ->select('id', 'sub_tests_id')
            ->get()
            ->groupBy('sub_tests_id');
        $allQuestionIds = $questions->flatten()->pluck('id');

        $answers = AttemptAnswer::where('attempt_id', $attempt->id)->whereIn('question_id', $allQuestionIds)->get()->groupBy('question_id');
        $subAttempts = AttemptSubTest::where('attempt_type_test_id', $typeAttempt->id)->get()->keyBy('sub_test_id');
        
        $completedSubTests = 0;
        $totalSubTests = $subTests->count();
        
        $subTests->each(function ($subTest) use ($questions, $answers, $subAttempts, &$completedSubTests) {
            $subQuestions = $questions[$subTest->id] ?? collect();
            $subQuestionIds = $subQuestions->pluck('id');

            $subAnswers = $answers->filter(fn ($_, $questionId) => $subQuestionIds->contains($questionId))->flatten();

            $totalQuestions = $subQuestions->count();
            $answeredCount = $subAnswers->whereNotNull('answer')->count();
            $correctCount = $subAnswers->where('is_correct', true)->count();

            $subAttempt = $subAttempts[$subTest->id] ?? null;

            $subTest->total_questions = $totalQuestions;
            $subTest->correct_count = $correctCount;

            $subTest->progress = $totalQuestions > 0
                ? round(($correctCount / $totalQuestions) * 100)
                : 0;

            $subTest->non_null_answers_count = $answeredCount;
            $subTest->null_answers_count = max(0, $totalQuestions - $answeredCount);

            $subTest->all_answered = $answeredCount >= $totalQuestions && $totalQuestions > 0;

            $subTest->completed = $subAttempt
                ? (bool) $subAttempt->is_finished
                : false;
                
            if ($subTest->completed) {
                $completedSubTests++;
            }
        });

        $totalQuestions = $questions->flatten()->count();
        $totalAttemptRecords = $answers->flatten()->count();

        $completionRate = $totalQuestions > 0
            ? round(($totalAttemptRecords / $totalQuestions) * 100)
            : 0;

        $totalDuration = $subTests->sum('duration');
        
        $allSubTestsCompleted = ($completedSubTests >= $totalSubTests) && ($totalSubTests > 0);
        
        return view('user.sub-tests', compact(
            'test',
            'typeTest',
            'subTests',
            'attempt',
            'totalDuration',
            'completionRate',
            'allSubTestsCompleted' 
        ));
    }
    public function questions(Tests $test, TypeTests $typeTest, SubTests $subTest, Request $request) {
        $tokenCheck = $this->checkRememberToken();
        if ($tokenCheck) {
            return $tokenCheck;
        }
        $this->assertTestHierarchy($test, $typeTest, $subTest);

        $user = Auth::user();

        $attempt = $this->attemptService->getOrCreateAttempt($test);
        $typeAttempt = $this->attemptService->getOrCreateTypeAttempt($attempt, $typeTest);
        $subAttempt  = $this->attemptService->getOrCreateSubAttempt($typeAttempt, $subTest);

        if ($subAttempt->is_finished) {
            return redirect()
                ->route('user.sub-tests', [
                    'test' => $test->slug,
                    'typeTest' => $typeTest->slug,
                ])
                ->with('info', 'Subtest ini sudah diselesaikan.');
        }
        $allQuestions = $subTest->questions()
            ->with('choices')
            ->orderBy('id')
            ->get();

        if ($allQuestions->isEmpty()) {
            return back()->with('error', 'Belum ada soal pada subtest ini.');
        }

        $isShuffle = $test->is_shuffle == 1;
        if ($isShuffle) {
            $allQuestions = $this->shuffleService
                ->getUniqueShuffledQuestions($user, $test, $subTest, $allQuestions);
        }
        $questionIdToNumber = $this->shuffleService
            ->createQuestionIdToNumberMap($allQuestions);
        $answers = AttemptAnswer::where('attempt_id', $attempt->id)
            ->whereIn('question_id', $allQuestions->pluck('id'))
            ->get()
            ->keyBy('question_id');
        $ranges = $this->rangeService->getActiveRanges($subTest->id);
        if ($ranges->isEmpty()) {
            return $this->handleNoRanges(
                $test,
                $typeTest,
                $subTest,
                $attempt,
                $allQuestions,
                $request,
                $isShuffle,
                $questionIdToNumber
            );
        }
        [$selectedRange, $currentRangeIndex] =
            $this->rangeService->findCurrentRange(
                $ranges,
                $answers,
                $allQuestions,
                $questionIdToNumber,
                [
                    'range_id' => $request->query('range_id'),
                    'range_completed' => $request->query('range_completed'),
                    'range_switch' => $request->query('range_switch'),
                    'auto_range_switch' => $request->query('auto_range_switch'),
                ]
            );
        $questions = $this->rangeService
            ->getQuestionsInRange($selectedRange, $allQuestions, $questionIdToNumber);

        if ($questions->isEmpty()) {
            return back()->with('error', 'Tidak ada soal dalam range ini.');
        }
        $currentQuestionIndex = $this->resolveCurrentQuestionIndex(
            $request,
            $questions,
            $allQuestions,
            $questionIdToNumber
        );

        $currentQuestionIndex = max(1, min($currentQuestionIndex, $questions->count()));
        $currentQuestion = $questions[$currentQuestionIndex - 1];
        $startNumber = $questionIdToNumber[$selectedRange->start_number] ?? null;
        $endNumber   = $questionIdToNumber[$selectedRange->end_number] ?? null;
        $currentNumber = $questionIdToNumber[$currentQuestion->id] ?? null;

        if (!$currentNumber || $currentNumber < $startNumber || $currentNumber > $endNumber) {
            return redirect()->route('user.questions', [
                'test' => $test->slug,
                'typeTest' => $typeTest->slug,
                'subTest' => $subTest->slug,
                'question' => 1,
                'range_id' => $selectedRange->id,
            ]);
        }
        $rangeProgress = $this->rangeService->getRangeProgress(
            $answers,
            $selectedRange,
            $questions,
            $ranges,
            $currentRangeIndex
        );
        $previousAnswer = $answers[$currentQuestion->id] ?? null;
        return view('user.questions', [
            'test' => $test,
            'typeTest' => $typeTest,
            'subTest' => $subTest,
            'questions' => $questions,
            'currentQuestion' => $currentQuestion,
            'currentQuestionIndex' => $currentQuestionIndex,
            'totalQuestions' => $questions->count(),
            'previousAnswer' => $previousAnswer,
            'attempt' => $attempt,
            'selectedRange' => $selectedRange,
            'rangeProgress' => $rangeProgress,
            'allQuestions' => $allQuestions,
            'allRanges' => $ranges,
            'questionIdToNumber' => $questionIdToNumber,
            'isShuffle' => $isShuffle,
            'totalAllQuestions' => $allQuestions->count(),
            'nextRange' => $rangeProgress['next_range_id']
                ? $ranges->firstWhere('id', $rangeProgress['next_range_id'])
                : null,
        ]);
    }

    private function resolveCurrentQuestionIndex(Request $request, $questions, $allQuestions, $questionIdToNumber)
    {
        $requestedQuestion = $request->query('question');
        
        if (!$requestedQuestion || !is_numeric($requestedQuestion)) {
            return 1;
        }
        
        $questionNumber = (int) $requestedQuestion;
        
        foreach ($allQuestions as $index => $question) {
            if (($index + 1) == $questionNumber) {
                foreach ($questions as $pos => $rangeQuestion) {
                    if ($rangeQuestion->id == $question->id) {
                        return $pos + 1;
                    }
                }
                return 1;
            }
        }
        
        return 1;
    }

    private function handleNoRanges($test, $typeTest, $subTest, $attempt, $allQuestions, $request, $isShuffle, $questionIdToNumber)
    {
        $questions = $allQuestions;
        $totalQuestions = $allQuestions->count();

        $currentQuestionIndex = (int) $request->query('question', 1);

        if ($currentQuestionIndex < 1 || $currentQuestionIndex > $totalQuestions) {
            $currentQuestionIndex = 1;
        }

        if ($questions->isEmpty() || !isset($questions[$currentQuestionIndex - 1])) {
            return back()->with('error', 'Data soal tidak valid.');
        }

        $currentQuestion = $questions[$currentQuestionIndex - 1];

        $previousAnswer = AttemptAnswer::where([
            'attempt_id' => $attempt->id,
            'question_id' => $currentQuestion->id
        ])->first();

        $answeredQuestions = AttemptAnswer::where('attempt_id', $attempt->id)
            ->whereIn('question_id', $allQuestions->pluck('id'))
            ->whereNotNull('answer')
            ->count();

        return view('user.questions', [
            'test' => $test,
            'typeTest' => $typeTest,
            'subTest' => $subTest,
            'questions' => $questions,
            'currentQuestion' => $currentQuestion,
            'currentQuestionIndex' => $currentQuestionIndex,
            'totalQuestions' => $totalQuestions,
            'previousAnswer' => $previousAnswer,
            'attempt' => $attempt,
            'selectedRange' => null,
            'answeredQuestions' => $answeredQuestions,
            'allQuestions' => $allQuestions,
            'isShuffle' => $isShuffle,
            'totalAllQuestions' => $allQuestions->count(),
            'questionIdToNumber' => $questionIdToNumber
        ]);
    }

    public function getShuffledOrder(Request $request, Tests $test, SubTests $subTest)
    {
        $tokenCheck = $this->checkRememberToken();
        if ($tokenCheck) {
            return response()->json(['error' => 'Session expired'], 401);
        }

        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $typeTest = $subTest->typeTest;
        abort_unless($typeTest && (int) $typeTest->tests_id === (int) $test->id, 404);

        $result = $this->shuffleService->getShuffledOrder($user, $test, $subTest);

        return response()->json([
            'shuffled' => $result['shuffled'],
            'order' => $result['order'],
            'total_questions' => $result['total_questions']
        ]);
    }

    public function submitAll(Request $request, Tests $test, TypeTests $typeTest, SubTests $subTest)
    {
        $tokenCheck = $this->checkRememberToken();
        if ($tokenCheck) {
            return $tokenCheck;
        }
        $this->assertTestHierarchy($test, $typeTest, $subTest);

        $questions = $subTest->questions()
            ->select('id', 'correct_option_id')
            ->get();
        $answersInput = $this->validatedAnswers($request, $subTest, $questions);

        $user = Auth::user();

        return DB::transaction(function () use ($test, $typeTest, $subTest, $user, $questions, $answersInput) {
            $attempt = $this->attemptService->getOrCreateAttempt($test);
            $attemptId = $attempt->id;

            $typeAttempt = $this->attemptService->getOrCreateTypeAttempt($attempt, $typeTest);
            $createdSubAttempt = $this->attemptService->getOrCreateSubAttempt($typeAttempt, $subTest);
            $subAttempt = AttemptSubTest::whereKey($createdSubAttempt->id)->lockForUpdate()->firstOrFail();

            abort_unless((int) $attempt->user_id === (int) $user->id, 404);

            if ($subAttempt->is_finished) {
                return redirect()
                    ->route('user.sub-tests', [
                        'test' => $test->slug,
                        'typeTest' => $typeTest->slug,
                    ])
                    ->with('info', 'Subtest ini sudah diselesaikan sebelumnya.');
            }
            $now = now();
            $upserts = [];

            $correctCount = 0;
            $wrongCount   = 0;

            $questionsById = $questions->keyBy('id');
            foreach ($answersInput as $questionId => $chosen) {
                $question = $questionsById->get((int) $questionId);
                if (!$question) {
                    continue;
                }

                $isCorrect = $chosen !== null
                    && $chosen == $question->correct_option_id;

                $upserts[] = [
                    'attempt_id' => $attemptId,
                    'question_id' => $question->id,
                    'answer' => $chosen,
                    'is_correct' => $isCorrect,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            if ($upserts !== []) {
                AttemptAnswer::upsert(
                    $upserts,
                    ['attempt_id', 'question_id'],
                    ['answer', 'is_correct', 'updated_at']
                );
            }

            $persistedAnswers = AttemptAnswer::query()
                ->where('attempt_id', $attemptId)
                ->whereIn('question_id', $questions->pluck('id'))
                ->whereNotNull('answer')
                ->get(['is_correct']);
            $correctCount = $persistedAnswers->where('is_correct', true)->count();
            $wrongCount = $persistedAnswers->where('is_correct', false)->count();
            $subAttempt->update([
                'correct_count' => $correctCount,
                'wrong_count' => $wrongCount,
                'is_finished' => 1,
                'updated_at' => $now,
            ]);
            $scoringService = new \App\Services\ScoringService();

            $subScore = $scoringService->calculateSubTestScore($subAttempt);
            $subAttempt->update(['score' => $subScore]);
            $typeStats = AttemptSubTest::where('attempt_type_test_id', $typeAttempt->id)
                ->selectRaw('
                    SUM(correct_count) as correct,
                    SUM(correct_count + wrong_count) as total
                ')
                ->first();

            $typeAttempt->update([
                'correct' => $typeStats->correct ?? 0,
                'total' => $typeStats->total ?? 0,
                'score' => $scoringService->calculateTypeTestScore($typeAttempt),
                'updated_at' => $now,
            ]);
            $attemptStats = AttemptTypeTest::where('attempt_id', $attempt->id)
                ->selectRaw('
                    SUM(correct) as total_correct,
                    SUM(total) as total_question
                ')
                ->first();

            $attempt->update([
                'total_correct' => $attemptStats->total_correct ?? 0,
                'total_question' => $attemptStats->total_question ?? 0,
                'score' => $scoringService->calculateAttemptScore($attempt),
                'updated_at' => $now,
            ]);
            return redirect()
                ->route('user.sub-tests', [
                    'test' => $test->slug,
                    'typeTest' => $typeTest->slug,
                ])
                ->with('success', 'Semua jawaban telah berhasil disubmit dan subtest selesai!');
        });
    }

    public function submitSingle(Request $request, Tests $test, TypeTests $typeTest, SubTests $subTest)
    {
        $tokenCheck = $this->checkRememberToken();
        if ($tokenCheck) {
            return $tokenCheck;
        }
        $this->assertTestHierarchy($test, $typeTest, $subTest);

        $validated = $request->validate([
            'question_id' => ['required', 'integer', 'min:1'],
            'answer' => ['nullable', 'integer', 'min:1'],
        ]);

        $question = $subTest->questions()
            ->select('id', 'correct_option_id')
            ->whereKey($validated['question_id'])
            ->first();

        if (!$question) {
            throw ValidationException::withMessages([
                'question_id' => 'Pertanyaan tidak termasuk dalam subtest ini.',
            ]);
        }

        $answer = $validated['answer'] ?? null;
        if ($answer !== null && !QuestionChoices::query()
            ->whereKey($answer)
            ->where(QuestionChoices::questionForeignKey(), $question->id)
            ->exists()) {
            throw ValidationException::withMessages([
                'answer' => 'Pilihan jawaban tidak dimiliki oleh pertanyaan tersebut.',
            ]);
        }

        $user = Auth::user();

        return DB::transaction(function () use ($test, $typeTest, $subTest, $question, $answer, $user) {
            $attempt = $this->attemptService->getOrCreateAttempt($test);
            $typeAttempt = $this->attemptService->getOrCreateTypeAttempt($attempt, $typeTest);
            $createdSubAttempt = $this->attemptService->getOrCreateSubAttempt($typeAttempt, $subTest);
            $subAttempt = AttemptSubTest::whereKey($createdSubAttempt->id)->lockForUpdate()->firstOrFail();

            abort_unless((int) $attempt->user_id === (int) $user->id, 404);
            if ($subAttempt->is_finished) {
                return redirect()->route('user.sub-tests', [
                    'test' => $test->slug,
                    'typeTest' => $typeTest->slug,
                ])->with('info', 'Subtest ini sudah diselesaikan.');
            }

            $isCorrect = $answer !== null && (int) $answer === (int) $question->correct_option_id;
            $now = now();
            AttemptAnswer::upsert([[
                'attempt_id' => $attempt->id,
                'question_id' => $question->id,
                'answer' => $answer,
                'is_correct' => $isCorrect,
                'created_at' => $now,
                'updated_at' => $now,
            ]], ['attempt_id', 'question_id'], ['answer', 'is_correct', 'updated_at']);

            return back()->with('success', 'Jawaban berhasil disimpan.');
        });
    }
    
    public function submit(Request $request, Tests $test, TypeTests $typeTest, SubTests $subTest)
    {
        $tokenCheck = $this->checkRememberToken();
        if ($tokenCheck) {
            return $tokenCheck;
        }
        $this->assertTestHierarchy($test, $typeTest, $subTest);

        if ($request->has('question_id')) {
            return $this->submitSingle($request, $test, $typeTest, $subTest);
        }
        return $this->submitAll($request, $test, $typeTest, $subTest);
    }

    private function updateFinalStatistics($attempt, $typeAttempt, $subAttempt)
    {
        $scoreService = new \App\Services\ScoringService();
        
        $typeAttempt->update([
            'correct' => AttemptSubTest::where('attempt_type_test_id', $typeAttempt->id)->sum('correct_count'),
            'total' => AttemptSubTest::where('attempt_type_test_id', $typeAttempt->id)
                ->sum('correct_count') +
                AttemptSubTest::where('attempt_type_test_id', $typeAttempt->id)
                ->sum('wrong_count'),
            'score' => $scoreService->calculateTypeTestScore($typeAttempt),
            'updated_at' => now(),
        ]);

        $attempt->update([
            'total_correct' => AttemptTypeTest::where('attempt_id', $attempt->id)->sum('correct'),
            'total_question' => AttemptTypeTest::where('attempt_id', $attempt->id)->sum('total'),
            'score' => $scoreService->calculateAttemptScore($attempt),
            'updated_at' => now(),
        ]);
    }
    
    public function completeRange(Request $request, Tests $test, TypeTests $typeTest, SubTests $subTest)
    {
        $tokenCheck = $this->checkRememberToken();
        if ($tokenCheck) {
            return $tokenCheck;
        }

        $this->assertTestHierarchy($test, $typeTest, $subTest);

        $request->validate([
            'range_id' => 'required|integer',
        ]);
        
        $user = Auth::user();
        $attempt = $this->attemptService->getOrCreateAttempt($test);
        $range = $subTest->instructionRanges()->whereKey($request->range_id)->firstOrFail();
        
        $allQuestions = $subTest->questions()->orderBy('id')->get();
        $isShuffle = $test->is_shuffle == 1;
        
        if ($isShuffle) {
            $allQuestions = $this->shuffleService->getUniqueShuffledQuestions($user, $test, $subTest, $allQuestions);
        }
        
        $questionIdToNumber = $this->shuffleService->createQuestionIdToNumberMap($allQuestions);
        $ranges = $this->rangeService->getActiveRanges($subTest->id);
        
        $currentRangeIndex = $ranges->search(function ($item) use ($range) {
            return $item->id == $range->id;
        });
        
        $nextRange = null;
        if ($currentRangeIndex !== false && isset($ranges[$currentRangeIndex + 1])) {
            $nextRange = $ranges[$currentRangeIndex + 1];
        }
        
        if ($nextRange) {
            $nextStartNumber = $questionIdToNumber[$nextRange->start_number] ?? 1;
            return redirect()->route('user.questions', [
                'test' => $test->slug,
                'typeTest' => $typeTest->slug,
                'subTest' => $subTest->slug,
                'question' => $nextStartNumber,
                'range_id' => $nextRange->id
            ])->with('success', 'Berhasil berpindah ke range berikutnya.');
        } else {
            return redirect()->route('user.sub-tests', [
                'test' => $test->slug,
                'typeTest' => $typeTest->slug,
            ])->with('success', 'Semua range telah diselesaikan!');
        }
    }
}
