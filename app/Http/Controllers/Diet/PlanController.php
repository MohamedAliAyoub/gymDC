<?php

namespace App\Http\Controllers\Diet;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\FullplanRequest;
use App\Http\Resources\Diet\PlanResource;
use App\Models\Diet\Item;
use App\Models\Diet\Meal;
use App\Models\Diet\MealItem;
use App\Models\Diet\Plan;
use App\Models\Diet\UserPlan;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PlanController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/diet/plans",
     *     summary="Retrieve all plans",
     *     @OA\Response(
     *         response="200",
     *         description="Plans retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Plans retrieved successfully"),
     *             @OA\Property(
     *                 property="plans",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Plan Name"),
     *                     @OA\Property(property="note", type="string", example="This is a plan note"),
     *                     @OA\Property(
     *                         property="meals",
     *                         type="array",
     *                         @OA\Items(
     *                             type="object",
     *                             @OA\Property(property="name", type="string", example="Meal 1"),
     *                             @OA\Property(property="note", type="string", example="This is a meal note"),
     *                             @OA\Property(
     *                                 property="items",
     *                                 type="array",
     *                                 @OA\Items(
     *                                     type="object",
     *                                     @OA\Property(property="name", type="string", example="Item 1"),
     *                                     @OA\Property(property="type", type="integer", example=0),
     *                                     @OA\Property(
     *                                         property="details",
     *                                         type="array",
     *                                         @OA\Items(
     *                                             type="object",
     *                                             @OA\Property(property="name", type="string", example="Detail Name"),
     *                                             @OA\Property(property="number", type="integer", example=1),
     *                                             @OA\Property(property="standard_type", type="integer", example=1),
     *                                             @OA\Property(property="carbohydrate", type="number", format="float", example=30),
     *                                             @OA\Property(property="protein", type="number", format="float", example=20),
     *                                             @OA\Property(property="fat", type="number", format="float", example=10),
     *                                             @OA\Property(property="calories", type="number", format="float", example=100)
     *                                         )
     *                                     )
     *                                 )
     *                             )
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response="404", description="Plans not found")
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'total_calories' => 'nullable|numeric',
            'total_carbohydrate' => 'nullable|numeric',
            'total_protein' => 'nullable|numeric',
            'total_fat' => 'nullable|numeric',
            'name' => 'nullable|string',
        ]);
        $attributes = [
            'total_calories' => $request->total_calories,
            'total_carbohydrate' => $request->total_carbohydrate,
            'total_protein' => $request->total_protein,
            'total_fat' => $request->total_fat,
        ];
        $attributes = array_filter($attributes, function ($value) {
            return $value !== null;
        });

        $plans = Plan::filterByAttributes($attributes)
            ->searchByName($request->name)
            ->orderByDesc('id')
            ->paginate(10);

        $plans->each(function ($plan) {
            $plan->loadPlanDetails();
        });

       return $this->paginateResponse($plans, 'Plans retrieved successfully');
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
    public function create(Request $request): JsonResponse
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
            $plan->loadPlanDetails();

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Plan not found',
            ], 404);
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Plan retrieved successfully',
            'plan' => PlanResource::make($plan),
        ]);
    }


    /**
     * @OA\Delete(
     *     path="/api/diet/plan/{plan_id}",
     *     summary="Delete a plan and its associated meals and items",
     *     @OA\Parameter(
     *         name="plan_id",
     *         in="path",
     *         description="Plan's ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="Plan and its associated meals and items deleted successfully"),
     *     @OA\Response(response="404", description="Plan not found")
     * )
     */
    public function delete($plan_id): JsonResponse
    {
        $plan = Plan::query()->find($plan_id);
        if (!$plan) {
            return response()->json([
                'status' => 'error',
                'message' => 'Plan not found',
            ], 404);
        }

        // Detach all items from all meals in the plan
        foreach ($plan->meals as $meal) {
            foreach ($meal->items as $item) {
                $meal->items()->detach($item->id);
            }
        }

        // Detach all meals from the plan
        $plan->meals()->detach();

        // Detach the plan from all users
        $plan->userPlans()->delete();
        // Delete the plan
        $plan->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Plan and its associated meals and items deleted successfully',
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

    public function createOrEditFullPlan(FullplanRequest $request): JsonResponse
    {
        $plan = $request->id ? Plan::query()->findOrFail($request->id) : new Plan;
        $plan->fill($request->only(['name']))->save();

        if ($request->note)
            $plan->note()->updateOrCreate(['plan_id' => $plan->id], ['content' => $request->note, "user_id" => auth()->id()]);

        $plan->note()->update(['content' => $request->note, "user_id" => auth()->id()]);

        foreach ($request->meals as $meal) {
            $existing_meal = isset($meal['id']) ? $plan->meals()->where('meals.id', $meal['id'])->first() : null;

            if ($existing_meal) {
                $existing_meal->update($meal);
            } else {
                $existing_meal = $plan->meals()->create($meal);
            }

            if (isset($meal['note']) && $meal['note']) {
                $existing_meal->note()->update(['content' => $meal['note'], "user_id" => auth()->id()]);
            }


            foreach ($meal['items'] as $item) {
                if (isset($item['id']) && $item['id'] != null) {
                    $existing_item = Item::query()->where('items.id', $item['id'])->first();
                    if ($existing_item) {
                        $existing_item->update($item);
                    } else {
                        // Handle the case where the item does not exist
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Item not found in the meal in the plan '
                        ], 404);
                    }

                } else {
                    $existing_item = $existing_meal->items()->create($item);

                }

                foreach ($item['details'] as $detail) {
                    $existing_item_details = isset($detail['id']) ? $existing_item->itemDetails()->where('item_details.id', $detail['id'])->first() : null;


                    if ($existing_item_details) {
                        $existing_item_details->update($detail);
                    } else {
                        $existing_item_details = $existing_item->itemDetails()->create(['name' => $detail['name']]);
                    }

                    $id_column = $item['type'] == 0 ? 'item_details_id' : 'item_id';
                    $id_value = $item['type'] == 0 ? $existing_item_details->id : $existing_item->id;

//                    dd($id_column, $id_value, $detail['number'], $detail['standard_type'], $detail['carbohydrate'], $detail['protein'], $detail['fat'], $detail['calories'], $item['type']);
                    $standard = $existing_item->standards()->updateOrCreate(
                        [$id_column => $id_value],
                        [
                            'number' => $detail['number'],
                            'standard_type' => $detail['standard_type'],
                            'carbohydrate' => $detail['carbohydrate'],
                            'protein' => $detail['protein'],
                            'fat' => $detail['fat'],
                            'calories' => $detail['calories'],
                            'type' => $item['type'],
                        ]
                    );
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
            'message' => 'Plan updated successfully',
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
            ->paginate(15);

        if ($userPlans->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No plans founded',
            ], 404);
        }

        $userPlans->load(['plan.meals.items.standard.standardType']);

        $plans = $userPlans->map(function ($userPlan) {
            if (is_null($userPlan->plan)) {
                $userPlan->delete();
            }
            if ($userPlan->plan) {
                $plan = $userPlan->plan;
                $plan->is_work = $userPlan->is_work;
                return $plan;
            }
        });

      return $this->paginateResponse($plans, 'Plans retrieved successfully');
    }

    public function duplicatePlan($id): JsonResponse
    {
        // Retrieve the original plan
        $originalPlan = Plan::query()->findOrFail($id);
        $originalPlan->loadPlanDetails();


        // Create a copy of the plan
        $copiedPlan = $originalPlan->replicate();
        $copiedPlan->save();

        // Retrieve the meals of the original plan
        $meals = $originalPlan->meals;

        foreach ($meals as $meal) {
            // Create a copy of the meal
            $copiedMeal = $meal->replicate();
            $copiedMeal->save();


            // Retrieve the items of the meal
            $items = $meal->load('items')->items;


            foreach ($items as $item) {
                // Create a copy of the item
                $copiedItem = $item->replicate();
                $copiedItem->save();

                // Attach the copied item to the copied meal
                $copiedMeal->items()->attach($copiedItem->id);


                // Retrieve the standard of the item
                $standard = $item->standard;

                // Create a copy of the standard
                $copiedStandard = $standard->replicate();
                $copiedStandard->item_id = $copiedItem->id;
                $copiedStandard->save();

                // Retrieve the meal_items of the meal
                $mealItems = $meal->items;


                foreach ($mealItems as $mealItem) {
                    // Create a copy of the meal_item
                    $copiedMealItem = $mealItem->replicate();
                    $copiedMealItem->save();

                    // Replicate the pivot data
                    $copiedMealItem->pivot->meal_id = $copiedMeal->id;
                    $copiedMealItem->pivot->item_id = $copiedMealItem->id;
                    $copiedMealItem->pivot->save();
                }
            }


        }

        return response()->json([
            'status' => 'success',
            'message' => 'Plan duplicated successfully',
            'plan' => PlanResource::make($copiedPlan),
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/diet/plan/{plan_id}/meal/{meal_id}/item/{item_id}",
     *     summary="Delete an item from a meal in a plan",
     *     @OA\Parameter(
     *         name="plan_id",
     *         in="path",
     *         description="Plan's ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="meal_id",
     *         in="path",
     *         description="Meal's ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="item_id",
     *         in="path",
     *         description="Item's ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="Item deleted successfully"),
     *     @OA\Response(response="404", description="Meal or Item not found")
     * )
     */

    public function deleteItemFromPlan($plan_id, $meal_id, $item_id): JsonResponse
    {
        $plan = Plan::query()->findOrFail($plan_id);
        $meal = $plan->meals()->where('meal_id', $meal_id)->first();

        if ($meal) {
            $item = $meal->items()->where('item_id', $item_id)->first();
            if ($item) {
                $meal->items()->detach($item_id);
            } else {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Item not found',
                ], 404);
            }
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'Meal not found',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Pivot record deleted successfully',
        ]);
    }


    /**
     * @OA\Delete(
     *     path="/api/diet/plan/{plan_id}/meal/{meal_id}",
     *     summary="Delete a meal from a plan",
     *     @OA\Parameter(
     *         name="plan_id",
     *         in="path",
     *         description="Plan's ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="meal_id",
     *         in="path",
     *         description="Meal's ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="Pivot record deleted successfully"),
     *     @OA\Response(response="404", description="Meal not found")
     * )
     */
    public function deleteMealFromPlan($plan_id, $meal_id): JsonResponse
    {
        $plan = Plan::query()->findOrFail($plan_id);
        $meal = $plan->meals()->where('meal_id', $meal_id)->first();

        if ($meal) {
            $plan->meals()->detach($meal_id);
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'Meal not found',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Pivot record deleted successfully',
        ]);
    }


}
