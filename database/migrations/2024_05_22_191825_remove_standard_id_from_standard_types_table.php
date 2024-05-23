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
        Schema::table('standard_types', function (Blueprint $table) {
            $table->dropForeign(['standard_id']);
            $table->dropColumn('standard_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('standard_types', function (Blueprint $table) {
            $table->foreignId('standard_id')->constrained()->cascadeOnDelete();
        });
    }
};
