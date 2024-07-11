<?php

namespace App\Http\Resources\Diet;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
{
    /**
     * @OA\Schema(
     *     schema="ItemResource",
     *     type="object",
     *     @OA\Property(property="id", type="integer", example=40),
     *     @OA\Property(property="name", type="string", example="Item 1"),
     *     @OA\Property(property="calories", type="number", format="float", example=0),
     *     @OA\Property(property="type", type="string", example="recipe"),
     *     @OA\Property(property="standard_name", type="string", example="1"),
     *     @OA\Property(property="number", type="integer", example=1),
     *     @OA\Property(property="carbohydrate", type="number", format="float", example=30),
     *     @OA\Property(property="fat", type="number", format="float", example=10),
     *     @OA\Property(property="protein", type="number", format="float", example=20),
     *     @OA\Property(
     *         property="item_details",
     *         type="array",
     *         @OA\Items(ref="#/components/schemas/ItemDetailResource")
     *     ),
     *     @OA\Property(property="has_details", type="boolean", example=true)
     * )
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name ?? null,
            'calories' => $this->calories,
            'type' => $this->type_label,
            'standard_name' => $this->standard ? $this->standard->number . ' ' . $this->standard->load('standardType')->standardType?->name . ' ' . $this->standard->name : null,
            'number' => $this->standard->number ?? 0,
            'carbohydrate' => $this->standard->carbohydrate ?? $this->itemDetails->sum(function ($itemDetail) {
                    return $itemDetail->standard->carbohydrate ?? 0;
                }),
            'fat' => $this->standard->fat ?? $this->itemDetails->sum(function ($itemDetail) {
                    return $itemDetail->standard->fat ?? 0;
                }),
            'protein' => $this->standard->protein ?? $this->itemDetails->sum(function ($itemDetail) {
                    return $itemDetail->standard->protein ?? 0;
                }),
            'item_details' => ItemDetailsResource::collection($this->itemDetails),
            'has_details' => $this->itemDetails->count() > 0 ? true : false,


        ];
    }
}
