<?php

namespace App\Http\Resources\Exercise;

use App\Models\Exercise\DoneExercise;
use App\Models\Exercise\ExerciseDetails;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExerciseDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
//        TODO  change datatypes to float
        return [
            'id' => $this->id,
            'exercise_id' => $this->exercise_id,
            'name' => $this->name,
            'exercise' => $this->exercise->name,
            'is_done' => DoneExercise::hasDoneExerciseDetailsToday($this->id),
            'sets' => $this->sets,
            'rir' => $this->rir,
            'reps' => $this->reps,
            'rest' => $this->rest,
            'weight' => $this->weight,
            'unit' => $this->unit,
            'day_names' => $this->day_names,
            'is_full' => ExerciseDetails::is_full($this->rir, $this->tempo, $this->rest),
            'duration' => $this->duration,

        ];
    }
}
