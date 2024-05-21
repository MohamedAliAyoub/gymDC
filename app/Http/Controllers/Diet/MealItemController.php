<?php

namespace App\Http\Controllers\Diet;

use App\Http\Controllers\Controller;
use App\Models\Diet\Meal;
use App\Models\Diet\MealItem;
use Illuminate\Http\Request;

class MealItemController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/diet/mealitem",
     *     summary="Retrieve all meal items",
     *     @OA\Response(response="200", description="Meal items retrieved successfully")
     * )
     */
    public function index()
    {
        $mealItems = MealItem::query()->paginate(15);

        return response()->json([
            'status' => 'success',
            'message' => 'Meal items retrieved successfully',
            'mealItems' => $mealItems,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/diet/mealitem",
     *     summary="Create a new meal item",
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="Meal item's name",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="meal_id",
     *         in="query",
     *         description="Meal id",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="item_id",
     *         in="query",
     *         description="Item id",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Meal item's status",
     *         required=true,
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Response(response="200", description="Meal item created successfully"),
     *     @OA\Response(response="400", description="Validation errors")
     * )
     */
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string',
            'meal_id' => 'required|integer',
            'item_id' => 'required|integer',
            'status' => 'required|boolean',
        ]);

        $mealItem = MealItem::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Meal item created successfully',
            'mealItem' => $mealItem,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/diet/mealitems",
     *     summary="Create new meal items",
     *     @OA\Parameter(
     *         name="meal_items",
     *         in="query",
     *         description="Array of meal items",
     *         required=true,
     *         @OA\Schema(type="array", @OA\Items(ref="#/components/schemas/MealItem"))
     *     ),
     *     @OA\Response(response="200", description="Meal items created successfully"),
     *     @OA\Response(response="400", description="Validation errors")
     * )
     */
    public function storeMealItems(Request $request)
    {
        $request->validate([
            'meal_name' => 'required|string', // 'meal_name' is not defined in the request body, should be 'meal_id
            'meal_items' => 'required|array',
            'meal_items.*.item_id' => 'required|integer|exists:items,id',
        ]);

        $meal = Meal::query()->create([
            'name' => $request->meal_name,
        ]);
        foreach ($request->meal_items as $mealItemData) {
            $mealItemData['meal_id'] = $meal->id;
            $mealItemData['item_id'] = $mealItemData['item_id'];
             MealItem::create($mealItemData);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Meal items created successfully',
            'mealItems' => $meal->load('items'),
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/diet/mealitem/{mealItem}",
     *     summary="Update an existing meal item",
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="Meal item's name",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="meal_id",
     *         in="query",
     *         description="Meal id",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="item_id",
     *         in="query",
     *         description="Item id",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Meal item's status",
     *         required=true,
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Response(response="200", description="Meal item updated successfully"),
     *     @OA\Response(response="400", description="Validation errors")
     * )
     */
    public function update(Request $request, $id)
    {
        try {
            $mealItem = MealItem::query()->findOrFail($id);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Meal item not found',
            ], 404);
        }
        $request->validate([
            'name' => 'required|string',
            'meal_id' => 'required|integer',
            'item_id' => 'required|integer',
            'status' => 'required|boolean',
        ]);

        $mealItem->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Meal item updated successfully',
            'mealItem' => $mealItem,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/diet/mealitem/{mealItem}",
     *     summary="Retrieve a meal item",
     *     @OA\Response(response="200", description="Meal item retrieved successfully"),
     *     @OA\Response(response="404", description="Meal item not found")
     * )
     */
    public function show($id)
    {
        try {
            $mealItem = MealItem::query()->findOrFail($id);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Meal item not found',
            ], 404);
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Meal item retrieved successfully',
            'mealItem' => $mealItem,
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/diet/mealitem/{mealItem}",
     *     summary="Delete a meal item",
     *     @OA\Response(response="200", description="Meal item deleted successfully"),
     *     @OA\Response(response="404", description="Meal item not found")
     * )
     */
    public function delete($id)
    {
        try {
            $mealItem = MealItem::query()->findOrFail($id);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Meal item not found',
            ], 404);
        }
        $mealItem->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Meal item deleted successfully',
        ]);
    }


}
