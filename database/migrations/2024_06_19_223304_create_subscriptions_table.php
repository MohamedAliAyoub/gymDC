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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nutrition_coach_id')->nullable()->constrained('users');
            $table->foreignId('workout_coach_id')->nullable()->constrained('users');
            $table->foreignId('client_id')->constrained('users');
            $table->foreignId('sale_id')->nullable()->constrained('users');
            $table->integer('duration')->nullable();
            $table->tinyInteger('type')->nullable();
            $table->date('started_at')->nullable();
            $table->decimal('paid_amount', 8, 2)->nullable();
            $table->date('freeze_start_at')->nullable();
            $table->integer('freeze_duration')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->tinyInteger('status')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
