<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subtest_instruction_ranges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sub_test_id')->constrained('sub_tests')->onDelete('cascade');
            $table->integer('start_number');   // contoh 10
            $table->integer('end_number');     // contoh 20
            $table->integer('order_number');   // untuk urutan instruksi (1 = pertama, 2 = kedua)
            $table->boolean('is_active')->default(false); // HRD mengaktifkan instruksi yang sedang berjalan
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subtest_instruction_ranges');
    }
};
