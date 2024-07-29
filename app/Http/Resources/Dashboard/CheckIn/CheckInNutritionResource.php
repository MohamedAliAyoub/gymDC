<?php

namespace App\Http\Resources\Dashboard\CheckIn;

use Illuminate\Http\Resources\Json\JsonResource;

class CheckInNutritionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'committed_nutrition_plan' => $this->committed_nutrition_plan,
            'weight' => $this->weight,
            'in_body_image' => $this->in_body_image_url,
            'chest_measurement' => $this->chest_measurement,
            'stomach_measurement' => $this->stomach_measurement,
            'waist_measurement' => $this->waist_measurement,
            'hips_measurement' => $this->hips_measurement,
            'thigh_measurement' => $this->thigh_measurement,
            'claves_measurement' => $this->claves_measurement,
            'notes' => $this->notes,
            'body_images' => $this->bodyImages ? BodyImageResource::collection($this->bodyImages) : [],

        ];
    }
}
