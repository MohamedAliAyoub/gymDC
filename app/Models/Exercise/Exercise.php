<?php

namespace App\Models\Exercise;

use App\Models\Diet\Note;
use App\Models\Diet\Plan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Exercise
 *
 * @property string $name
 * @property bool $status
 *
 * @package App\Models\Exercise
 */
class Exercise extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'run_duration',
        'status',
    ];
    protected $hidden = [
        'pivot'
    ];

    /**
     * Get the plan that the meal belongs to.
     */
    public function plan():BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Get the note for the exercise.
     */
    public function note()
    {
        return $this->hasOne(NoteExercise::class);
    }

  /**
   * get exercise details
   */
    public function details(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ExerciseDetails::class);
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
            ->whereDate('created_at', now()->toDateString())
            ->exists();
    }


}
