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
        Schema::table('done_exercises', function (Blueprint $table) {
            $table->foreignId('exercise_details_id')->nullable();
            $table->foreignId('plan_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('done_exercises', function (Blueprint $table) {

            $table->dropForeign(['exercise_details_id' ]);
            $table->dropForeign(['plan_id' ]);
            $table->dropColumn('exercise_details_id');
            $table->dropColumn('plan_id');
        });
    }
};
