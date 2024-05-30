<?php

namespace App\Http\Resources\Exercise;

use App\Models\Exercise\Exercise;
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
            'status' => $this->status,
            'done' => Exercise::hasDoneExerciseToday($this->id),
            'details' => ExerciseDetailsResource::collection($this->details),
            'note' => $this->note,

        ];
    }
}
