<?php

namespace App\Http\Controllers\Exercise;

use App\Http\Controllers\Controller;
use App\Models\Exercise\DoneExercise;
use Illuminate\Http\Request;

class DoneExerciseController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/exercise/done",
     *     summary="Retrieve done exercises with pagination 15 items per page",
     *     @OA\Response(response="200", description="Done exercises retrieved successfully")
     * )
     */
    public function index()
    {
        $doneExercises = DoneExercise::query()->paginate(15);

        return response()->json([
            'status' => 'success',
            'message' => 'Done exercises retrieved successfully',
            'doneExercises' => $doneExercises,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/exercise/done",
     *     summary="Create a new done exercise",
     *     @OA\Parameter(
     *         name="exercise_id",
     *         in="query",
     *         description="Exercise's id",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="plan_id",
     *         in="query",
     *         description="Plan's id",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="reps",
     *         in="query",
     *         description="Reps",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="kg",
     *         in="query",
     *         description="Kg",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="rir",
     *         in="query",
     *         description="Rir",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="tempo",
     *         in="query",
     *         description="Tempo",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="rest",
     *         in="query",
     *         description="Rest",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Status",
     *         required=false,
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Response(response="200", description="Done exercise created successfully"),
     *     @OA\Response(response="422", description="Validation errors")
     * )
     */
    public function create(Request $request)
    {
        $request->validate([
            'exercise_id' => 'required|exists:exercises,id',
            'reps' => 'nullable|integer',
            'kg' => 'nullable|integer',
            'rir' => 'nullable|integer',
            'tempo' => 'nullable|string',
            'rest' => 'nullable|integer',
            'run_duration' => 'nullable|integer',
            'sets' => 'nullable|integer',
        ]);

        $doneExercise = DoneExercise::query()
            ->where('user_id', auth()->id())
            ->where('exercise_id', $request->exercise_id)
            ->whereDate('created_at', now()->toDateString())
            ->first();

        if ($doneExercise) {
            $doneExercise->update(['is_done' => !$doneExercise->is_done]);
        } else {
            $doneExercise = DoneExercise::create([
                'user_id' => auth()->id(),
                'exercise_id' => $request->exercise_id,
                'reps' => $request->reps,
                'kg' => $request->kg,
                'rir' => $request->rir,
                'tempo' => $request->tempo,
                'rest' => $request->rest,
                'run_duration' => $request->run_duration,
                'sets' => $request->sets,
                'status' => 1,
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Done exercise created or updated successfully',
            'doneExercise' => $doneExercise,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/exercise/done/{id}",
     *     summary="Retrieve a specific done exercise",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Done exercise's id",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="Done exercise retrieved successfully"),
     *     @OA\Response(response="404", description="Done exercise not found")
     * )
     */
    public function show(int $id)
    {
        $doneExercise = DoneExercise::query()->find($id);

        if (!$doneExercise) {
            return response()->json([
                'status' => 'error',
                'message' => 'Done exercise not found',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Done exercise retrieved successfully',
            'doneExercise' => $doneExercise,
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/exercise/done/{id}",
     *     summary="Update a specific done exercise",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Done exercise's id",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="exercise_id",
     *         in="query",
     *         description="Exercise's id",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="plan_id",
     *         in="query",
     *         description="Plan's id",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="reps",
     *         in="query",
     *         description="Reps",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="kg",
     *         in="query",
     *         description="Kg",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="rir",
     *         in="query",
     *         description="Rir",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="tempo",
     *         in="query",
     *         description="Tempo",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="rest",
     *         in="query",
     *         description="Rest",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Status",
     *         required=false,
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Response(response="200", description="Done exercise updated successfully"),
     *     @OA\Response(response="404", description="Done exercise not found")
     * )
     */
    public function update(Request $request, int $id)
    {
        $request->validate([
            'exercise_id' => 'nullable|exists:exercises,id',
            'plan_id' => 'nullable|exists:exercise_plans,id',
            'reps' => 'nullable|integer',
            'kg' => 'nullable|integer',
            'rir' => 'nullable|integer',
            'tempo' => 'nullable|string',
            'rest' => 'nullable|integer',
            'status' => 'nullable|boolean',
            'sets' => 'nullable|integer',
        ]);

        $doneExercise = DoneExercise::query()->find($id);

        if (!$doneExercise) {
            return response()->json([
                'status' => 'error',
                'message' => 'Done exercise not found',
            ], 404);
        }

        $doneExercise->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Done exercise updated successfully',
            'doneExercise' => $doneExercise,
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/exercise/done/{id}",
     *     summary="Delete a specific done exercise",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Done exercise's id",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="Done exercise deleted successfully"),
     *     @OA\Response(response="404", description="Done exercise not found")
     * )
     */
    public function delete(int $id)
    {
        $doneExercise = DoneExercise::query()->find($id);

        if (!$doneExercise) {
            return response()->json([
                'status' => 'error',
                'message' => 'Done exercise not found',
            ], 404);
        }

        $doneExercise->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Done exercise deleted successfully',
        ]);
    }
}
