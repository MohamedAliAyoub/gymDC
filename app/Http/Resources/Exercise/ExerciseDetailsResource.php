<?php

namespace App\Http\Resources\Exercise;

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
        return [
            'id' => $this->id,
            'exercise_id' => $this->exercise_id,
            'exercise' => $this->exercise->name,
            'sets' => $this->sets,
            'reps' => $this->reps,
            'rest' => $this->rest,
            'weight' => $this->weight,
            'unit' => $this->unit,
            'day_names' => $this->day_names,
            'done' => ExerciseDetails::done($this->id),
            'is_full' => ExerciseDetails::is_full($this->rir , $this->tempo , $this->rest)

        ];
    }
}
