<?php

namespace App\Http\Controllers\Exercise;

use App\Http\Controllers\Controller;
use App\Models\Exercise\ExercisePlanExercise;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ExercisePlanExerciseController extends Controller
{
    public function assignExercisesToPlan(Request $request): JsonResponse
    {
        $request->validate([
            'plan_exercise_id' => 'required|integer',
            'exercise_ids' => 'required|array',
            'exercise_ids.*' => 'required|integer|exists:exercises,id',
            'status' => 'nullable|boolean',
        ]);

        foreach ($request->exercise_ids as $exercise_id) {
            ExercisePlanExercise::query()->create([
                'plan_exercise_id' => $request->plan_exercise_id,
                'exercise_id' => $exercise_id,
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Exercises assigned to plan successfully',
        ]);
    }

}
