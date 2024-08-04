<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyToUserPlanExercisesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_plan_exercises', function (Blueprint $table) {
            $table->dropForeign(['plan_id']);
            $table->foreign('plan_id')->references('id')->on('plan_exercises')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_plan_exercises', function (Blueprint $table) {
            $table->dropForeign(['plan_id']);
        });
    }
}
