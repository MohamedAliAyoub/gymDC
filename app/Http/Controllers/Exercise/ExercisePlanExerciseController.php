<?php

namespace App\Http\Controllers\Exercise;

use App\Http\Controllers\Controller;
use App\Models\Exercise\ExercisePlanExercise;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ExercisePlanExerciseController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/exercise/plan/assignExercisesToPlan",
     *     summary="Assign exercises to a plan",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="plan_exercise_id", type="integer", example=1),
     *             @OA\Property(property="exercise_ids", type="array", @OA\Items(type="integer"), example="[1, 2, 3]"),
     *             @OA\Property(property="status", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Exercises assigned to plan successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Exercises assigned to plan successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation errors",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Validation errors"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
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
