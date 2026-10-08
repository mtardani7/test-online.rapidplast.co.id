<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('attempt_sub_tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attempt_type_test_id')->constrained('attempt_type_tests')->onDelete('cascade');
            $table->foreignId('sub_test_id')->constrained('sub_tests')->onDelete('cascade');
            $table->integer('score')->default(0);
            $table->integer('correct_count')->default(0);
            $table->integer('wrong_count')->default(0);
            $table->boolean('is_finished')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attempt_sub_tests');
    }
};
