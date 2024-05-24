<?php

namespace App\Http\Controllers\Diet;

use App\Http\Controllers\Controller;
use App\Http\Resources\Diet\PlanResource;
use App\Models\Diet\UserMeal;
use App\Models\Diet\UserPlan;
use Illuminate\Http\Request;

class AppController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/diet/app/active-plan",
     *     summary="Retrieve active plan",
     *     @OA\Response(response="200", description="Active plan retrieved successfully"),
     *     @OA\Response(response="404", description="No active plan found")
     * )
     */
    public function getActivePlan()
    {
        $userPlan = UserPlan::query()->where('user_id', auth()->id())->where('is_work', true)->first();

        if (!$userPlan) {
            return response()->json([
                'status' => 'error',
                'message' => 'No active plan found',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Active plan retrieved successfully',
            'plan' => PlanResource::make($userPlan->plan),
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/diet/meal/assign",
     *     summary="Assign a meal to a user",
     *     @OA\Parameter(
     *         name="meal_id",
     *         in="query",
     *         description="Meal's ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="Meal assigned to user successfully"),
     *     @OA\Response(response="400", description="Validation errors")
     * )
     */
    public function assignMealToUser(Request $request)
    {
        $request->validate([
            'meal_id' => 'required|integer|exists:meals,id',
        ]);

        $userMeal =UserMeal::query()->create([
            'user_id' => auth()->id(),
            'meal_id' => $request->meal_id,
            'status' => true,
            'is_eaten' => 1,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Meal assigned to user successfully',
            'userMeal' => $userMeal,
        ]);
    }


}
