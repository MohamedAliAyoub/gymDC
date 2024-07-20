<?php

namespace App\Models\Exercise;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class ExercisePlanExercise
 *
 * @property int $plan_id
 * @property int $exercise_id
 * @property bool $status
 *
 * @package App\Models\Exercise
 */

class ExercisePlanExercise extends Model
{
    use HasFactory;

    protected $fillable = [
        'plan_exercise_id',
        'exercise_id',
        'status',
    ];
    protected $casts = [
        'status' => 'boolean',
    ];

    /**
     * Get the exercise that owns the ExercisePlanExercise
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function exercise(): BelongsTo
    {
        return $this->belongsTo(Exercise::class);
    }

    /**
     * Get the plan that owns the ExercisePlanExercise
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(PlanExercise::class);
    }
}
