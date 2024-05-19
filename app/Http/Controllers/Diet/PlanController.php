<?php

namespace App\Http\Controllers\Diet;

use App\Http\Controllers\Controller;
use App\Models\Diet\Plan;
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

    public function update(Request $request, Plan $plan)
    {
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
    public function show(Plan $plan)
    {
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
        $plan = Plan::find($id);

        if (!$plan) {
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
}
