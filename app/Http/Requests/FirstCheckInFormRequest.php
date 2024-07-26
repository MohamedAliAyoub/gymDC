<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FirstCheckInFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'height' => 'nullable|numeric',
            'weight' => 'nullable|numeric',
            'age' => 'nullable|integer',
            'gender' => 'nullable|boolean',
            'activity_level' => 'nullable|boolean',
            'in_body' => 'nullable|string',
            'target_for_join' => 'nullable|boolean',
            'job' => 'nullable|string',
            'play_another_sport' => 'nullable|boolean',
            'health_problems' => 'nullable|boolean',
            'medical_analysis' => 'nullable|boolean',
            'medications' => 'nullable|string',
            'injuries_surgeries' => 'nullable|boolean',
            'regular_sport' => 'nullable|string',
            'smoker' => 'nullable|boolean',
            'diet_before' => 'nullable|boolean',
            'family_support_you' => 'nullable|boolean',
            'past_diet_experience' => 'nullable|string',
            'food_you_dont_like' => 'nullable|string',
            'main_mails_number' => 'nullable|integer',
            'many_mails_number_you_want' => 'nullable|integer',
            'available_budget' => 'nullable|boolean',
            'rate_appetite' => 'nullable|integer',
            'use_vitamins_or_minerals' => 'nullable|boolean',
            'use_nutritional_supplements' => 'nullable|boolean',
            'have_injuries' => 'nullable|boolean',
            'injuries_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'resistance_training' => 'nullable|boolean',
            'where_do_workout' => 'nullable|boolean',
            'available_tool_in_home' => 'nullable|string',
            'days_number_for_exercise' => 'nullable|integer',
            'exercise_you_dont_like' => 'nullable|boolean',
            'favorite_cardio' => 'nullable|boolean',
            'daily_steps' => 'nullable|integer',
            'previous_experience_online_coaching' => 'nullable|boolean',
            'subscribe_reason' => 'nullable|string',
            'notes' => 'nullable|string',
            'body_images' => 'nullable|array',
            'body_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }
}



