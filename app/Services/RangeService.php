<?php

namespace App\Services;

use App\Models\SubtestInstructionRange;
use Illuminate\Support\Collection;

class RangeService
{
    public function getActiveRanges($subTestId): Collection
    {
        return cache()->remember(
            "active_ranges_subtest_{$subTestId}",
            3600,
            fn () => SubtestInstructionRange::where('sub_test_id', $subTestId)
                ->where('is_active', true)
                ->orderBy('order_number')
                ->select(['id', 'sub_test_id', 'start_number', 'end_number', 'order_number'])
                ->get()
        );
    }

    public function getQuestionsInRange($range, Collection $allQuestions, array $questionIdToNumber): Collection
    {
        $start = $questionIdToNumber[$range->start_number] ?? null;
        $end   = $questionIdToNumber[$range->end_number] ?? null;

        if (!$start || !$end) {
            return collect();
        }

        return $allQuestions->slice($start - 1, $end - $start + 1)->values();
    }

    public function findCurrentRange(Collection $ranges, Collection $answers, Collection $allQuestions, array $questionIdToNumber, array $context = []): array {
        if ($ranges->isEmpty()) {
            return [null, null];
        }
        $rangeStats = function ($range) use ($answers, $allQuestions, $questionIdToNumber) {
            $questions = $this->getQuestionsInRange(
                $range,
                $allQuestions,
                $questionIdToNumber
            );

            if ($questions->isEmpty()) {
                return [0, 0];
            }

            $answered = $answers
                ->whereNotNull('answer')
                ->whereIn('question_id', $questions->pluck('id'))
                ->count();

            return [$answered, $questions->count()];
        };
        if (!empty($context['range_id'])) {
            $forcedIndex = $ranges->search(fn ($r) => $r->id == $context['range_id']);

            if ($forcedIndex !== false) {
                return [$ranges[$forcedIndex], $forcedIndex];
            }
        }
        foreach ($ranges as $i => $range) {
            [$answered, $total] = $rangeStats($range);

            if ($answered < $total) {
                return [$range, $i];
            }
        }

        return [$ranges->last(), $ranges->count() - 1];
    }


    public function getRangeProgress(
        Collection $answers,
        $range,
        Collection $questionsInRange,
        Collection $ranges,
        int $currentRangeIndex
    ): array {
        $answered = $answers
            ->whereNotNull('answer')
            ->whereIn('question_id', $questionsInRange->pluck('id'));

        $total = $questionsInRange->count();
        $answeredCount = $answered->count();

        $nextRange = $ranges[$currentRangeIndex + 1] ?? null;

        return [
            'current' => $answeredCount,
            'total' => $total,
            'percentage' => $total > 0 ? ($answeredCount / $total) * 100 : 0,
            'range_index' => $currentRangeIndex,
            'total_ranges' => $ranges->count(),
            'is_last_range' => $currentRangeIndex === $ranges->count() - 1,
            'next_range_id' => $nextRange?->id,
        ];
    }

    public function resolveRangeTransition(
    Collection $ranges,
    Collection $answers,
    Collection $allQuestions,
    array $context = []
    ) {
        [$currentRange, $index] =
            $this->findCurrentRange(...);

        if (!empty($context['auto_range_switch'])) {
            return $ranges[$index + 1] ?? null;
        }

        if (!empty($context['range_completed'])) {
            return $ranges[$index + 1] ?? null;
        }

        return $currentRange;
    }

}
