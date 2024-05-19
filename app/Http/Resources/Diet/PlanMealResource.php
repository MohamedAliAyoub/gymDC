<?php

namespace App\Http\Resources\Diet;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlanMealResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return[
            'id' => $this->id,
            'plan_id' => $this->plan_id,
            'meal_id' => $this->meal_id,
            'plane' => $this->plan->name,
            'meal' => $this->meal->name,
            'status' => $this->status,
        ];
    }
}
