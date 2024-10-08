<?php

namespace App\Http\Resources\Dashboard;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientSubscriptionsResource extends JsonResource
{


    public function toArray($request): array
    {
        $user = auth()->user();
        return [
            'id' => $this->id,
            'nutrition_coach' => $this->nutritionCoach->name ?? 'the coach will specify the nutrition coach',
            'workout_coach' => $this->workoutCoach->name ?? 'the coach will specify the workout coach',
            'type' => $this->packages_name,
            'package' => $this->packages_name,
            'duration' => $this->duration,
            'current_duration' => 6, //todo import from the model
            'status' => $this->status_name,
            'is_active' => $this->status == 1,
            'started_at' => $this->started_at?->format('Y-M-d') ?? 'the coach will specify the start date',
            'paid_amount' => $this->paid_amount,
            'paid_at' => $this->paid_at?->format('Y-M-d'),
            'freeze_start_at' => $this->freeze_start_at?->format('Y-M-d'),
            'freeze_duration' => $this->freeze_duration,
            'created_at' => $this->created_at?->format('Y-M-d'),

        ];
    }
}
