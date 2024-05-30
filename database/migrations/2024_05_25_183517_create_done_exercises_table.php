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
        Schema::create('done_exercises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('exercise_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('exercise_details_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('plan_id')->nullable()->constrained()->cascadeOnDelete();
            $table->tinyInteger('rir')->nullable();
            $table->string('tempo')->nullable();
            $table->tinyInteger('rest')->nullable();
            $table->tinyInteger('kg')->nullable();
            $table->tinyInteger('reps')->nullable();
            $table->boolean('status')->default(false);
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
        Schema::dropIfExists('done_exercises');
    }
};
