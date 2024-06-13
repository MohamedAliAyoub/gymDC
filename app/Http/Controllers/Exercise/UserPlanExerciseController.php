<?php

namespace App\Http\Controllers\Exercise;

use App\Http\Controllers\Controller;
use App\Http\Resources\Exercise\ExerciseResource;
use App\Http\Resources\Exercise\PlanExerciseResource;
use App\Http\Resources\Exercise\RestDayResource;
use App\Http\Resources\Exercise\UserPlanExerciseResource;
use App\Models\Exercise\NoteExercise;
use App\Models\Exercise\UserPlanExercise;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UserPlanExerciseController extends Controller
{
    /**
     * tags={"Exercise"},
     * @OA\Get(
     *     path="/api/exercise/user-plan-exercise",
     *     summary="Retrieve user plan exercises with pagination 15 items per page",
     *     @OA\Response(response="200", description="User plan exercises retrieved successfully")
     * )
     */
    public function index()
    {
        $userPlanExercises = UserPlanExercise::query()->paginate(15);

        return response()->json([
            'status' => 'success',
            'message' => 'User plan exercises retrieved successfully',
            'userPlanExercises' => $userPlanExercises,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/exercise/user-plan-exercise",
     *     summary="Create a new user plan exercise",
     *     tags={"Exercise"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="plan_id", type="integer", description="Must exist in exercise plans table"),
     *             @OA\Property(property="user_id", type="integer", description="Must exist in users table"),
     *             @OA\Property(property="status", type="boolean"),
     *             @OA\Property(property="is_work", type="boolean"),
     *             @OA\Property(property="days", type="string"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Note exercise created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/NoteExercise")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *     ),
     * )
     */
    public function create(Request $request)
    {

        $request->validate([
            'plan_id' => 'required|exists:plan_exercises,id',
            'user_id' => 'required|exists:users,id',
            'status' => 'nullable|boolean',
            'is_work' => 'nullable|boolean',
            'days' => 'required|array',
            'days.*' => 'required|int|between:0,6',
        ]);
        $userPlan = UserPlanExercise::query()->create($request->all());
        foreach ($request->days as $day) {
            $plan = UserPlanExercise::query()->where([
                'user_id' => $request->user_id,
            ])->whereJsonContains('days', (string)$day)
                ->first();
            if (isset($plan)) {
                if (count($plan->days) == 1)
                    $plan->update('is_work', false);
                else {
                    $updatedDays = array_diff($plan->days, [$day]);
                    $plan->update('days', $updatedDays);
                }

            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'user plan exercise created successfully',
            'noteExercise' => $userPlan,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/exercise/user-plan-exercise/{id}",
     *     summary="Retrieve a specific note exercise",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Note exercise's ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="Note exercise retrieved successfully")
     * )
     */
    public function show(int $id)
    {

        $userPlan = UserPlanExercise::query()->find($id);

        if (!$userPlan) {
            return response()->json([
                'status' => 'error',
                'message' => 'User plan exercise not found',
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'User plan exercise retrieved successfully',
            'userPlanExercise' => UserPlanExerciseResource::make($userPlan),
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/exercise/user-plan-exercise/{id}",
     *     summary="Update a specific user plan exercise",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="User plan exercise's ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="plan_id", type="integer", description="Must exist in exercise plans table"),
     *             @OA\Property(property="user_id", type="integer", description="Must exist in users table"),
     *             @OA\Property(property="status", type="boolean"),
     *             @OA\Property(property="is_work", type="boolean"),
     *             @OA\Property(property="days", type="string"),
     *         )
     *     ),
     *     @OA\Response(response="200", description="User plan exercise updated successfully"),
     *     @OA\Response(response="400", description="Bad request"),
     *     @OA\Response(response="500", description="Server error")
     * )
     */
    public function update(Request $request, int $id)
    {
        $request->validate([
            'plan_id' => 'required|exists:plan_exercises,id',
            'user_id' => 'required|exists:users,id',
            'status' => 'boolean',
            'is_work' => 'boolean',
            'days' => 'required|array',
            'days.*' => 'required|int|between:0,6',
        ]);

        $userPlan = UserPlanExercise::query()->find($id);

        if (!$userPlan) {
            return response()->json([
                'status' => 'error',
                'message' => 'User plan exercise not found',
            ]);
        }

        $userPlan->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'User plan exercise updated successfully',
            'userPlanExercise' => $userPlan,
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/exercise/user-plan-exercise/{id}",
     *     summary="Delete a specific user plan exercise",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="User plan exercise's ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="User plan exercise deleted successfully"),
     *     @OA\Response(response="404", description="User plan exercise not found")
     * )
     */
    public function delete(int $id)
    {
        $userPlan = UserPlanExercise::query()->find($id);

        if (!$userPlan) {
            return response()->json([
                'status' => 'error',
                'message' => 'User plan exercise not found',
            ]);
        }

        $userPlan->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'User plan exercise deleted successfully',
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
                'todayPlan' =>  [
                    'rest_day' => true,
                ],
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Today\'s plan retrieved successfully',
            'todayPlan' => PlanExerciseResource::make($todayPlan->plan),
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

        if (!$plan) {
            return response()->json([
                'status' => 'success',
                'message' => 'rest day today',
                'plane' =>  [
                    'rest_day' => true,
                ],
            ]);
        }


        return response()->json([
            'status' => 'success',
            'message' => 'Plan by date retrieved successfully',
            'plan' => PlanExerciseResource::make($plan->plan),
        ]);
    }
}
