<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attempts', function (Blueprint $table) {
            $table->integer('iq_score')->nullable()->after('score');
            $table->string('iq_category')->nullable()->after('iq_score');
        });

        $ranges = DB::table('iq_ranges')
            ->where('test_type', 'TPA')
            ->orderBy('raw_min')
            ->get();

        if ($ranges->isEmpty()) {
            return;
        }

        $attempts = DB::table('attempts')
            ->join('tests', 'attempts.test_id', '=', 'tests.id')
            ->select('attempts.id', 'attempts.score', 'tests.name', 'tests.slug');

        if (Schema::hasColumn('tests', 'type')) {
            $attempts->addSelect('tests.type');
        }

        $attempts->orderBy('attempts.id')->chunk(500, function ($batch) use ($ranges) {
            foreach ($batch as $attempt) {
                $type = isset($attempt->type) ? strtoupper(trim($attempt->type)) : '';
                $testLabel = ($attempt->name ?? '') . ' ' . ($attempt->slug ?? '');
                $isTpa = $type !== ''
                    ? $type === 'TPA'
                    : (bool) preg_match('/\\btpa\\b|potensi[\\s-]+akademik/i', $testLabel);

                if (!$isTpa) {
                    continue;
                }

                foreach ($ranges as $range) {
                    if ($attempt->score >= $range->raw_min && $attempt->score <= $range->raw_max) {
                        DB::table('attempts')->where('id', $attempt->id)->update([
                            'iq_score' => $range->iq_score,
                            'iq_category' => $range->category,
                        ]);
                        break;
                    }
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('attempts', function (Blueprint $table) {
            $table->dropColumn(['iq_score', 'iq_category']);
        });
    }
};
