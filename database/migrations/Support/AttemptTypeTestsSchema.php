<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Safely reconciles the historical attempt_type_tests.sub_test_id shape with
 * the current attempt -> type test relationship.
 */
class AttemptTypeTestsSchema
{
    public static function reconcile(): void
    {
        if (!Schema::hasTable('attempt_type_tests')) {
            return;
        }

        $hasLegacyColumn = Schema::hasColumn('attempt_type_tests', 'sub_test_id');
        $hasCurrentColumn = Schema::hasColumn('attempt_type_tests', 'type_test_id');

        if (!$hasLegacyColumn && !$hasCurrentColumn) {
            throw new RuntimeException('attempt_type_tests has neither sub_test_id nor type_test_id; migration stopped without changing data.');
        }

        if ($hasLegacyColumn) {
            self::validateLegacyMapping($hasCurrentColumn);

            if (!$hasCurrentColumn) {
                Schema::table('attempt_type_tests', function (Blueprint $table) {
                    $table->unsignedBigInteger('type_test_id')->nullable();
                });
            }

            DB::statement('UPDATE attempt_type_tests AS att
                INNER JOIN sub_tests AS st ON st.id = att.sub_test_id
                SET att.type_test_id = st.type_tests_id
                WHERE att.type_test_id IS NULL');

            if (DB::table('attempt_type_tests')->whereNull('type_test_id')->exists()) {
                throw new RuntimeException('Could not map every attempt_type_tests.sub_test_id to a type_test_id; migration stopped.');
            }

            DB::statement('ALTER TABLE attempt_type_tests MODIFY type_test_id BIGINT UNSIGNED NOT NULL');
            self::ensureTypeTestForeignKey();

            $legacyForeignKeys = DB::select("SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE
                WHERE TABLE_SCHEMA = DATABASE()
                  AND TABLE_NAME = 'attempt_type_tests'
                  AND COLUMN_NAME = 'sub_test_id'
                  AND REFERENCED_TABLE_NAME IS NOT NULL");

            if ($legacyForeignKeys) {
                Schema::table('attempt_type_tests', function (Blueprint $table) use ($legacyForeignKeys) {
                    foreach ($legacyForeignKeys as $foreignKey) {
                        $table->dropForeign($foreignKey->CONSTRAINT_NAME);
                    }
                });
            }

            Schema::table('attempt_type_tests', function (Blueprint $table) {
                $table->dropColumn('sub_test_id');
            });
        } else {
            self::ensureTypeTestForeignKey();
        }
    }

    private static function validateLegacyMapping(bool $hasCurrentColumn): void
    {
        if (!Schema::hasTable('sub_tests') || !Schema::hasColumn('sub_tests', 'type_tests_id')) {
            throw new RuntimeException('Cannot map attempt_type_tests.sub_test_id: sub_tests.type_tests_id is unavailable. No data was changed.');
        }

        $unmapped = DB::table('attempt_type_tests AS att')
            ->leftJoin('sub_tests AS st', 'st.id', '=', 'att.sub_test_id')
            ->whereNull('st.type_tests_id')
            ->count();

        if ($unmapped > 0) {
            throw new RuntimeException("Found {$unmapped} attempt_type_tests rows with no valid subtest-to-type-test mapping. No data was changed.");
        }

        if ($hasCurrentColumn) {
            $conflictingMappings = DB::table('attempt_type_tests AS att')
                ->join('sub_tests AS st', 'st.id', '=', 'att.sub_test_id')
                ->whereNotNull('att.type_test_id')
                ->whereColumn('att.type_test_id', '!=', 'st.type_tests_id')
                ->count();

            if ($conflictingMappings > 0) {
                throw new RuntimeException("Found {$conflictingMappings} conflicting type-test mappings. No data was changed.");
            }
        }

        $ambiguousGroups = DB::table('attempt_type_tests AS att')
            ->join('sub_tests AS st', 'st.id', '=', 'att.sub_test_id')
            ->select('att.attempt_id', 'st.type_tests_id')
            ->groupBy('att.attempt_id', 'st.type_tests_id')
            ->havingRaw('COUNT(*) > 1')
            ->limit(1)
            ->exists();

        if ($ambiguousGroups) {
            throw new RuntimeException('Multiple legacy rows map to the same attempt/type-test pair. Automatic consolidation could alter attempt statistics, so migration stopped without changing data.');
        }
    }

    private static function ensureTypeTestForeignKey(): void
    {
        $foreignKeys = DB::select("SELECT CONSTRAINT_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'attempt_type_tests'
              AND COLUMN_NAME = 'type_test_id'
              AND REFERENCED_TABLE_NAME IS NOT NULL");

        foreach ($foreignKeys as $foreignKey) {
            if ($foreignKey->REFERENCED_TABLE_NAME !== 'type_tests' || $foreignKey->REFERENCED_COLUMN_NAME !== 'id') {
                throw new RuntimeException('attempt_type_tests.type_test_id has an unexpected foreign key; migration stopped.');
            }
        }

        if (!$foreignKeys) {
            Schema::table('attempt_type_tests', function (Blueprint $table) {
                $table->foreign('type_test_id', 'attempt_type_tests_type_test_id_foreign')
                    ->references('id')->on('type_tests')->cascadeOnDelete();
            });
        }
    }

    public static function ensureUniquePair(): void
    {
        $indexes = DB::select("SELECT INDEX_NAME, NON_UNIQUE, GROUP_CONCAT(COLUMN_NAME ORDER BY SEQ_IN_INDEX) AS indexed_columns
            FROM information_schema.STATISTICS
            WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'attempt_type_tests'
            GROUP BY INDEX_NAME, NON_UNIQUE");

        foreach ($indexes as $index) {
            if ((int) $index->NON_UNIQUE === 0 && $index->indexed_columns === 'attempt_id,type_test_id') {
                return;
            }
        }

        $duplicates = DB::table('attempt_type_tests')
            ->select('attempt_id', 'type_test_id')
            ->groupBy('attempt_id', 'type_test_id')
            ->havingRaw('COUNT(*) > 1')
            ->limit(1)
            ->exists();

        if ($duplicates) {
            throw new RuntimeException('Duplicate attempt/type-test rows exist. Unique index was not added and no rows were deleted.');
        }

        Schema::table('attempt_type_tests', function (Blueprint $table) {
            $table->unique(['attempt_id', 'type_test_id'], 'attempt_type_tests_attempt_type_unique');
        });
    }
}
