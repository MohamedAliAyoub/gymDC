<?php

namespace App\Models\Exercise;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WeeklyPlan extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'status'];

    public function planExercises(): HasMany
    {
        return $this->hasMany(PlanExercise::class , 'weekly_plan_id' , 'id');
    }

    public function userPlanExercises(): HasMany
    {
        return $this->hasMany(UserPlanExercise::class , 'plan_id' , 'id');
    }


    public function loadPlanExercisesDetails()
    {
        return $this->planExercises->map(function ($planExercise) {
            $planExercise->load('exercises.details');
            return $planExercise;
        });
    }
}
