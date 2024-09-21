<?php

namespace App\Http\Controllers\Exercise;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\FullEsxercisePlanRequest;
use App\Http\Resources\Exercise\PlanExerciseResource;
use App\Http\Resources\Exercise\WeeklyPlanExerciseResource;
use App\Models\Diet\Plan;
use App\Models\Diet\UserPlan;
use App\Models\Exercise\Exercise;
use App\Models\Exercise\ExerciseDetails;
use App\Models\Exercise\ExercisePlanExercise;
use App\Models\Exercise\PlanExercise;
use App\Models\Exercise\UserPlanExercise;
use App\Models\Exercise\WeeklyPlan;
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

        return $this->paginateResponse($plans, 'Plans retrieved successfully');
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

        return response()->json([
            'status' => 'success',
            'message' => 'Plan by date retrieved successfully',
            'plan' => PlanExerciseResource::make($plan),
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/exercise/plan/store",
     *     operationId="storeExercisePlan",
     *     tags={"Exercise Plans"},
     *     summary="Create or update a full exercise plan",
     *     description="Create or update a full exercise plan with weekly plans, plans, exercises and exercise details",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="weekly_plan_id", type="integer", example=1),
     *             @OA\Property(property="weekly_plan_name", type="string", example="Weekly Plan 1"),
     *             @OA\Property(
     *                 property="plans",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Plan 1"),
     *                     @OA\Property(property="note", type="string", example="This is a plan note"),
     *                     @OA\Property(
     *                         property="exercises",
     *                         type="array",
     *                         @OA\Items(
     *                             type="object",
     *                             @OA\Property(property="id", type="integer", example=1),
     *                             @OA\Property(property="name", type="string", example="Exercise 1"),
     *                             @OA\Property(property="note", type="string", example="This is an exercise note"),
     *                             @OA\Property(property="run_duration", type="integer", example=30),
     *                             @OA\Property(
     *                                 property="exercise_details",
     *                                 type="array",
     *                                 @OA\Items(
     *                                     type="object",
     *                                     @OA\Property(property="id", type="integer", example=1),
     *                                     @OA\Property(property="name", type="string", example="Detail Name"),
     *                                     @OA\Property(property="previous", type="string", example="Previous Detail"),
     *                                     @OA\Property(property="rir", type="string", example="RIR Detail"),
     *                                     @OA\Property(property="tempo", type="string", example="Tempo Detail"),
     *                                     @OA\Property(property="rest", type="string", example="Rest Detail"),
     *                                     @OA\Property(property="kg", type="integer", example=10),
     *                                     @OA\Property(property="sets", type="integer", example=3),
     *                                     @OA\Property(property="reps", type="integer", example=10),
     *                                     @OA\Property(property="status", type="boolean", example=true),
     *                                     @OA\Property(property="duration", type="integer", example=30)
     *                                 )
     *                             )
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Exercise plan created or updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Exercise plan created or updated successfully")
     *         )
     *     ),
     *     @OA\Response(response=400, description="Bad Request"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    public function storeExercisePlan(FullEsxercisePlanRequest $request): JsonResponse
    {

        $weeklyPlan = WeeklyPlan::find($request->weekly_plan_id);

        if (!$weeklyPlan) {
            $weeklyPlan = WeeklyPlan::create([
                'name' => $request->weekly_plan_name,
                'client_id' => $request->user_ids,
                'note' => $request->note
            ]);
        } else {
            $weeklyPlan->update([
                'name' => $request->weekly_plan_name,
                'client_id' => $request->user_ids,
                'note' => $request->note
            ]);
        }

        if($request->is_work == 1)
            $this->activatePlan($weeklyPlan->id , $request->user_ids);


        foreach ($request->plans as $planData) {

            $plan = null;
            if (isset($planData['id']))
                $plan = PlanExercise::query()->find($planData['id']);
            if ($plan) {
                $plan->update(['name' => $planData['name'], 'weekly_plan_id' => $weeklyPlan->id]);
            } else
                $plan = PlanExercise::create(['name' => $planData['name'], 'weekly_plan_id' => $weeklyPlan->id]);

            if (isset($planData['note'])) {
                $plan->note()->updateOrCreate(
                    ['plan_exercise_id' => $plan->id],
                    [
                        'content' => $planData['note'],
                        'user_id' => auth()->id()
                    ],
                );
            }
            foreach ($planData['exercises'] as $exerciseData) {
                $exercise = null;

                if (isset($exerciseData['id'])) {

                    $exercise = Exercise::find($exerciseData['id']);

                }

                if (!$exercise) {
                    $exercise = Exercise::create([
                        'name' => $exerciseData['name'],
                        'run_duration' => $exerciseData['run_duration'] ?? 0,
                        'plan_id' => $plan->id
                    ]);
                } else {
                    $exercise->update([
                        'name' => $exerciseData['name'],
                        'run_duration' => $exerciseData['run_duration'] ?? 0,
                    ]);
                }

                ExercisePlanExercise::query()->create([
                    'exercise_id' => $exercise->id,
                    'plan_exercise_id' => $plan->id,
                ]);
                if (isset($exerciseData['note'])) {
                    $exercise->note()->updateOrCreate(
                        ['exercise_id' => $plan->id],
                        ['content' => $exerciseData['note'], 'user_id' => auth()->id()]
                    );
                }
                foreach ($exerciseData['exercise_details'] as $detail) {
                    $exerciseDetails = ExerciseDetails::query()->find($detail['id'] ?? null);

                    if (!$exerciseDetails) {
                        $exerciseDetails = ExerciseDetails::create([
                            'name' => $detail['name'],
                            'previous' => $detail['previous'],
                            'rir' => $detail['rir'],
                            'tempo' => $detail['tempo'],
                            'rest' => $detail['rest'],
                            'kg' => $detail['kg'],
                            'sets' => $detail['sets'],
                            'reps' => $detail['reps'],
                            'status' => $detail['status'],
                            'exercise_id' => $exercise->id,
                            'duration' => $detail['duration'],
                        ]);
                    } else {

                        $exerciseDetails->update($detail);
                    }
                }
            }

            //assign plan to UserPlanExercise
            $data = [
                'plan_id' => $plan->id,
                'user_ids' => [$request->user_ids],
                'is_work' => $planData['is_work'] ?? true,
                'weekly_plan_id' => $weeklyPlan->id,
                'days' => [$planData['days']],
            ];

            $requestData = new \Illuminate\Http\Request();
            $requestData->replace($data);

            $this->assignPlanToUsers($requestData);
        }


        return response()->json([
            'status' => 'success',
            'message' => 'Exercise plan created or updated successfully',
            'plan' => WeeklyPlanExerciseResource::make($weeklyPlan->refresh()),
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/diet/plan/assign",
     *     summary="Assign a plan to multiple users",
     *     @OA\Parameter(
     *         name="user_ids",
     *         in="query",
     *         description="Array of User IDs",
     *         required=true,
     *         @OA\Schema(type="array", @OA\Items(type="integer"))
     *     ),
     *     @OA\Parameter(
     *         name="plan_id",
     *         in="query",
     *         description="Plan's ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="Plan assigned to users successfully"),
     *     @OA\Response(response="400", description="Validation errors")
     * )
     */
    public function assignPlanToUsers(Request $request): JsonResponse
    {

        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'required|integer|exists:users,id',
            'plan_id' => 'required|integer',
            'is_work' => 'boolean',
            'weekly_plan_id' => 'nullable|integer|exists:weekly_plans,id',
            'days' => 'required|array',
            'days.*' => 'required|integer',
        ]);

        $is_work = $request->is_work == 1 ? 1 : 0;

        if ($is_work == 1)
            UserPlanExercise::query()->whereIn('user_id', $request->user_ids)->update(['is_work' => false]);
        $userPlans = UserPlanExercise::assignPlanToUsers($request->user_ids, $request->plan_id, $is_work, $request->weekly_plan_id, $request->days);

        return response()->json([
            'status' => 'success',
            'message' => 'Plan assigned to users successfully',
            'userPlans' => $userPlans,
        ]);
    }

    private function activatePlan($id , $user_id)
    {
        WeeklyPlan::query()
            ->where('id', '!=', $id)
            ->where('client_id', $user_id)
            ->update(['is_work' => false]);
        WeeklyPlan::query()->where('id', $id)->update(['is_work' => true]);
    }

}
