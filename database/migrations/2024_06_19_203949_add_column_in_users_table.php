<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->tinyInteger('subscription_status')->default(0)->nullable()->after('email_verified_at');
            $table->tinyInteger('packages')->nullable()->after('email_verified_at');
            $table->tinyInteger('form_status')->default(0)->nullable()->after('email_verified_at');
            $table->tinyInteger('age')->nullable()->after('email_verified_at');
            $table->float('weight')->nullable()->after('email_verified_at');
            $table->float('height')->nullable()->after('email_verified_at');
            $table->tinyInteger('vib')->nullable()->after('email_verified_at');
            $table->foreignId('nutrition_coach_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('work_out_coach_id')->nullable()->constrained('users')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'subscription_status',
                'packages',
                'form_status',
                'age',
                'weight',
                'height',
                'vib'
            ]);
        });
    }
};
