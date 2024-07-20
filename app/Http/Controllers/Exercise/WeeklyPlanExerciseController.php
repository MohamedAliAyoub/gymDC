<?php

namespace App\Http\Controllers\Exercise;

use App\Http\Controllers\Controller;
use App\Http\Resources\Exercise\WeeklyPlanExerciseResource;
use App\Models\Exercise\WeeklyPlan;
use Illuminate\Http\JsonResponse;

class WeeklyPlanExerciseController extends Controller
{

    public function index():JsonResponse
    {
        $weeklyPlans = WeeklyPlan::query()->with(['planExercises' => function ($q) {
            $q->with(['exercises' => function ($q) {
                $q->with('details');
            } , 'run' , 'note' , 'userPlanExercises'  ]);
        }])->paginate(10);

//        dd($weeklyPlans->first()->planExercises->first()->userPlanExercises->first()->day_names);
        return response()->json([
            'status' => 'success',
            'message' => 'Plan by date retrieved successfully',
            'plan' => WeeklyPlanExerciseResource::collection($weeklyPlans)
        ]);
    }
}
