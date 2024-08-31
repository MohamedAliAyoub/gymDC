<?php

namespace App\Http\Controllers\Exercise;

use App\Http\Controllers\Controller;
use App\Models\Exercise\NoteExercise;
use Illuminate\Http\Request;

class NoteExerciseCotroller extends Controller
{


    /**
     * @OA\Get(
     *     path="/api/exercise/note-exercise",
     *     summary="Retrieve note exercises with pagination 15 items per page",
     *     tags={"Exercise"},
     *     @OA\Response(response="200", description="Note exercises retrieved successfully")
     * )
     */
    public function index()
    {
        $noteExercises = NoteExercise::query()->paginate(15);

     return $this->paginateResponse($noteExercises, 'Note exercises retrieved successfully');
    }

    /**
     * @OA\Schema(
     *     schema="NoteExercise",
     *     type="object",
     *     @OA\Property(property="user_id", type="integer", description="Must exist in users table"),
     *     @OA\Property(property="title", type="string"),
     *     @OA\Property(property="content", type="string"),
     *     @OA\Property(property="status", type="boolean"),
     *     @OA\Property(property="exercise_plan_id", type="integer", description="Must exist in exercise plans table"),
     *     @OA\Property(property="exercise_id", type="integer", description="Must exist in exercises table"),
     * )
     */
    public function create(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'string|nullable',
            'content' => 'required|string',
            'status' => 'nullable|boolean',
            'plan_exercise_id' => 'nullable|exists:plan_exercises,id',
            'exercise_id' => 'nullable|exists:exercises,id',
        ]);

        $noteExercise = NoteExercise::query()->create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Note exercise created successfully',
            'noteExercise' => $noteExercise,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/exercise/note-exercise/{id}",
     *     summary="Retrieve a specific note exercise",
     *     tags={"Exercise"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Note exercise's id",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Note exercise retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/NoteExercise")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Note exercise not found",
     *     ),
     * )
     */
    public function show(int $id)
    {
        $noteExercise = NoteExercise::query()->find($id);

        if (!$noteExercise) {
            return response()->json([
                'status' => 'error',
                'message' => 'Note exercise not found',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Note exercise retrieved successfully',
            'noteExercise' => $noteExercise,
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/exercise/note-exercise/{id}",
     *     summary="Update a specific note exercise",
     *     tags={"Exercise"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Note exercise's id",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="user_id", type="integer", description="Must exist in users table"),
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="content", type="string"),
     *             @OA\Property(property="status", type="boolean"),
     *             @OA\Property(property="exercise_plan_id", type="integer", description="Must exist in exercise plans table"),
     *             @OA\Property(property="exercise_id", type="integer", description="Must exist in exercises table"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Note exercise updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/NoteExercise")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Note exercise not found",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *     ),
     * )
     */
    public function update(Request $request, int $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'string|nullable',
            'content' => 'required|string',
            'status' => 'boolean',
            'exercise_plan_id' => 'nullable|exists:exercise_plans,id',
            'exercise_id' => 'nullable|exists:exercises,id',
        ]);

        $noteExercise = NoteExercise::query()->find($id);

        if (!$noteExercise) {
            return response()->json([
                'status' => 'error',
                'message' => 'Note exercise not found',
            ], 404);
        }

        $noteExercise->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Note exercise updated successfully',
            'noteExercise' => $noteExercise,
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/exercise/note-exercise/{id}",
     *     summary="Delete a specific note exercise",
     *     tags={"Exercise"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Note exercise's id",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Note exercise deleted successfully",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Note exercise not found",
     *     ),
     * )
     */
    public function delete(int $id)
    {
        $noteExercise = NoteExercise::query()->find($id);

        if (!$noteExercise) {
            return response()->json([
                'status' => 'error',
                'message' => 'Note exercise not found',
            ], 404);
        }

        $noteExercise->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Note exercise deleted successfully',
        ]);
    }
}
