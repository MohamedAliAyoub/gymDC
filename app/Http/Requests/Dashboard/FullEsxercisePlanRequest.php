<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class FullEsxercisePlanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'weekly_plan_id' => 'nullable|integer',
            'weekly_plan_name' => 'required|string',
            'plans' => 'required|array',
            'plans.*.id' => 'nullable|integer',
            'plans.*.name' => 'required|string',
            'plans.*.note' => 'nullable|string',
            'plans.*.exercises' => 'required|array',
            'plans.*.exercises.*.id' => 'nullable|integer',
            'plans.*.exercises.*.name' => 'required|string',
            'plans.*.exercises.*.note' => 'nullable|string',
            'plans.*.exercises.*.run_duration' => 'nullable|integer',
            'plans.*.exercises.*.exercise_details' => 'required|array',
            'plans.*.exercises.*.exercise_details.*.id' => 'nullable|integer',
            'plans.*.exercises.*.exercise_details.*.name' => 'required|string',
            'plans.*.exercises.*.exercise_details.*.previous' => 'required|string',
            'plans.*.exercises.*.exercise_details.*.rir' => 'required|string',
            'plans.*.exercises.*.exercise_details.*.tempo' => 'required|string',
            'plans.*.exercises.*.exercise_details.*.rest' => 'required|string',
            'plans.*.exercises.*.exercise_details.*.kg' => 'required|integer',
            'plans.*.exercises.*.exercise_details.*.sets' => 'required|integer',
            'plans.*.exercises.*.exercise_details.*.reps' => 'required|integer',
            'plans.*.exercises.*.exercise_details.*.status' => 'required|boolean',
            'plans.*.exercises.*.exercise_details.*.duration' => 'required|integer',

        ];
    }
}
