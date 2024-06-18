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
        'user_id',
        'exercise_id',
        'exercise_details_id',
        'plan_id',
        'rir',
        'sets',
        'tempo',
        'rest',
        'kg',
        'reps',
        'status',
        'is_run',
        'run_duration',
        'is_done'
    ];
    protected $casts = [
        'is_done' => 'boolean',
        'is_run' => 'boolean',
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
            ->where('is_done', true)
            ->whereDate('created_at', Carbon::today());
    }

    /**
     * Check if the user has done the exercise today
     *
     * @param $exerciseDetailsId
     * @return bool
     */
    public static function hasDoneExerciseDetailsToday($exerciseDetailsId): bool
    {
        return DoneExercise::query()
            ->where('user_id', auth()->id())
            ->where('exercise_details_id', $exerciseDetailsId)
            ->where('is_done', true)
            ->whereDate('created_at', now()->toDateString())
            ->exists();
    }

    /**
     * Check if the user has done the exercise today
     *
     * @param $planId
     * @return bool
     */
    public static  function hasDonePlanToday($planId): bool
    {
        return DoneExercise::query()
            ->where('user_id', auth()->id())
            ->where('plan_id', $planId)
            ->where('is_done', true)
            ->whereDate('created_at', now()->toDateString())
            ->exists();
    }

    /**
     * Check if the user has done the exercise today
     *
     * @param $exerciseId
     * @return bool
     */
    public static function hasDoneExerciseToday($exerciseId): bool
    {
        return DoneExercise::query()
            ->where('user_id', auth()->id())
            ->where('exercise_id', $exerciseId)
            ->where('is_done', true)
            ->whereDate('created_at', now()->toDateString())
            ->exists();
    }
}

