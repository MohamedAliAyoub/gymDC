<?php

namespace App\Models\Exercise;

use App\Models\Diet\Note;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class PlanExercise
 *
 * @property string $name
 * @property bool $status
 * @property int $weekly_plan_id
 * @property Exercise[] $exercises
 * @property Exercise[] $run
 * @property Note $note
 * @property bool $done
 * @property bool $rest_day
 * @property bool $is_done
 *
 * @package App\Models\Exercise
 */
class PlanExercise extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
        'weekly_plan_id'
    ];
    protected $hidden = [
        'pivot'
    ];


    /**
     * Get the weekly plan that owns the plan.
     */
    public function weeklyPlan():belongsTo
    {
        return $this->belongsTo(WeeklyPlan::class);
    }
    /**
     * Get the exercises for the plan.
     */
    public function exercises():BelongsToMany
    {
        return $this->belongsToMany(Exercise::class, ExercisePlanExercise::class);
    }

    /**
     * Get the run for the plan.
     */
    public function run()
    {
        return $this->belongsToMany(Exercise::class, ExercisePlanExercise::class);
    }

    /**
     * Get the note for the plan.
     */
    public function note():HasOne
    {
        return $this->hasOne(NoteExercise::class);
    }

    public static function hasDoneExerciseToday($exerciseId): bool
    {
        return DoneExercise::query()
            ->where('user_id', auth()->id())
            ->where('exercise_id', $exerciseId)
            ->whereDate('created_at', now()->toDateString())
            ->exists();
    }




}
