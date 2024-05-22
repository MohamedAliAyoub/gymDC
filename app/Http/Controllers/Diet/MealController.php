<?php

namespace App\Http\Controllers\Diet;

use App\Http\Controllers\Controller;
use App\Models\Diet\Meal;
use App\Models\Diet\UserMeal;
use Illuminate\Http\Request;

class MealController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/diet/meal",
     *     summary="Retrieve all meals",
     *     @OA\Response(response="200", description="Meals retrieved successfully")
     * )
     */
    public function index()
    {
        $meals = Meal::query()->paginate(15);

        return response()->json([
            'status' => 'success',
            'message' => 'Meals retrieved successfully',
            'meals' => $meals,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/diet/meal",
     *     summary="Create a new meal",
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="Meal's name",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Meal's status",
     *         required=false,
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Response(response="200", description="Meal created successfully"),
     *     @OA\Response(response="400", description="Validation errors")
     * )
     */
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'status' => 'nullable',
        ]);

        $meal = Meal::query()->create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Meal created successfully',
            'meal' => $meal,
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/diet/meal/{meal}",
     *     summary="Update an existing meal",
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="Meal's name",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Meal's status",
     *         required=true,
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Response(response="200", description="Meal updated successfully"),
     *     @OA\Response(response="400", description="Validation errors")
     * )
     */
    public function update(Request $request, $id)
    {
        try {
            $meal = Meal::query()->findOrFail($id);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Meal not found',
            ], 404);
        }
        $request->validate([
            'name' => 'required|string',
            'status' => 'required|boolean',
        ]);

        $meal->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Meal updated successfully',
            'meal' => $meal,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/diet/meal/{meal}",
     *     summary="Retrieve a meal",
     *     @OA\Response(response="200", description="Meal retrieved successfully"),
     *     @OA\Response(response="404", description="Meal not found")
     * )
     */
    public function show($id)
    {
        try {
            $meal = Meal::query()->findOrFail($id);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Meal not found',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Meal retrieved successfully',
            'meal' => $meal,
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/diet/meal/{meal}",
     *     summary="Delete a meal",
     *     @OA\Response(response="200", description="Meal deleted successfully"),
     *     @OA\Response(response="404", description="Meal not found")
     * )
     */
    public function delete($id)
    {
        try {
            $meal = Meal::query()->findOrFail($id);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Meal not found',
            ], 404);
        }
        $meal->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Meal deleted successfully',
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/diet/meal/assign",
     *     summary="Assign a meal to a user",
     *     @OA\Parameter(
     *         name="meal_id",
     *         in="query",
     *         description="Meal's ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="Meal assigned to user successfully"),
     *     @OA\Response(response="400", description="Validation errors")
     * )
     */
    public function assignMealToUser(Request $request)
    {
        $request->validate([
            'meal_id' => 'required|integer|exists:meals,id',
        ]);

        $userMeal =UserMeal::query()->create([
            'user_id' => auth()->id(),
            'meal_id' => $request->meal_id,
            'status' => true,
            'is_eaten' => 1,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Meal assigned to user successfully',
            'userMeal' => $userMeal,
        ]);
    }
}
