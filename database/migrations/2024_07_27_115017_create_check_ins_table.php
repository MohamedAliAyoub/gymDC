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
        Schema::create('check_ins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('committed_nutrition_plan')->nullable();
            $table->string('weight')->nullable();
            $table->string('in_body_image')->nullable();
            $table->float('chest_measurement')->nullable();
            $table->float('stomach_measurement')->nullable();
            $table->float('waist_measurement')->nullable();
            $table->float('hips_measurement')->nullable();
            $table->float('thigh_measurement')->nullable();
            $table->float('claves_measurement')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('check_ins');
    }
};
