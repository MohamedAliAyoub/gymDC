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
            'nutrition_coach' => $this->nutritionCoach->name,
            'workout_coach' => $this->workoutCoach->name,
            'type' => $this->type_name,
            'package' => $this->packages_name,
            'duration' => $this->duration,
            'current_duration' => 6,
            'status' => $this->status_name,
            'started_at' => $this->started_at ?? 'the coach will specify the start date',
            'paid_amount' => $this->paid_amount,
            'created_at' => $this->created_at,

        ];
    }
}
