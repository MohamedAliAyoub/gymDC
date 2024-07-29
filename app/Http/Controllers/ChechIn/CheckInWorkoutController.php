<?php

namespace App\Http\Controllers\ChechIn;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckIn\CheckInWorkoutRequest;
use App\Models\BodyImage;
use App\Models\CheckIn\CheckInWorkout;
use Illuminate\Http\JsonResponse;
use function App\Http\Helpers\uploadImage;

class CheckInWorkoutController extends Controller
{
    public function store(CheckInWorkoutRequest $request): JsonResponse
    {

        $validated = $request->validated();
        $validated['user_id'] = auth()->id();
        $checkInWorkout = CheckInWorkout::query()->create($validated);

        return response()->json(['message' => 'workout check-in form submitted successfully']);
    }
}
