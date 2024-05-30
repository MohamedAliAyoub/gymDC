<?php

namespace App\Http\Resources\Exercise;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NoteExerciseResource extends JsonResource
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
            'title' => $this->title,
            'content' => $this->content
        ];
    }
}
