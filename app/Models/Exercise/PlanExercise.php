<?php

namespace App\Models\Exercise;

use App\Models\Diet\Note;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class PlanExercise
 *
 * @property string $name
 * @property bool $status
 *
 * @package App\Models\Exercise
 */
class PlanExercise extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
    ];
    protected $hidden = [
        'pivot'
    ];


    /**
     * Get the exercises for the plan.
     */
    public function exercises():BelongsToMany
    {
        return $this->belongsToMany(Exercise::class, 'plan_exercises');
    }

    /**
     * Get the note for the plan.
     */
    public function note():HasOne
    {
        return $this->hasOne(NoteExercise::class);
    }

}
