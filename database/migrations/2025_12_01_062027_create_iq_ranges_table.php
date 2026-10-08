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
        Schema::create('iq_ranges', function (Blueprint $table) {
            $table->id();
            $table->string('test_type');
            $table->integer('raw_min');  
            $table->integer('raw_max');  
            $table->integer('iq_score'); 
            $table->string('category');  
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('iq_ranges');
    }
};
