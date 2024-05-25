<?php

namespace App\Models\Exercise;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class DoneExercise
 *
 * @property int $plan_id
 * @property int $user_id
 * @property int $exercise_id
 * @property int $exercise_details_id
 *
 * @package App\Models\Exercise
 */
class DoneExercise extends Model
{
    use HasFactory;
    protected $fillable = [
        'plan_id',
        'user_id',
        'exercise_id',
        'exercise_details_id',

    ];

    /**
     * Get the plan that the user plan exercise belongs to.
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(PlanExercise::class, 'plan_id');
    }
    /**
     * Get the user that the user plan exercise belongs to.
     */
    public function exercise(): BelongsTo
    {
        return $this->belongsTo(Exercise::class, 'exercise_id');
    }
    /**
     * Get the exercise details that the user plan exercise belongs to.
     */
    public function exerciseDetails(): BelongsTo
    {
        return $this->belongsTo(ExerciseDetails::class, 'exercise_details_id');
    }
    /**
     * Get the done for the user plan exercise.
     */

    public function doneExercise()
    {
        return $this->hasOne(DoneExercise::class);
    }
}

