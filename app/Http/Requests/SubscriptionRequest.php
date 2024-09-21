<?php

namespace App\Http\Requests;

use App\Enums\PackagesEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubscriptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'nutrition_coach_id' => 'nullable|exists:users,id',
            'workout_coach_id' => 'nullable|exists:users,id',
            'client_id' => 'required|exists:users,id',
            'sale_id' => 'nullable|exists:users,id',
            'duration' => 'nullable|integer',
            'type' => ['nullable', 'integer', Rule::in(PackagesEnum::getValues())],
            'started_at' => 'nullable|date',
            'paid_amount' => 'nullable|numeric',
            'freeze_start_at' => 'nullable|date',
            'freeze_duration' => 'nullable|integer',
            'paid_at' => 'nullable|date',
            'status' => 'nullable|integer',
            'whats_group_link' => 'nullable|url',
        ];
    }
}
