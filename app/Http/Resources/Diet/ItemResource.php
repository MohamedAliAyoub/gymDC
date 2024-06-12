<?php

namespace App\Http\Resources\Diet;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
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
            'name' => $this->name ?? null,
            'type' => $this->type_label,
            'standard_name' => $this->standard->name .' '.$this->standard->load('standardType')->standardType?->name ,
            'carbohydrate' => $this->standard->carbohydrate?? $this->itemDetails->sum(function ($itemDetail) {
                return $itemDetail->standard->carbohydrate ?? 0;
            }),
            'fat' => $this->standard->fat?? $this->itemDetails->sum(function ($itemDetail) {
                return $itemDetail->standard->fat ?? 0;
            }),
            'protein' => $this->standard->protein?? $this->itemDetails->sum(function ($itemDetail) {
                return $itemDetail->standard->protein ?? 0;
            }),
            'item_details' => ItemDetailsResource::collection($this->itemDetails),
            'has_details' => $this->itemDetails->count() > 0 ? true : false,


        ];
    }
}
