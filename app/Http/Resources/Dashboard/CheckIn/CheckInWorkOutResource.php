<?php

namespace App\Http\Resources\Dashboard\CheckIn;

use Illuminate\Http\Resources\Json\JsonResource;

class CheckInWorkOutResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'user_id' => $this->user_id,
            'training_in_last_period' => $this->training_in_last_period,
            'progress_in_wight' => $this->progress_in_wight,
            'training_number_suitable' => $this->training_number_suitable,
            'training_intensity_suitable' => $this->training_intensity_suitable,
            'degree_of_muscle' => $this->degree_of_muscle,
            'exercise_cause_pain' => $this->exercise_cause_pain,
            'notes' => $this->notes,
        ];
    }
}
