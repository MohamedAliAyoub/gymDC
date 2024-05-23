<?php

namespace App\Http\Controllers\Diet;

use App\Http\Controllers\Controller;
use App\Http\Resources\Diet\PlanResource;
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
}
