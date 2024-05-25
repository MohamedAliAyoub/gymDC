<?php

namespace App\Models\Exercise;

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
 * @property bool $is_run
 * @property string $run_duration
 *
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
        'is_run',
        'run_duration',
    ];

    /**
     * Get the exercise that the exercise details belongs to.
     */
    public function exercise(): BelongsTo
    {
        return $this->belongsTo(Exercise::class, 'exercise_id');
    }


}
