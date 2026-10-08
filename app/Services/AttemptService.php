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

    public function ensureSubTestDeadline(AttemptSubTest $subAttempt, SubTests $subTest): AttemptSubTest
    {
        abort_unless((int) $subAttempt->sub_test_id === (int) $subTest->id, 404);

        return DB::transaction(function () use ($subAttempt, $subTest) {
            AttemptTypeTest::query()
                ->whereKey($subAttempt->attempt_type_test_id)
                ->lockForUpdate()
                ->firstOrFail();

            return $this->ensureSubTestDeadlineLocked($subAttempt, $subTest);
        });
    }

    public function isSubTestExpired(AttemptSubTest $subAttempt, $at = null): bool
    {
        return $subAttempt->expires_at !== null
            && $subAttempt->expires_at->lte($at ?? now());
    }

    public function openSubTestAttempt($typeAttempt, SubTests $subTest): AttemptSubTest
    {
        abort_unless((int) $typeAttempt->type_test_id === (int) $subTest->type_tests_id, 404);

        return DB::transaction(function () use ($typeAttempt, $subTest) {
            $lockedTypeAttempt = AttemptTypeTest::query()
                ->whereKey($typeAttempt->id)
                ->lockForUpdate()
                ->firstOrFail();
            abort_unless((int) $lockedTypeAttempt->type_test_id === (int) $subTest->type_tests_id, 404);

            $subAttempt = AttemptSubTest::firstOrCreate(
                [
                    'attempt_type_test_id' => $lockedTypeAttempt->id,
                    'sub_test_id' => $subTest->id,
                ],
                ['score' => 0, 'correct_count' => 0, 'wrong_count' => 0]
            );

            $lockedSubAttempt = AttemptSubTest::query()
                ->whereKey($subAttempt->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedSubAttempt->is_finished) {
                return $lockedSubAttempt;
            }

            return $this->ensureSubTestDeadlineLocked($lockedSubAttempt, $subTest);
        });
    }

    private function ensureSubTestDeadlineLocked(AttemptSubTest $subAttempt, SubTests $subTest): AttemptSubTest
    {
        $lockedSubAttempt = AttemptSubTest::query()
            ->whereKey($subAttempt->id)
            ->lockForUpdate()
            ->firstOrFail();

        if ($lockedSubAttempt->started_at === null && $lockedSubAttempt->expires_at === null) {
            $startedAt = now();
            $expiresAt = $startedAt->copy()->addMinutes((int) ($subTest->duration ?? 15));

            AttemptSubTest::query()
                ->whereKey($lockedSubAttempt->id)
                ->whereNull('started_at')
                ->whereNull('expires_at')
                ->update([
                    'started_at' => $startedAt,
                    'expires_at' => $expiresAt,
                    'updated_at' => $startedAt,
                ]);
        }

        return $lockedSubAttempt->fresh();
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
