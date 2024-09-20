<?php

namespace App\Http\Controllers\Exercise;

use App\Http\Controllers\Controller;
use App\Http\Resources\Exercise\WeeklyPlanExerciseResource;
use App\Models\Exercise\Exercise;
use App\Models\Exercise\PlanExercise;
use App\Models\Exercise\WeeklyPlan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WeeklyPlanExerciseController extends Controller
{

    public function index(): JsonResponse
    {
        $weeklyPlans = WeeklyPlan::query()->with(['planExercises' => function ($q) {
            $q->with(['exercises' => function ($q) {
                $q->with('details');
            }, 'run', 'note', 'userPlanExercises']);
        }])->orderByDesc('id')->paginate(10);

        $weeklyPlans->getCollection()->transform(function ($item) {
            return new WeeklyPlanExerciseResource($item);
        });
       return $this->paginateResponse($weeklyPlans, 'Plan by date retrieved successfully');
    }

    public function show(WeeklyPlan $weeklyPlan): JsonResponse
    {
        $weeklyPlan->loadPlanExercisesDetails();
        return response()->json([
            'status' => 'success',
            'message' => 'Plan by date retrieved successfully',
            'plan' => WeeklyPlanExerciseResource::make($weeklyPlan),
        ]);
    }

    public function deleteWeeklyPlan($id): JsonResponse
    {
        $weeklyPlan = WeeklyPlan::query()->find($id);
        if (!$weeklyPlan) {
            return response()->json([
                'status' => 'error',
                'message' => 'Plan by date not found',
            ], 404);
        }

        //delete details in exercise
        foreach ($weeklyPlan->planExercises as $planExercise) {
            $planExercise->exercises()->delete();
        }

        $weeklyPlan->planExercises()->delete();
        $weeklyPlan->userPlanExercises()->delete();
        $weeklyPlan->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Plan by date deleted successfully',
        ]);
    }

    public function deletePlanExercises($id): JsonResponse
    {
        $planExercise = PlanExercise::query()->find($id);
        if (!$planExercise) {
            return response()->json([
                'status' => 'error',
                'message' => 'PlanExercise not found',
            ], 404);
        }

        // Delete related exercises
        $planExercise->exercises()->delete();

        // Delete related userPlanExercises
        $planExercise->userPlanExercises()->delete();

        // Delete the PlanExercise itself
        $planExercise->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'PlanExercise and related records deleted successfully',
        ]);
    }


    public function deleteExercise($id): JsonResponse
    {
        $exercise = Exercise::query()->find($id);
        if (!$exercise) {
            return response()->json([
                'status' => 'error',
                'message' => 'Exercise not found',
            ], 404);
        }

        // Delete related ExerciseDetails
        $exercise->details()->delete();

        // Delete the Exercise itself
        $exercise->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Exercise and related records deleted successfully',
        ]);
    }

    public function getClientPlan($id): JsonResponse
    {
        $weeklyPlans = WeeklyPlan::query()
            ->with(['planExercises' => function ($q) {
                $q->with(['exercises' => function ($q) {
                    $q->with('details');
                }, 'run', 'note', 'userPlanExercises']);
            }])
            ->whereHas('planExercises.userPlanExercises', function ($q) use ($id) {
                $q->where('user_id', $id);
            })
            ->orderByDesc('id')
            ->get();

        if ($weeklyPlans->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Plan by date not found',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Plan by date retrieved successfully',
            'plan' => WeeklyPlanExerciseResource::collection($weeklyPlans),
            'count' => $weeklyPlans->count(),
        ]);
    }
}
