<?php

namespace App\Http\Controllers\Diet;

use App\Http\Controllers\Controller;
use App\Http\Resources\Diet\PlanResource;
use App\Models\Diet\UserMeal;
use App\Models\Diet\UserPlan;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AppController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/diet/plan",
     *     summary="Retrieve all plans",
     *     @OA\Response(
     *         response="200",
     *         description="Plans retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Active plan retrieved successfully"),
     *             @OA\Property(
     *                 property="plan",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=76),
     *                 @OA\Property(property="name", type="string", example="Plan Name"),
     *                 @OA\Property(property="total_calories", type="number", format="float", example=500),
     *                 @OA\Property(property="total_carbohydrate", type="number", format="float", example=60),
     *                 @OA\Property(property="total_protein", type="number", format="float", example=40),
     *                 @OA\Property(property="total_fat", type="number", format="float", example=20),
     *                 @OA\Property(property="is_work", type="boolean", example=true),
     *                 @OA\Property(property="notes", type="string", example="this note is optional called plan note"),
     *                 @OA\Property(
     *                     property="meals",
     *                     type="array",
     *                     @OA\Items(ref="#/components/schemas/MealResource")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response="404", description="Plans not found")
     * )
     */
    public function getActivePlan(): JsonResponse
    {
        $userPlan = UserPlan::query()
            ->where('user_id', auth()->id())
            ->where('is_work', true)
            ->with(['plan' => function ($q) {
                $q->with(['meals' => function ($q) {
                    $q->with(['items' => function ($q) {
                        $q->with(['standard' => function ($q) {
                            $q->with('standardType');
                        }]);
                    }]);
                }]);
            }])->first();

        if (!$userPlan) {
            return response()->json([
                'status' => 'error',
                'message' => 'No active plan found',
            ], 404);
        }

        $plan = $userPlan->plan;
        $plan->is_work = $userPlan->is_work;
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
    public function assignMealToUser(Request $request): JsonResponse
    {
        $request->validate([
            'meal_id' => 'required|integer|exists:meals,id',
        ]);

        $userMeal = UserMeal::query()
            ->where('user_id', auth()->id())
            ->where('meal_id', $request->meal_id)
            ->whereDate('created_at', now()->toDateString())
            ->first();

        if ($userMeal) {
            $userMeal->update(['is_eaten' => !$userMeal->is_eaten]);
        } else {
            $userMeal = UserMeal::create([
                'user_id' => auth()->id(),
                'meal_id' => $request->meal_id,
                'is_eaten' => 1,
                'created_at' => now()->toDateString(),
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Meal assigned to user successfully',
            'userMeal' => $userMeal,
        ]);
    }


}
