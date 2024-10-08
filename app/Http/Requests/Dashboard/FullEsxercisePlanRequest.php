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
            'note' => 'nullable|string',
            'is_work' => 'nullable|boolean',
            'plans' => 'required|array',
            'plans.*.id' => 'nullable|integer',
            'plans.*.name' => 'required|string',
            'plans.*.days' => 'required|integer|between:0,6',
            'plans.*.note' => 'nullable|string',
            'plans.*.exercises' => 'required|array',
            'plans.*.exercises.*.id' => 'nullable|integer',
            'plans.*.exercises.*.name' => 'required|string',
            'plans.*.exercises.*.note' => 'nullable|string',
            'plans.*.exercises.*.run_duration' => 'nullable|integer',
            'plans.*.exercises.*.exercise_details' => 'nullable|array',
            'plans.*.exercises.*.exercise_details.*.id' => 'nullable|integer',
            'plans.*.exercises.*.exercise_details.*.name' => 'nullable|string',
            'plans.*.exercises.*.exercise_details.*.previous' => 'nullable',
            'plans.*.exercises.*.exercise_details.*.rir' => 'nullable',
            'plans.*.exercises.*.exercise_details.*.tempo' => 'nullable',
            'plans.*.exercises.*.exercise_details.*.rest' => 'nullable',
            'plans.*.exercises.*.exercise_details.*.kg' => 'nullable|integer',
            'plans.*.exercises.*.exercise_details.*.sets' => 'nullable|integer',
            'plans.*.exercises.*.exercise_details.*.reps' => 'nullable|integer',
            'plans.*.exercises.*.exercise_details.*.status' => 'nullable|boolean',
            'plans.*.exercises.*.exercise_details.*.duration' => 'nullable|integer',

        ];
    }
}
