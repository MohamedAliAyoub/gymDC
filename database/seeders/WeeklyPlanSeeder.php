<?php

namespace Database\Seeders;

use App\Models\Exercise\WeeklyPlan;
use Illuminate\Database\Seeder;

class WeeklyPlanSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 0; $i < 10; $i++) {
            WeeklyPlan::create([
                'name' => 'Weekly Plan ' . $i,
                'status' => 1
            ]);
        }
    }
}
