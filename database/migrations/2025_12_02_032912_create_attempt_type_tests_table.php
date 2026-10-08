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
        Schema::create('attempt_type_tests', function (Blueprint $table) {
            $table->id();

            $table->foreignId('attempt_id')->constrained('attempts')->onDelete('cascade');
            $table->foreignId('type_test_id')->constrained('type_tests')->onDelete('cascade');

            $table->integer('correct')->default(0);
            $table->integer('total')->default(0);
            $table->double('score')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attempt_type_tests');
    }
};
