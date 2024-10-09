<?php

namespace App\Http\Resources\Diet;

use App\Models\Diet\Meal;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MealResource extends JsonResource
{
    /**
     * @OA\Schema(
     *     schema="MealResource",
     *     type="object",
     *     @OA\Property(property="id", type="integer", example=37),
     *     @OA\Property(property="name", type="string", example="Meal 1"),
     *     @OA\Property(property="calories", type="number", format="float", example=250),
     *     @OA\Property(property="fat", type="number", format="float", example=10),
     *     @OA\Property(property="carbohydrate", type="number", format="float", example=30),
     *     @OA\Property(property="protein", type="number", format="float", example=20),
     *     @OA\Property(property="items_count", type="integer", example=2),
     *     @OA\Property(property="note", type="string", example="this note is optional called meal note"),
     *     @OA\Property(
     *         property="items",
     *         type="array",
     *         @OA\Items(ref="#/components/schemas/ItemResource")
     *     ),
     *     @OA\Property(property="is_eaten", type="boolean", example=false)
     * )
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
            'is_eaten' => $this->is_eaten_done,
            'count_done_calories' => $this->count_done_calories,
            'items' => ItemResource::collection($this->items),
        ];
    }
}
