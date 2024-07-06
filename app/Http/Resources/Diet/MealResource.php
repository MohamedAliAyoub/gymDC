<?php

namespace App\Http\Resources\Diet;

use App\Models\Diet\Meal;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MealResource extends JsonResource
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
            'calories' => $this->calories,
            'fat' => $this->fat,
            'carbohydrate' => $this->carbohydrate,
            'protein' => $this->protein,
            'items_count' => $this->items->count(),
            'note' => $this->note->content ?? null,
            'items' => ItemResource::collection($this->items),
            'is_eaten' => Meal::hasEatenMealToday($this->id)  ,
        ];
    }
}
