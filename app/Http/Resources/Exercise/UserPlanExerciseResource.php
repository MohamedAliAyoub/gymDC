<?php

namespace App\Http\Resources\Exercise;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserPlanExerciseResource extends JsonResource
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
            'plan_id' => $this->plan_id,
            'user_id' => $this->user_id,
            'status' => $this->status,
            'is_work' => $this->is_work,
            'days' => $this->days,
            'plan' => $this->plan->name,
            'user' => $this->user->name,
            'day_names' => $this->day_names,
            'notes' => $this->notes->content,
        ];
    }
}
