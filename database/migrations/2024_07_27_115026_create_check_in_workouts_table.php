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
        Schema::create('check_in_workouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('training_in_last_period')->nullable();
            $table->string('progress_in_wight')->nullable();
            $table->boolean('training_number_suitable')->nullable();
            $table->string('training_intensity_suitable')->nullable();
            $table->integer('degree_of_muscle')->nullable();
            $table->string('exercise_cause_pain')->nullable();
            $table->string('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('check_in_workouts');
    }
};
