<?php

namespace App\Http\Resources\Exercise;

use App\Models\Exercise\DoneExercise;
use App\Models\Exercise\Exercise;
use App\Models\Exercise\ExerciseDetails;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExerciseResource extends JsonResource
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
            'name' => $this->name,
            'is_done' => DoneExercise::hasDoneExerciseToday($this->id),
            'status' => $this->status,
            'details' => ExerciseDetailsResource::collection($this->details),
            'note' => $this->note->content ?? null,
        ];
    }
}
