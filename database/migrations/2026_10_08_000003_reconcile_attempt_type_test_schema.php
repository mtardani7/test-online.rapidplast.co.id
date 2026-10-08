<?php

use Illuminate\Database\Migrations\Migration;

require_once __DIR__ . '/Support/AttemptTypeTestsSchema.php';

return new class extends Migration
{
    public function up(): void
    {
        AttemptTypeTestsSchema::reconcile();
        AttemptTypeTestsSchema::ensureUniquePair();
    }

    public function down(): void
    {
        throw new RuntimeException('This data-preserving schema reconciliation is forward-only. Restore from a verified backup to reverse it safely.');
    }
};
