<?php

namespace App\Models\Exercise;

use App\Models\User;
use Carbon\Carbon;
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
        'rir',
        'tempo',
        'rest',
        'kg',
        'reps',
        'status',
        'is_run',
        'run_duration',

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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function doneToday()
    {
        return $this->where('user_id', auth()->id())
            ->whereDate('created_at', Carbon::today());
    }

    public function isPlanIdInDoneExercise(int $planId): bool
    {
        return $this->doneToday()->where('plan_id', $planId)->exists();
    }

    public function isExerciseIdInDoneExercise(int $exerciseId): bool
    {
        return $this->doneToday()->where('exercise_id', $exerciseId)->exists();
    }

    public function isExerciseDetailsIdInDoneExercise(int $exerciseDetailsId): bool
    {
        return $this->doneToday()->where('exercise_details_id', $exerciseDetailsId)->exists();
    }
}

