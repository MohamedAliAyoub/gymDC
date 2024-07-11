<?php

namespace App\Http\Controllers\Diet;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\FullplanRequest;
use App\Http\Resources\Diet\PlanResource;
use App\Models\Diet\Plan;
use App\Models\Diet\UserPlan;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PlanController extends Controller
{

    public function index(): JsonResponse
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
    public function assignPlanToUsers(Request $request): JsonResponse
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'required|integer|exists:users,id',
            'plan_id' => 'required|integer|exists:plans,id',
            'is_work' => 'boolean',
        ]);

        $is_work = $request->is_work == 1 ? 1 : 0;

        if ($is_work == 1)
            UserPlan::query()->whereIn('user_id', $request->user_ids)->update(['is_work' => false]);
        $userPlans = UserPlan::assignPlanToUsers($request->user_ids, $request->plan_id, $is_work);

        return response()->json([
            'status' => 'success',
            'message' => 'Plan assigned to users successfully',
            'userPlans' => $userPlans,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/plans",
     *     operationId="createFullPlan",
     *     tags={"Plans"},
     *     summary="Create a new full plan",
     *     description="Create a new full plan with meals and items",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", example="Plan Name"),
     *             @OA\Property(property="note", type="string", example="This is a plan note"),
     *             @OA\Property(
     *                 property="meals",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="name", type="string", example="Meal 1"),
     *                     @OA\Property(property="note", type="string", example="This is a meal note"),
     *                     @OA\Property(
     *                         property="items",
     *                         type="array",
     *                         @OA\Items(
     *                             type="object",
     *                             @OA\Property(property="name", type="string", example="Item 1"),
     *                             @OA\Property(property="type", type="integer", example=0),
     *                             @OA\Property(
     *                                 property="details",
     *                                 type="array",
     *                                 @OA\Items(
     *                                     type="object",
     *                                     @OA\Property(property="name", type="string", example="Detail Name"),
     *                                     @OA\Property(property="number", type="integer", example=1),
     *                                     @OA\Property(property="standard_type", type="integer", example=1),
     *                                     @OA\Property(property="carbohydrate", type="number", format="float", example=30),
     *                                     @OA\Property(property="protein", type="number", format="float", example=20),
     *                                     @OA\Property(property="fat", type="number", format="float", example=10),
     *                                     @OA\Property(property="calories", type="number", format="float", example=100)
     *                                 )
     *                             )
     *                         )
     *                     )
     *                 )
     *             ),
     *             @OA\Property(property="user_ids", type="array", @OA\Items(type="integer"), example={1, 2, 3}),
     *             @OA\Property(property="is_work", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Plan created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Plan created successfully"),
     *             @OA\Property(property="plan", ref="#/components/schemas/PlanResource")
     *         )
     *     ),
     *     @OA\Response(response=400, description="Bad Request"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    public function createFullPlan(FullplanRequest $request): JsonResponse
    {
        $plan = Plan::query()->create($request->only(['name']));
        if ($request->note)
            $plan->note()->create(['content' => $request->note, "user_id" => auth()->id()]);

        foreach ($request->meals as $meal) {
            $new_meal = $plan->meals()->create($meal);

            if (isset($meal['note']) && $meal['note']) {
                $new_meal->note()->create(['content' => $meal['note'], "user_id" => auth()->id()]);
            }

            foreach ($meal['items'] as $item) {
                $new_item = $new_meal->items()->create($item);
                foreach ($item['details'] as $detail) {
                    $new_item_details = $new_item->itemDetails()->create(['name' => $detail['name']]);
                    $id_column = $item['type'] == 0 ? 'item_details_id' : 'item_id';
                    $id_value = $item['type'] == 0 ? $new_item_details->id : $new_item->id;
                    $new_item->standards()->create([
                        'number' => $detail['number'],
                        'standard_type' => $detail['standard_type'],
                        'carbohydrate' => $detail['carbohydrate'],
                        'protein' => $detail['protein'],
                        'fat' => $detail['fat'],
                        'calories' => $detail['calories'],
                        'type' => $item['type'],
                        $id_column => $id_value,
                    ]);
                }
            }
        }

        if ($request->user_ids)
            $this->assignPlanToUsers(new Request([
                'user_ids' => $request->user_ids,
                'plan_id' => $plan->id,
                'is_work' => $request->is_work ?? false,
            ]));


        return response()->json([
            'status' => 'success',
            'message' => 'Plan created successfully',
            'plan' => PlanResource::make($plan),
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/diet/plan/user/{user_id}",
     *     summary="Retrieve all plans for a specific user",
     *     @OA\Parameter(
     *         name="user_id",
     *         in="path",
     *         description="User's ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Plans retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Plans retrieved successfully"),
     *             @OA\Property(
     *                 property="plan",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/PlanResource")
     *             )
     *         )
     *     ),
     *     @OA\Response(response="404", description="Plans not found")
     * )
     */
    public function getClientPlans($user_id): JsonResponse
    {
        $userPlans = UserPlan::query()
            ->where('user_id', $user_id)
            ->orderByDesc('id')
            ->get();

        if ($userPlans->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No plans founded',
            ], 404);
        }

        $userPlans->load(['plan.meals.items.standard.standardType']);

        $plans = $userPlans->map(function ($userPlan) {
            $plan = $userPlan->plan;
            $plan->is_work = $userPlan->is_work;
            return $plan;
        });


        return response()->json([
            'status' => 'success',
            'message' => 'plans retrieved successfully',
            'plan' => PlanResource::collection($plans),
        ]);
    }
}
