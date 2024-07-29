<?php

namespace App\Http\Requests\CheckIn;

use Illuminate\Foundation\Http\FormRequest;

class CheckInRequest extends FormRequest
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
            'committed_nutrition_plan' => 'nullable|boolean',
            'weight' => 'nullable|string|max:255',
            'in_body_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'chest_measurement' => 'nullable|numeric',
            'stomach_measurement' => 'nullable|numeric',
            'waist_measurement' => 'nullable|numeric',
            'hips_measurement' => 'nullable|numeric',
            'thigh_measurement' => 'nullable|numeric',
            'claves_measurement' => 'nullable|numeric',
            'notes' => 'nullable|string|max:255',
            'body_images' => 'nullable|array',
            'body_images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }
}
