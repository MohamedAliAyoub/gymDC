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
        'sets',
        'reps',
        'status',
        'exercise_id',
        'duration',
    ];

    /**
     * Get the exercise that the exercise details belongs to.
     */
    public function exercise(): BelongsTo
    {
        return $this->belongsTo(Exercise::class, 'exercise_id');
    }

    /**
     * Check if the exercise details is done
     *
     * @param int $id
     * @return bool
     */

    public static function is_full($rir , $tempo,$rest): bool
    {
        if (is_null($rir) || is_null($rest) || is_null($tempo))
            return false;
        return true;
    }

}
