<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubscriptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'nutrition_coach_id' => 'required|exists:users,id',
            'workout_coach_id' => 'required|exists:users,id',
            'client_id' => 'required|exists:users,id',
            'sale_id' => 'nullable|exists:users,id',
            'duration' => 'nullable|integer',
            'type' => 'nullable|integer',
            'started_at' => 'nullable|date',
            'paid_amount' => 'nullable|numeric',
            'freeze_start_at' => 'nullable|date',
            'freeze_duration' => 'nullable|integer',
            'paid_at' => 'nullable|date',
            'status' => 'nullable|integer',
        ];
    }
}
