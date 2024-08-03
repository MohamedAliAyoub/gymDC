<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\PackagesEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\Dashboard\SubscriptionResource;
use App\Models\Dashboard\UserSubscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserSubscriptionController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'client_id' => 'required|exists:users,id',
            'packages_type' => ['required', Rule::in(PackagesEnum::getValues())],
            'duration' => 'required|numeric',
            'paid_amount' => 'required|numeric',
            'whatsapp_group_link' => 'nullable|url',
        ]);
        $validatedData['sale_id'] = auth()->id();

        $userSubscription = UserSubscription::query()->create($validatedData);
        return response()->json([
            'status' => 'success',
            'message' => 'User subscription created successfully',
        ]);
    }
}
