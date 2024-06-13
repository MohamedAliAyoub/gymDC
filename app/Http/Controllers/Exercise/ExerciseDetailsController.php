<?php

namespace App\Http\Controllers\Exercise;

use App\Http\Controllers\Controller;
use App\Models\Exercise\ExerciseDetails;
use Illuminate\Http\Request;

class ExerciseDetailsController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/exercise/exercise-details",
     *     summary="Retrieve exercises-details with pagination 15 items per page",
     *     @OA\Response(response="200", description="Exercises retrieved successfully")
     * )
     */
    public function index()
    {
        $exerciseDetails = ExerciseDetails::query()->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Exercise details retrieved successfully',
            'exerciseDetails' => $exerciseDetails,
        ]);
    }

    /**
     * @OA\Schema(
     *     schema="ExerciseDetails",
     *     type="object",
     *     @OA\Property(property="name", type="string"),
     *     @OA\Property(property="previous", type="integer"),
     *     @OA\Property(property="rir", type="integer"),
     *     @OA\Property(property="tempo", type="string"),
     *     @OA\Property(property="rest", type="integer"),
     *     @OA\Property(property="kg", type="integer"),
     *     @OA\Property(property="reps", type="integer"),
     *     @OA\Property(property="status", type="boolean"),
     *     @OA\Property(property="exercise_id", type="integer"),
     *     @OA\Property(property="duration", type="integer"),
     * )
     */
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'string|nullable',
            'previous' => 'integer|nullable',
            'rir' => 'integer|nullable',
            'tempo' => 'string|nullable',
            'rest' => 'integer|nullable',
            'kg' => 'integer|nullable',
            'reps' => 'integer|nullable',
            'status' => 'nullable|boolean',
            'exercise_id' => 'required|exists:exercises,id',
            'duration' => 'nullable|integer',
        ]);
        $exerciseDetails = ExerciseDetails::query()->create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Exercise details created successfully',
            'exerciseDetails' => $exerciseDetails,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/exercise/exercise-details/{id}",
     *     summary="Retrieve a specific exercise detail",
     *     tags={"Exercise"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Exercise detail's id",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Exercise detail retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/ExerciseDetails")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Exercise detail not found",
     *     ),
     * )
     */

    public function show(int $id)
    {
        $exerciseDetails = ExerciseDetails::query()->find($id);

        if (!$exerciseDetails) {
            return response()->json([
                'status' => 'error',
                'message' => 'Exercise details not found',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Exercise details retrieved successfully',
            'exerciseDetails' => $exerciseDetails,
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/exercise/exercise-details/{id}",
     *     summary="Update a specific exercise detail",
     *     tags={"Exercise"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Exercise detail's id",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="previous", type="integer"),
     *             @OA\Property(property="rir", type="integer"),
     *             @OA\Property(property="tempo", type="string"),
     *             @OA\Property(property="rest", type="integer"),
     *             @OA\Property(property="kg", type="integer"),
     *             @OA\Property(property="reps", type="integer"),
     *             @OA\Property(property="status", type="boolean"),
     *             @OA\Property(property="duration", type="integer"),
     *             @OA\Property(property="exercise_id", type="integer", description="Must exist in exercises table"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Exercise details updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/ExerciseDetails")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Exercise details not found",
     *     ),
     * )
     */
    public function update(Request $request, int $id)
    {
        $request->validate([
            'name' => 'string|nullable',
            'previous' => 'integer|nullable',
            'rir' => 'integer|nullable',
            'tempo' => 'string|nullable',
            'rest' => 'integer|nullable',
            'kg' => 'integer|nullable',
            'reps' => 'integer|nullable',
            'status' => 'nullable|boolean',
            'exercise_id' => 'required|exists:exercises,id',
            'duration' => 'nullable|integer',
        ]);

        $exerciseDetails = ExerciseDetails::query()->find($id);

        if (!$exerciseDetails) {
            return response()->json([
                'status' => 'error',
                'message' => 'Exercise details not found',
            ], 404);
        }

        $exerciseDetails->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Exercise details updated successfully',
            'exerciseDetails' => $exerciseDetails,
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/exercise/exercise-details/previous/{id}",
     *     summary="Update a specific exercise detail only previous",
     *     tags={"Exercise"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Exercise detail's id",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="previous", type="integer"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Exercise details updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/ExerciseDetails")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Exercise details not found",
     *     ),
     * )
     */
    public function updatePrevious(Request $request, int $id)
    {
        $request->validate([
            'previous' => 'required|integer',
        ]);
        $exerciseDetails = ExerciseDetails::query()->find($id);

        if (!$exerciseDetails) {
            return response()->json([
                'status' => 'error',
                'message' => 'Exercise details not found',
            ], 404);
        }

        $exerciseDetails->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Exercise details updated successfully',
            'exerciseDetails' => $exerciseDetails,
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/exercise/exercise-details/{id}",
     *     summary="Delete a specific exercise detail",
     *     tags={"Exercise"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Exercise detail's id",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Exercise details deleted successfully",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Exercise details not found",
     *     ),
     * )
     */
    public function delete(int $id)
    {
        $exerciseDetails = ExerciseDetails::query()->find($id);

        if (!$exerciseDetails) {
            return response()->json([
                'status' => 'error',
                'message' => 'Exercise details not found',
            ], 404);
        }

        $exerciseDetails->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Exercise details deleted successfully',
        ]);
    }
}
