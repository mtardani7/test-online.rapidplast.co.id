<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;

class ShuffleService
{
    public function getUniqueShuffledQuestions($user, $test, $subTest, Collection $questions): Collection
    {
        $questionsHash = md5($questions->pluck('id')->implode(','));

        $cacheKey = "shuffle:u{$user->id}:t{$test->id}:s{$subTest->id}:{$questionsHash}";

        $order = Cache::remember($cacheKey, 7200, function () use ($user, $test, $subTest, $questions) {
            $seed = $this->generateSeed($user->id, $test->id, $subTest->id);

            $ids = $questions->pluck('id')->all();
            $this->deterministicShuffle($ids, $seed);

            return $ids;
        });

        return $this->reorderById($questions, $order);
    }

    private function reorderById(Collection $questions, array $order): Collection
    {
        $map = $questions->keyBy('id');

        return collect($order)
            ->map(fn ($id) => $map[$id] ?? null)
            ->filter()
            ->values();
    }

    private function generateSeed(int $userId, int $testId, int $subTestId): int
    {
        return crc32("u{$userId}:t{$testId}:s{$subTestId}");
    }

    private function deterministicShuffle(array &$array, int $seed): void
    {
        $random = $seed;

        for ($i = count($array) - 1; $i > 0; $i--) {
            $random = ($random * 1103515245 + 12345) & 0x7fffffff;
            $j = $random % ($i + 1);

            [$array[$i], $array[$j]] = [$array[$j], $array[$i]];
        }
    }

    public function getShuffledOrder($user, $test, $subTest): array
    {
        $questions = $subTest->questions()->orderBy('id')->get();

        if (!$test->is_shuffle) {
            return [
                'shuffled' => false,
                'order' => $questions->pluck('id')->toArray(),
                'total_questions' => $questions->count(),
            ];
        }

        $shuffled = $this->getUniqueShuffledQuestions($user, $test, $subTest, $questions);

        return [
            'shuffled' => true,
            'order' => $shuffled->pluck('id')->toArray(),
            'total_questions' => $shuffled->count(),
        ];
    }

    public function createQuestionIdToNumberMap(Collection $questions): array
    {
        return $questions
            ->pluck('id')
            ->flip()
            ->map(fn ($i) => $i + 1)
            ->toArray();
    }
}
