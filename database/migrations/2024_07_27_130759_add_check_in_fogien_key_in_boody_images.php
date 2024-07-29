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
        Schema::table('body_images', function (Blueprint $table) {
            $table->foreignId('check_in_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('first_check_in_form_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('body_images', function (Blueprint $table) {
            //
        });
    }
};
