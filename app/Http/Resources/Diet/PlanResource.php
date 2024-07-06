<?php

namespace App\Http\Resources\Diet;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlanResource extends JsonResource
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
            'total_calories' => $this->meals->sum('calories'),
            'total_carbohydrate' => $this->meals->sum('carbohydrate'),
            'total_protein' => $this->meals->sum('protein'),
            'total_fat' => $this->meals->sum('fat'),
            'notes' => $this->note?->content,
            'meals' => MealResource::collection($this->meals),

        ];
    }
}
