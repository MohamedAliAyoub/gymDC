<?php

namespace App\Models\Exercise;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class ExerciseDetails
 *
 * @property string $name
 * @property string $previous
 * @property string $rir
 * @property string $tempo
 * @property string $rest
 * @property string $kg
 * @property string $reps
 * @property bool $status
 * @property int $exercise_id
 * @package App\Models\Exercise
 */
class ExerciseDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'previous',
        'rir',
        'tempo',
        'rest',
        'kg',
        'reps',
        'status',
        'exercise_id',
    ];

    /**
     * Get the exercise that the exercise details belongs to.
     */
    public function exercise(): BelongsTo
    {
        return $this->belongsTo(Exercise::class, 'exercise_id');
    }

    /**
     * Get the done exercise that the exercise details belongs to.
     */
    public function doneExercise()
    {
        return $this->hasMany(DoneExercise::class, 'exercise_details_id', 'id');
    }


    /**
     * Check if the user has done the exercise details today
     *
     * @param $detailsId
     * @return bool
     */
    public static function Done($detailsId): bool
    {
        return DoneExercise::query()
            ->where('exercise_details_id', $detailsId)
            ->where('user_id', auth()->id())
            ->whereDate('created_at', Carbon::today())
            ->exists();
    }


}
