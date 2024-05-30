<?php

namespace App\Http\Controllers\Exercise;

use App\Http\Controllers\Controller;
use App\Http\Resources\Exercise\ExerciseResource;
use App\Models\Exercise\Exercise;
use Illuminate\Http\Request;

class ExerciseController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/exercise/exercise",
     *     summary="Retrieve exercises with pagination 15 items per page",
     *     @OA\Response(response="200", description="Exercises retrieved successfully")
     * )
     */
    public function index()
    {
        $exercises = Exercise::query()->paginate(15);

        return response()->json([
            'status' => 'success',
            'message' => 'Exercises retrieved successfully',
            'exercises' => $exercises,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/exercise/exercise",
     *     summary="Create a new exercise",
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="Exercise's name",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Exercise's status",
     *         required=false,
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Response(response="200", description="Exercise created successfully"),
     *     @OA\Response(response="422", description="Validation errors")
     * )
     */
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'status' => 'nullable|boolean',
        ]);

        $exercise = Exercise::query()->create([
            'name' => $request->name,
            'status' => $request->status ?? true
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Exercise created successfully',
            'exercise' => $exercise,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/exercise/exercise/{id}",
     *     summary="Retrieve a specific exercise",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Exercise's id",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="Exercise retrieved successfully"),
     *     @OA\Response(response="404", description="Exercise not found")
     * )
     */
    public function show($id)
    {
        $exercise = Exercise::query()->find($id);

        if (!$exercise) {
            return response()->json([
                'status' => 'error',
                'message' => 'Exercise not found',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Exercise retrieved successfully',
            'exercise' => ExerciseResource::make($exercise),
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/exercise/exercise/{id}",
     *     summary="Update a specific exercise",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Exercise's id",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="Exercise's name",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Exercise's status",
     *         required=false,
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Response(response="200", description="Exercise updated successfully"),
     *     @OA\Response(response="404", description="Exercise not found"),
     *     @OA\Response(response="422", description="Validation errors")
     * )
     */
    public function update(Request $request, $id)
    {
        try {
            $exercise = Exercise::query()->findOrFail($id);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Exercise not found',
            ], 404);
        }
        $request->validate([
            'name' => 'required|string',
            'status' => 'nullable|boolean',
        ]);

        $exercise->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Exercise updated successfully',
            'exercise' => $exercise,
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/exercise/exercise/{id}",
     *     summary="Delete a specific exercise",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Exercise's id",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="Exercise deleted successfully"),
     *     @OA\Response(response="404", description="Exercise not found")
     * )
     */
    public function delete($id)
    {
        $exercise = Exercise::query()->find($id);

        if (!$exercise) {
            return response()->json([
                'status' => 'error',
                'message' => 'Exercise not found',
            ], 404);
        }

        $exercise->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Exercise deleted successfully',
        ]);
    }
}
