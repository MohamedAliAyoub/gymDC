<?php

namespace App\Models\Exercise;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WeeklyPlan extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'status', 'client_id', 'is_work' , 'note'];


    protected $appends = ['is_work'];
    protected $casts = [
        'status' => 'boolean',
        'is_work' => 'boolean',
    ];


    public function planExercises(): HasMany
    {
        return $this->hasMany(PlanExercise::class, 'weekly_plan_id', 'id');
    }

    public function userPlanExercises(): HasMany
    {
        return $this->hasMany(UserPlanExercise::class, 'plan_id', 'id');
    }


    public function loadPlanExercisesDetails()
    {
        return $this->planExercises->map(function ($planExercise) {
            $planExercise->load('exercises.details');
            return $planExercise;
        });
    }

    public function userPlanExercise(): HasMany
    {
        return $this->hasMany(UserPlanExercise::class, 'weekly_plan_id', 'id');
    }


}
