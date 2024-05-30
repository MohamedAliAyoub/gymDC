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
        Schema::create('note_exercises', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained()->cascadeOnDelete();
            $table->string("title")->nullable();
            $table->text("content");
            $table->boolean("status")->default(true);
            $table->foreignId("plan_exercise_id")->nullable()->constrained('plan_exercises')->cascadeOnDelete();
            $table->foreignId("exercise_id")->nullable()->constrained('exercisesphp artisan make:model PlanExercise')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('note_exercises');
    }
};
