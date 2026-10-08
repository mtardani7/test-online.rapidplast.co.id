<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('attempt_sub_tests')) {
            return;
        }

        $hasStartedAt = Schema::hasColumn('attempt_sub_tests', 'started_at');
        $hasExpiresAt = Schema::hasColumn('attempt_sub_tests', 'expires_at');

        if (!$hasStartedAt || !$hasExpiresAt) {
            Schema::table('attempt_sub_tests', function (Blueprint $table) use ($hasStartedAt, $hasExpiresAt) {
                if (!$hasStartedAt) {
                    $table->timestamp('started_at')->nullable();
                }
                if (!$hasExpiresAt) {
                    $table->timestamp('expires_at')->nullable();
                }
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('attempt_sub_tests')) {
            return;
        }

        $columns = array_values(array_filter(
            ['started_at', 'expires_at'],
            fn (string $column) => Schema::hasColumn('attempt_sub_tests', $column)
        ));

        if ($columns !== []) {
            Schema::table('attempt_sub_tests', function (Blueprint $table) use ($columns) {
                $table->dropColumn($columns);
            });
        }
    }
};
