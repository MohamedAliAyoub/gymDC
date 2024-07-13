<?php

namespace App\Http\Resources\Dashboard;

use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
{

    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'nutrition_coach_id' => $this->nutritionCoach?->name,
            'workout_coach_id' => $this->workoutCoach?->name,
            'client_id' => $this->client?->name,
            'type' => $this->packages_name,
            'sale_id' => $this->sale?->name,
            'duration' => $this->duration,
            'status' => $this->status_name,
            'is_active' => $this->status == 1,
            'package' => $this->packages_name,
            'started_at' => $this->started_at?->format('Y-m-d') ?? 'the coach will specify the start date',
            'paid_amount' => $this->paid_amount,
            'freeze_start_at' => $this->freeze_start_at?->format('Y-m-d'),
            'freeze_duration' => $this->freeze_duration,
            'paid_at' => $this->paid_at?->format('Y-m-d'),
            'created_at' => $this->created_at?->format('Y-m-d'),
        ];
    }
}
