<?php

namespace App\Http\Controllers\Diet;

use App\Http\Controllers\Controller;
use App\Models\Diet\Plan;
use App\Models\Diet\UserPlan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/diet/plan",
     *     summary="Retrieve all plans",
     *     @OA\Response(response="200", description="Plans retrieved successfully")
     * )
     */
    public function index()
    {
        $plans = Plan::query()->paginate(15);


        return response()->json([
            'status' => 'success',
            'message' => 'Plans retrieved successfully',
            'plans' => $plans,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/diet/plan",
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

    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'status' => 'nullable',
        ]);


        $plan = Plan::query()->create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Plan created successfully',
            'plan' => $plan,
        ]);
    }


    /**
     * @OA\Put(
     *     path="/api/diet/plan/{plan}",
     *     summary="Update an existing plan",
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
     *     @OA\Response(response="400", description="Validation errors")
     * )
     */

    public function update(Request $request, $id)
    {
        try {
            $plan = Plan::query()->findOrFail($id);
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
     * @OA\Get(
     *     path="/api/diet/plan/{plan}",
     *     summary="Retrieve a plan",
     *     @OA\Response(response="200", description="Plan retrieved successfully"),
     *     @OA\Response(response="404", description="Plan not found")
     * )
     */
    public function show($id)
    {
        try {
            $plan = Plan::query()->findOrFail($id);
        } catch (\Exception $e) {
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
     * @OA\Delete(
     *     path="/api/diet/plan/{plan}",
     *     summary="Delete a plan",
     *     @OA\Response(response="200", description="Plan deleted successfully"),
     *     @OA\Response(response="404", description="Plan not found")
     * )
     */
    public function delete($id)
    {

        try {
            $plan = Plan::query()->findOrFail($id);
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
    public function assignPlanToUsers(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'required|integer|exists:users,id',
            'plan_id' => 'required|integer|exists:plans,id',
        ]);

        UserPlan::query()->whereIn('user_id', $request->user_ids)->update(['is_work' => false]);
        $userPlans = UserPlan::assignPlanToUsers($request->user_ids, $request->plan_id);

        return response()->json([
            'status' => 'success',
            'message' => 'Plan assigned to users successfully',
            'userPlans' => $userPlans,
        ]);
    }


}
