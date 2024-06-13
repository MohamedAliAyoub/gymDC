<?php

namespace App\Http\Controllers\Exercise;

use App\Http\Controllers\Controller;
use App\Http\Resources\Exercise\PlanExerciseResource;
use App\Models\Exercise\PlanExercise;
use App\Models\Exercise\UserPlanExercise;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PlanExerciseController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/exercise/plan",
     *     summary="Retrieve plans with pagination 15 items per page",
     *     @OA\Response(response="200", description="Plans retrieved successfully")
     * )
     */
    public function index(): JsonResponse
    {
        $plans = PlanExercise::query()->paginate(15);

        return response()->json([
            'status' => 'success',
            'message' => 'Plans retrieved successfully',
            'plans' => $plans,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/exercise/plan",
     *     summary="Create a new plan",
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="Plan's name",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Plan's status",
     *         required=false,
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Response(response="200", description="Plan created successfully"),
     *     @OA\Response(response="400", description="Validation errors")
     * )
     */
    public function create(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string',
            'status' => 'nullable|boolean',
            'exercise_ids' => 'nullable|array',
            'exercise_ids.*' => 'nullable|integer|exists:exercises,id',
        ]);

        $plan = PlanExercise::query()->create([
            'name' => $request->name,
            'status' => $request->status ?? true,
        ]);
        //add exercises to plan
        $plan->exercises()->attach($request->exercise_ids);

        return response()->json([
            'status' => 'success',
            'message' => 'Plan created successfully',
            'plan' => $plan,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/exercise/plan/{id}",
     *     summary="Retrieve a plan",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Plan's ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="Plan retrieved successfully"),
     *     @OA\Response(response="404", description="Plan not found")
     * )
     */
    public function show(int $id): JsonResponse
    {
        $plan = PlanExercise::query()->find($id);

        if (!$plan) {
            return response()->json([
                'status' => 'error',
                'message' => 'Plan not found',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Plan retrieved successfully',
            'plan' => $plan,
        ]);
    }

   /**
 * @OA\Put(
 *     path="/api/exercise/plan/{id}",
 *     summary="Update a plan",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="Plan's ID",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(
 *         name="name",
 *         in="query",
 *         description="Plan's name",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="status",
 *         in="query",
 *         description="Plan's status",
 *         required=false,
 *         @OA\Schema(type="boolean")
 *     ),
 *     @OA\Response(response="200", description="Plan updated successfully"),
 *     @OA\Response(response="422", description="Validation errors"),
 *     @OA\Response(response="404", description="Plan not found")
 * )
 */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $plan = PlanExercise::query()->findOrFail($id);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Plan not found',
            ], 404);
        }

        $request->validate([
            'name' => 'required|string',
            'status' => 'nullable|boolean',
        ]);

        $plan->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Plan updated successfully',
            'plan' => $plan,
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/exercise/plan/{id}",
     *     summary="Delete a plan",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Plan's ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="Plan deleted successfully"),
     *     @OA\Response(response="404", description="Plan not found")
     * )
     */
    public function delete(int $id): JsonResponse
    {
        try {
            $plan = PlanExercise::query()->findOrFail($id);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Plan not found',
            ], 404);
        }

        $plan->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Plan deleted successfully',
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/exercise/user-plan-exercise/today-plan",
     *     summary="Retrieve today's plan",
     *     @OA\Response(response="200", description="Today's plan retrieved successfully")
     * )
     */
    public function getTodayPlan(): \Illuminate\Http\JsonResponse
    {
        $todayPlan = UserPlanExercise::getPlanOfToday();
        if (!$todayPlan) {
            return response()->json([
                'status' => 'success',
                'message' => 'rest day today',
                'todayPlan' => [
                    'rest_day' => true,
                ]
            ]);
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Today\'s plan retrieved successfully',
            'todayPlan' =>PlanExerciseResource::make($todayPlan->plan),
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/exercise/user-plan-exercise/plan-by-date",
     *     summary="Retrieve plan by date",
     *     @OA\Parameter(
     *         name="date",
     *         in="query",
     *         description="Date in format Y-m-d",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response="200", description="Plan by date retrieved successfully")
     * )
     */
    public function getPlanByDate(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'date' => 'required|date_format:Y-m-d',
        ]);
        $date = Carbon::createFromFormat('Y-m-d', $request->date);

        $plan = UserPlanExercise::getPlanByDate($date);

        return response()->json([
            'status' => 'success',
            'message' => 'Plan by date retrieved successfully',
            'plan' => $plan,
        ]);
    }

}
