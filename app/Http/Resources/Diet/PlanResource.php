<?php

namespace App\Http\Resources\Diet;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlanResource extends JsonResource
{
    /**
     * @OA\Schema(
     *     schema="PlanResource",
     *     type="object",
     *     @OA\Property(property="id", type="integer", example=27),
     *     @OA\Property(property="name", type="string", example="Plan Name"),
     *     @OA\Property(property="total_calories", type="number", format="float", example=500),
     *     @OA\Property(property="total_carbohydrate", type="number", format="float", example=60),
     *     @OA\Property(property="total_protein", type="number", format="float", example=40),
     *     @OA\Property(property="total_fat", type="number", format="float", example=20),
     *     @OA\Property(property="notes", type="string", example="this note is optional called plan note"),
     *     @OA\Property(
     *         property="meals",
     *         type="array",
     *         @OA\Items(ref="#/components/schemas/MealResource")
     *     )
     * )
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'created_at' => $this->created_at->format('Y-m-d'),
            'total_calories' => $this->meals->sum('calories'),
            'total_carbohydrate' => $this->meals->sum('carbohydrate'),
            'total_protein' => $this->meals->sum('protein'),
            'total_fat' => $this->meals->sum('fat'),
            'is_work' => $this->is_work != null ? $this->is_work : false,
            'notes' => $this->note?->content,
            'meals' => MealResource::collection($this->meals),

        ];
    }
}
