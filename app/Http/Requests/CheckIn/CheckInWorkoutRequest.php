<?php

namespace App\Http\Requests\CheckIn;

use Illuminate\Foundation\Http\FormRequest;

class CheckInWorkoutRequest extends FormRequest
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
            'training_in_last_period' => 'nullable|string|max:255',
            'progress_in_wight' => 'nullable|string|max:255',
            'training_number_suitable' => 'nullable|boolean',
            'training_intensity_suitable' => 'nullable|string|max:255',
            'degree_of_muscle' => 'nullable|integer',
            'exercise_cause_pain' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:255',
        ];
    }
}
