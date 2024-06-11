<?php

namespace App\Http\Resources\Exercise;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlanExerciseResource extends JsonResource
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
            'plan_id' => $this->name,
            'status' => $this->status,
            'exercises_count' => $this->exercises->whereNull('run_duration')->count(),
            'exercises' => ExerciseResource::collection($this->exercises->whereNull('run_duration')) ?? [],
            'run' =>  $this->run->where('run_duration' , '!=' , null)->first() ?? [],
            'note' => $this->note,
            'done' => $this->done,
        ];
    }
}
