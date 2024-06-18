<?php

namespace App\Http\Resources\Exercise;

use App\Models\Exercise\Exercise;
use App\Models\Exercise\ExerciseDetails;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DoneExerciseResource extends JsonResource
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
            'done' => Exercise::hasDoneExerciseToday($this->id),
        ];
    }
}
