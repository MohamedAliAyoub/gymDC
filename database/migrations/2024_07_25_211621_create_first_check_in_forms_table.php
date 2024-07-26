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
        Schema::create('first_check_in_forms', function (Blueprint $table) {
            $table->id();
            $table->float('height')->nullable();
            $table->float('weight')->nullable();
            $table->integer('age')->nullable();
            $table->boolean('gender')->nullable();
            $table->boolean('activity_level')->nullable();
            $table->string('in_body')->nullable();
            $table->boolean('target_for_join')->nullable();
            $table->string('job')->nullable();
            $table->boolean('play_another_sport')->nullable();
            $table->boolean('health_problems')->nullable();
            $table->boolean('medical_analysis')->nullable();
            $table->string('medications')->nullable();
            $table->boolean('injuries_surgeries')->nullable();
            $table->string('regular_sport')->nullable();
            $table->boolean('smoker')->nullable();
            $table->boolean('diet_before')->nullable();
            $table->boolean('family_support_you')->nullable();
            $table->text('past_diet_experience')->nullable();
            $table->text('food_you_dont_like')->nullable();
            $table->tinyInteger('main_mails_number')->nullable();
            $table->tinyInteger('many_mails_number_you_want')->nullable();
            $table->boolean('available_budget')->nullable();
            $table->tinyInteger('rate_appetite')->nullable();
            $table->boolean('use_vitamins_or_minerals')->nullable();
            $table->boolean('use_nutritional_supplements')->nullable();
            $table->boolean('have_injuries')->nullable();
            $table->boolean('injuries_image')->nullable();
            $table->boolean('resistance_training')->nullable();
            $table->boolean('where_do_workout')->nullable();
            $table->text('available_tool_in_home')->nullable();
            $table->tinyInteger('days_number_for_exercise')->nullable();
            $table->boolean('exercise_you_dont_like')->nullable();
            $table->boolean('favorite_cardio')->nullable();
            $table->integer('daily_steps')->nullable();
            $table->boolean('previous_experience_online_coaching')->nullable();
            $table->text('subscribe_reason')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('user_id')->constrained('users');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('frist_check_in_forms');
    }
};
