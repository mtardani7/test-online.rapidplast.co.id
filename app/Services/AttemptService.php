<?php

namespace App\Services;

use App\Models\Attempt;
use App\Models\AttemptTypeTest;
use App\Models\AttemptSubTest;
use App\Models\Tests;
use App\Models\TypeTests;
use App\Models\SubTests;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AttemptService
{
    public function getOrCreateAttempt(Tests $test, ?int $userId = null)
    {
        $userId = $userId ?? Auth::id();

        abort_unless($userId, 401);

        return DB::transaction(function () use ($test, $userId) {
            // attempts intentionally has no user/test unique constraint until retake
            // policy is decided. Locking the owner row serializes same-user creation.
            User::query()->whereKey($userId)->lockForUpdate()->firstOrFail();

            return Attempt::firstOrCreate(
                [
                    'user_id' => $userId,
                    'test_id' => $test->id,
                ],
                [
                    'total_correct' => 0,
                    'total_question' => 0,
                    'score' => 0,
                    'started_at' => now(),
                ]
            );
        });
    }

    public function getOrCreateTypeAttempt($attempt, TypeTests $typeTest)
    {
        abort_unless((int) $attempt->test_id === (int) $typeTest->tests_id, 404);

        return AttemptTypeTest::firstOrCreate(
            [
                'attempt_id' => $attempt->id,
                'type_test_id' => $typeTest->id,
            ],
            [
                'correct' => 0,
                'total' => 0,
                'score' => 0,
            ]
        );
    }

    public function getOrCreateSubAttempt($typeAttempt, SubTests $subTest)
    {
        abort_unless((int) $typeAttempt->type_test_id === (int) $subTest->type_tests_id, 404);

        return AttemptSubTest::firstOrCreate(
            [
                'attempt_type_test_id' => $typeAttempt->id,
                'sub_test_id' => $subTest->id,
            ],
            [
                'score' => 0,
                'correct_count' => 0,
                'wrong_count' => 0,
            ]
        );
    }

    public function initializeTestAttempt(Tests $test)
    {
        $attempt = $this->getOrCreateAttempt($test);

        foreach ($test->typeTests as $typeTest) {
            $typeAttempt = $this->getOrCreateTypeAttempt($attempt, $typeTest);

            foreach ($typeTest->subTests as $subTest) {
                $this->getOrCreateSubAttempt($typeAttempt, $subTest);
            }
        }

        return $attempt;
    }

    public function getAttemptWithRelations($testId, $userId = null)
    {
        $userId = $userId ?? Auth::id();
        
        return Attempt::where('user_id', $userId)
            ->where('test_id', $testId)
            ->with(['typeAttempts', 'typeAttempts.subAttempts'])
            ->first();
    }

    public function updateSubAttemptStatistics($subAttempt, $correctCount, $wrongCount)
    {
        $subAttempt->update([
            'correct_count' => $correctCount,
            'wrong_count' => $wrongCount,
            'updated_at' => now(),
        ]);
    }

    public function markSubAttemptAsFinished($subAttempt)
    {
        $subAttempt->update([
            'is_finished' => 1,
            'updated_at' => now(),
        ]);
    }
}
