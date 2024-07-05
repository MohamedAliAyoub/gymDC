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
        return $this->hasMany(PlanExercise::class);
    }

}
