<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('tests', 'type')) {
            Schema::table('tests', function (Blueprint $table) {
                $table->string('type', 32)->default('OTHER')->after('slug');
            });
        }

        DB::table('tests')->orderBy('id')->chunkById(500, function ($tests) {
            foreach ($tests as $test) {
                $label = strtolower(($test->name ?? '') . ' ' . ($test->slug ?? ''));
                $type = str_contains($label, 'potensi akademik') || str_contains($label, 'potensi-akademik')
                    || preg_match('/\\btpa\\b/', $label)
                    ? 'TPA'
                    : ((str_contains($label, 'speed accuracy') || str_contains($label, 'speed-accuracy')) ? 'SA' : 'OTHER');

                DB::table('tests')->where('id', $test->id)->update(['type' => $type]);
            }
        });

        // These temporary compound indexes make the duplicate cleanup efficient.
        if (!DB::table('information_schema.STATISTICS')->where('TABLE_SCHEMA', DB::getDatabaseName())->where('TABLE_NAME', 'attempt_answers')->where('INDEX_NAME', 'attempt_answers_dedupe_idx')->exists()) {
            Schema::table('attempt_answers', function (Blueprint $table) {
                $table->index(['attempt_id', 'question_id', 'updated_at', 'id'], 'attempt_answers_dedupe_idx');
            });
        }
        if (!DB::table('information_schema.STATISTICS')->where('TABLE_SCHEMA', DB::getDatabaseName())->where('TABLE_NAME', 'attempt_type_tests')->where('INDEX_NAME', 'attempt_type_tests_dedupe_idx')->exists()) {
            Schema::table('attempt_type_tests', function (Blueprint $table) {
                $table->index(['attempt_id', 'type_test_id', 'updated_at', 'id'], 'attempt_type_tests_dedupe_idx');
            });
        }
        if (!DB::table('information_schema.STATISTICS')->where('TABLE_SCHEMA', DB::getDatabaseName())->where('TABLE_NAME', 'attempt_sub_tests')->where('INDEX_NAME', 'attempt_sub_tests_dedupe_idx')->exists()) {
            Schema::table('attempt_sub_tests', function (Blueprint $table) {
                $table->index(['attempt_type_test_id', 'sub_test_id', 'updated_at', 'id'], 'attempt_sub_tests_dedupe_idx');
            });
        }

        // Keep the newest answer value for each attempt/question pair.
        DB::statement("DELETE old FROM attempt_answers old JOIN attempt_answers newer
            ON newer.attempt_id = old.attempt_id
            AND newer.question_id = old.question_id
            AND (COALESCE(newer.updated_at, '1000-01-01 00:00:00') > COALESCE(old.updated_at, '1000-01-01 00:00:00')
                OR (COALESCE(newer.updated_at, '1000-01-01 00:00:00') = COALESCE(old.updated_at, '1000-01-01 00:00:00') AND newer.id > old.id))");

        // Keep the latest statistics row for each attempt/type-test pair.
        DB::statement("DELETE old FROM attempt_type_tests old JOIN attempt_type_tests newer
            ON newer.attempt_id = old.attempt_id
            AND newer.type_test_id = old.type_test_id
            AND (COALESCE(newer.updated_at, '1000-01-01 00:00:00') > COALESCE(old.updated_at, '1000-01-01 00:00:00')
                OR (COALESCE(newer.updated_at, '1000-01-01 00:00:00') = COALESCE(old.updated_at, '1000-01-01 00:00:00') AND newer.id > old.id))");

        DB::statement("DELETE old FROM attempt_sub_tests old JOIN attempt_sub_tests newer
            ON newer.attempt_type_test_id = old.attempt_type_test_id
            AND newer.sub_test_id = old.sub_test_id
            AND (COALESCE(newer.updated_at, '1000-01-01 00:00:00') > COALESCE(old.updated_at, '1000-01-01 00:00:00')
                OR (COALESCE(newer.updated_at, '1000-01-01 00:00:00') = COALESCE(old.updated_at, '1000-01-01 00:00:00') AND newer.id > old.id))");

        if (!DB::table('information_schema.STATISTICS')->where('TABLE_SCHEMA', DB::getDatabaseName())->where('TABLE_NAME', 'attempt_answers')->where('INDEX_NAME', 'attempt_answers_attempt_question_unique')->exists()) {
            Schema::table('attempt_answers', function (Blueprint $table) {
                $table->unique(['attempt_id', 'question_id'], 'attempt_answers_attempt_question_unique');
            });
        }

        if (!DB::table('information_schema.STATISTICS')->where('TABLE_SCHEMA', DB::getDatabaseName())->where('TABLE_NAME', 'attempt_type_tests')->where('INDEX_NAME', 'attempt_type_tests_attempt_type_unique')->exists()) {
            Schema::table('attempt_type_tests', function (Blueprint $table) {
                $table->unique(['attempt_id', 'type_test_id'], 'attempt_type_tests_attempt_type_unique');
            });
        }

        if (!DB::table('information_schema.STATISTICS')->where('TABLE_SCHEMA', DB::getDatabaseName())->where('TABLE_NAME', 'attempt_sub_tests')->where('INDEX_NAME', 'attempt_sub_tests_attempt_subtest_unique')->exists()) {
            Schema::table('attempt_sub_tests', function (Blueprint $table) {
                $table->unique(['attempt_type_test_id', 'sub_test_id'], 'attempt_sub_tests_attempt_subtest_unique');
            });
        }

        Schema::table('attempt_answers', function (Blueprint $table) {
            $table->dropIndex('attempt_answers_dedupe_idx');
        });
        Schema::table('attempt_type_tests', function (Blueprint $table) {
            $table->dropIndex('attempt_type_tests_dedupe_idx');
        });
        Schema::table('attempt_sub_tests', function (Blueprint $table) {
            $table->dropIndex('attempt_sub_tests_dedupe_idx');
        });

        if (!DB::table('information_schema.STATISTICS')->where('TABLE_SCHEMA', DB::getDatabaseName())->where('TABLE_NAME', 'attempts')->where('INDEX_NAME', 'attempts_user_created_idx')->exists()) {
            Schema::table('attempts', function (Blueprint $table) {
                $table->index(['user_id', 'created_at'], 'attempts_user_created_idx');
            });
        }

        if (!DB::table('information_schema.STATISTICS')->where('TABLE_SCHEMA', DB::getDatabaseName())->where('TABLE_NAME', 'attempts')->where('INDEX_NAME', 'attempts_user_test_created_idx')->exists()) {
            Schema::table('attempts', function (Blueprint $table) {
                $table->index(['user_id', 'test_id', 'created_at'], 'attempts_user_test_created_idx');
            });
        }

        if (!DB::table('information_schema.STATISTICS')->where('TABLE_SCHEMA', DB::getDatabaseName())->where('TABLE_NAME', 'iq_ranges')->where('INDEX_NAME', 'iq_ranges_type_raw_min_raw_max_idx')->exists()) {
            Schema::table('iq_ranges', function (Blueprint $table) {
                $table->index(['test_type', 'raw_min', 'raw_max'], 'iq_ranges_type_raw_min_raw_max_idx');
            });
        }
    }

    public function down(): void
    {
        Schema::table('iq_ranges', function (Blueprint $table) {
            $table->dropIndex('iq_ranges_type_raw_min_raw_max_idx');
        });
        Schema::table('attempts', function (Blueprint $table) {
            $table->dropIndex('attempts_user_created_idx');
            $table->dropIndex('attempts_user_test_created_idx');
        });
        Schema::table('attempt_sub_tests', function (Blueprint $table) {
            $table->dropUnique('attempt_sub_tests_attempt_subtest_unique');
        });
        Schema::table('attempt_type_tests', function (Blueprint $table) {
            $table->dropUnique('attempt_type_tests_attempt_type_unique');
        });
        Schema::table('attempt_answers', function (Blueprint $table) {
            $table->dropUnique('attempt_answers_attempt_question_unique');
        });
        Schema::table('tests', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
