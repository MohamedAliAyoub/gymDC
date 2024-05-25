<?php

namespace App\Models\Exercise;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NoteExercise extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'status',
        'exercise_plan_id',
        'exercise_id',
    ];

    /**
     * Get the exercise that the note exercise belongs to.
     */
    public function exercise(): BelongsTo
    {
        return $this->belongsTo(Exercise::class, 'exercise_id');
    }

    /**
     * Get the exercise plan that the note exercise belongs to.
     */
    public function exercisePlan(): BelongsTo
    {
        return $this->belongsTo(PlanExercise::class, 'exercise_plan_id');
    }
}
