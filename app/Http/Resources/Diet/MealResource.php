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
            'items_count' => $this->items->count(),
            'items' => ItemResource::collection($this->items),
            'is_eaten' => Meal::hasEatenMealToday($this->id),
            'note' => $this->note,
        ];
    }
}
