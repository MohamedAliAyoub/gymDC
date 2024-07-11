<?php

namespace App\Http\Resources\Diet;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemDetailsResource extends JsonResource
{
    /**
     * @OA\Schema(
     *     schema="ItemDetailResource",
     *     type="object",
     *     @OA\Property(property="id", type="integer", example=50),
     *     @OA\Property(property="item_id", type="integer", example=40),
     *     @OA\Property(property="item", type="string", example="Item Name"),
     *     @OA\Property(property="name", type="string", example="Detail Name"),
     *     @OA\Property(property="calories", type="number", format="float", example=0),
     *     @OA\Property(property="standard_name", type="string", example="Standard Name"),
     *     @OA\Property(property="carbohydrate", type="number", format="float", example=30),
     *     @OA\Property(property="protein", type="number", format="float", example=20),
     *     @OA\Property(property="fat", type="number", format="float", example=10)
     * )
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'item_id' => $this->item_id,
            'item' => $this->item->name,
            'name' => $this->name,
            'calories' => $this->calories,
            'standard_name' => $this->standard->name ?? null,
            'carbohydrate' => $this->standard->carbohydrate ?? null,
            'protein' => $this->standard->protein ?? null,
            'fat' => $this->standard->fat ?? null,


        ];
    }
}
