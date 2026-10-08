<?php

namespace App\Services;

use App\Models\AttemptAnswer;
use App\Models\SubTests;

class QuestionNavigationService
{
    private RangeService $rangeService;

    public function __construct(RangeService $rangeService)
    {
        $this->rangeService = $rangeService;
    }

    /**
     * Hitung statistik subtest DARI MEMORY (bukan query)
     */
    public function calculateSubTestStatisticsFromAnswers($answers)
    {
        $answered = $answers->whereNotNull('answer');
        $correct = $answered->where('is_correct', true)->count();

        return [
            'correct_count' => $correct,
            'wrong_count' => $answered->count() - $correct,
            'total_answered' => $answered->count(),
        ];
    }

    /**
     * Navigasi ke soal berikutnya (RANGE AWARE)
     */
    public function getNextQuestionContext(
        $currentQuestionId,
        $questionIdToNumber,
        $questionsInRange,
        $ranges,
        $currentRangeIndex
    ) {
        $currentNumber = $questionIdToNumber[$currentQuestionId] ?? null;
        if (!$currentNumber) {
            return null;
        }

        $range = $ranges[$currentRangeIndex];
        $rangeStart = $questionIdToNumber[$range->start_number];
        $rangeEnd   = $questionIdToNumber[$range->end_number];

        if ($currentNumber >= $rangeEnd) {
            $nextRange = $ranges[$currentRangeIndex + 1] ?? null;

            return [
                'is_last_in_range' => true,
                'next_range_id' => $nextRange?->id,
                'next_question_number' => $nextRange
                    ? $questionIdToNumber[$nextRange->start_number]
                    : null,
            ];
        }

        return [
            'is_last_in_range' => false,
            'next_question_number' => $currentNumber + 1,
            'next_range_id' => $range->id,
        ];
    }
}
