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
        Schema::create('exercise_details', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->tinyInteger('previous')->nullable();
            $table->tinyInteger('rir')->nullable();
            $table->string('tempo')->nullable();
            $table->tinyInteger('rest')->nullable();
            $table->tinyInteger('kg')->nullable();
            $table->tinyInteger('reps')->nullable();
            $table->boolean('status')->default(true);
            $table->foreignId('exercise_id')->constrained()->onDelete('cascade');
            $table->boolean('is_run')->default(false);
            $table->integer('run_duration')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exercise_details');
    }
};
