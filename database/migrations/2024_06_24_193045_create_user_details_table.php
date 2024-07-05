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
        Schema::create('user_details', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('subscription_status')->default(0)->nullable();
            $table->tinyInteger('packages')->nullable();
            $table->tinyInteger('form_status')->default(0)->nullable();
            $table->tinyInteger('age')->nullable();
            $table->float('weight')->nullable();
            $table->float('height')->nullable();
            $table->tinyInteger('vib')->nullable();
            $table->foreignId('nutrition_coach_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('work_out_coach_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('in_body_image')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_details');
    }
};
