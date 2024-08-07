<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class FullplanRequest extends FormRequest
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
            'id' => 'nullable|integer|exists:plans,id',
            'name' => 'required|string',
            'note' => 'nullable|string',
            'meals' => 'required|array',
            'meals.*.id' => 'nullable|integer|exists:meals,id',
            'meals.*.name' => 'required|string',
            'meals.*.note' => 'nullable|string',
            'meals.*.carbohydrate' => 'required|numeric',
            'meals.*.protein' => 'required|numeric',
            'meals.*.fat' => 'required|numeric',
            'meals.*.calories' => 'required|numeric',
            'meals.*.items' => 'required|array',
            'meals.*.items.*.id' => 'nullable|integer|exists:items,id',
            'meals.*.items.*.name' => 'required|string',
            'meals.*.items.*.type' => 'required|integer',
            'meals.*.items.*.details' => 'required|array',
            'meals.*.items.*.details.*.id' => 'nullable|integer|exists:item_details,id',
            'meals.*.items.*.details.*.name' => 'required|string',
            'meals.*.items.*.details.*.number' => 'required|integer',
            'meals.*.items.*.details.*.standard_type' => 'required|integer|exists:standard_types,id',
            'meals.*.items.*.details.*.carbohydrate' => 'required|numeric',
            'meals.*.items.*.details.*.protein' => 'required|numeric',
            'meals.*.items.*.details.*.fat' => 'required|numeric',
            'meals.*.items.*.details.*.calories' => 'required|numeric',
        ];
    }
}
