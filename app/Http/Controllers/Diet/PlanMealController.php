<?php

namespace App\Http\Controllers\Diet;

use App\Http\Controllers\Controller;
use App\Http\Resources\Diet\PlanMealResource;
use App\Models\Diet\PlanMeal;
use Illuminate\Http\Request;

class PlanMealController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/diet/planmeal",
     *     summary="Retrieve all plan meals",
     *     @OA\Response(response="200", description="Plan meals retrieved successfully")
     * )
     */
    public function index()
    {
        $planMeals = PlanMeal::query()->with(['meal' , 'plan'])->paginate(15);

        return response()->json([
            'status' => 'success',
            'message' => 'Plan meals retrieved successfully',
            'planMeals' => PlanMealResource::collection($planMeals),
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/diet/planmeal",
     *     summary="Create a new plan meal",
     *     @OA\Parameter(
     *         name="plan_id",
     *         in="query",
     *         description="Plan id",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="meal_id",
     *         in="query",
     *         description="Meal id",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Plan meal's status",
     *         required=true,
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Response(response="200", description="Plan meal created successfully"),
     *     @OA\Response(response="400", description="Validation errors")
     * )
     */
    public function create(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|integer',
            'meal_id' => 'required|integer',
            'status' => 'nullable|boolean',
        ]);

        $planMeal = PlanMeal::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Plan meal created successfully',
            'planMeal' => $planMeal,
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/diet/planmeal/{planMeal}",
     *     summary="Update an existing plan meal",
     *     @OA\Parameter(
     *         name="plan_id",
     *         in="query",
     *         description="Plan id",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="meal_id",
     *         in="query",
     *         description="Meal id",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Plan meal's status",
     *         required=true,
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Response(response="200", description="Plan meal updated successfully"),
     *     @OA\Response(response="400", description="Validation errors")
     * )
     */
    public function update(Request $request, $id)
    {
        try {
            $planMeal = PlanMeal::query()->findOrFail($id);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Plan meal not found',
            ], 404);
        }
        $request->validate([
            'plan_id' => 'required|integer|exists:plans,id',
            'meal_id' => 'required|integer|exists:meals,id',
            'status' => 'nullable|boolean',
        ]);

        $planMeal->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Plan meal updated successfully',
            'planMeal' => $planMeal,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/diet/planmeal/{planMeal}",
     *     summary="Retrieve a plan meal",
     *     @OA\Response(response="200", description="Plan meal retrieved successfully"),
     *     @OA\Response(response="404", description="Plan meal not found")
     * )
     */
    public function show($id)
    {
        try {
            $planMeal = PlanMeal::query()->findOrFail($id);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Plan meal not found',
            ], 404);
        }
        $planMeal->load(['meal', 'plan']);
        return response()->json([
            'status' => 'success',
            'message' => 'Plan meal retrieved successfully',
            'planMeal' => PlanMealResource::make($planMeal),
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/diet/planmeal/{planMeal}",
     *     summary="Delete a plan meal",
     *     @OA\Response(response="200", description="Plan meal deleted successfully"),
     *     @OA\Response(response="404", description="Plan meal not found")
     * )
     */
    public function delete($id)
    {
        try {
            $planMeal = PlanMeal::query()->findOrFail($id);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Plan meal not found',
            ], 404);
        }
        $planMeal->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Plan meal deleted successfully',
        ]);
    }
}
