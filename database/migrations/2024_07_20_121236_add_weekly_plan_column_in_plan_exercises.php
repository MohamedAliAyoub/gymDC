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
        Schema::table('plan_exercises', function (Blueprint $table) {
            if (!Schema::hasColumn('plan_exercises', 'weekly_plan_id')) {
                $table->foreignId('weekly_plan_id')->nullable()->after('id')->constrained('weekly_plans')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plan_exercises', function (Blueprint $table) {
            $table->dropForeign(['weekly_plan_id']);
            $table->dropColumn('weekly_plan_id');
        });
    }
};
