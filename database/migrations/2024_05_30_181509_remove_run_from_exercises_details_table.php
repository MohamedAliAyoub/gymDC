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
        Schema::table('exercises_details', function (Blueprint $table) {
            $table->dropColumn('is_run');
            $table->dropColumn('run_duration');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exercises_details', function (Blueprint $table) {
            $table->boolean('is_run')->default(false);
            $table->integer('run_duration')->nullable();
        });
    }
};
