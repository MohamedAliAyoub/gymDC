<?php

namespace App\Http\Resources\Diet;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemDetailsResource extends JsonResource
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
