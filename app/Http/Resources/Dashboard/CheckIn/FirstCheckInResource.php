<?php

namespace App\Http\Resources\Dashboard\CheckIn;

use Illuminate\Http\Resources\Json\JsonResource;

class FirstCheckInResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'height' => $this->height ,
            'weight' => $this->weight,
            'age' => $this->age,
            'gender' => $this->gender,
            'activity_level' => $this->activity_level,
            'in_body' => $this->in_body,
            'target_for_join' => $this->target_for_join,
            'job' => $this->job,
            'play_another_sport' => $this->play_another_sport,
            'health_problems' => $this->health_problems,
            'medical_analysis' => $this->medical_analysis,
            'medications' => $this->medications,
            'injuries_surgeries' => $this->injuries_surgeries,
            'regular_sport' => $this->regular_sport,
            'smoker' => $this->smoker,
            'diet_before' => $this->diet_before,
            'family_support_you' => $this->family_support_you,
            'past_diet_experience' => $this->past_diet_experience,
            'food_you_dont_like' => $this->food_you_dont_like,
            'main_mails_number' => $this->main_mails_number,
            'many_mails_number_you_want' => $this->many_mails_number_you_want,
            'available_budget' => $this->available_budget,
            'rate_appetite' => $this->rate_appetite,
            'use_vitamins_or_minerals' => $this->use_vitamins_or_minerals,
            'use_nutritional_supplements' => $this->use_nutritional_supplements,
            'have_injuries' => $this->have_injuries,
            'injuries_image' => $this->injuries_image_url,
            'resistance_training' => $this->resistance_training,
            'where_do_workout' => $this->where_do_workout,
            'available_tool_in_home' => $this->available_tool_in_home,
            'days_number_for_exercise' => $this->days_number_for_exercise,
            'exercise_you_dont_like' => $this->exercise_you_dont_like,
            'favorite_cardio' => $this->favorite_cardio,
            'daily_steps' => $this->daily_steps,
            'previous_experience_online_coaching' => $this->previous_experience_online_coaching,
            'subscribe_reason' => $this->subscribe_reason,
            'notes' => $this->notes,
            'body_images' => $this->bodyImages ? BodyImageResource::collection($this->bodyImages) : [],
            'created_at' => $this->created_at->format('Y-M-d'),

        ];
    }
}
