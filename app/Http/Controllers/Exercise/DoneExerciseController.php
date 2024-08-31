<?php

namespace App\Http\Controllers\Exercise;

use App\Http\Controllers\Controller;
use App\Models\Exercise\DoneExercise;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DoneExerciseController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/exercise/done",
     *     summary="Retrieve done exercises with pagination 15 items per page",
     *     @OA\Response(response="200", description="Done exercises retrieved successfully")
     * )
     */
    public function index(): JsonResponse
    {
        $doneExercises = DoneExercise::query()->paginate(15);

      return $this->paginateResponse($doneExercises, 'Done exercises retrieved successfully');
    }

    /**
     * @OA\Post(
     *     path="/api/exercise/done",
     *     summary="Create or update a done exercise",
     *     @OA\Parameter(
     *         name="exercise_id",
     *         in="query",
     *         description="The ID of the exercise",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="reps",
     *         in="query",
     *         description="The number of repetitions of the exercise",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="kg",
     *         in="query",
     *         description="The weight used in the exercise, in kilograms",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="rir",
     *         in="query",
     *         description="The reps in reserve",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="tempo",
     *         in="query",
     *         description="The tempo of the exercise",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="rest",
     *         in="query",
     *         description="The rest time in seconds",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="The status of the exercise",
     *         required=false,
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Response(response="200", description="Done exercise created or updated successfully"),
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
                'is_done' => 1,
            ]);
        }

        $doneResponse = [
            'id' => $doneExercise->id,
            'is_done' => $doneExercise->is_done
        ];
        if ($request->run_duration != null) {
            $doneResponse['run_duration'] = $request->run_duration;
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Done exercise created or updated successfully',
            'doneExercise' => $doneResponse,

        ]);
    }




    public function createWithDetails(Request $request)
    {
        $request->validate([
            'exercise_details_id' => 'required|exists:exercise_details,id',
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
                'is_done' => 1,
            ]);
        }

        $doneResponse = [
            'id' => $doneExercise->id,
            'is_done' => $doneExercise->is_done
        ];
        if ($request->run_duration != null) {
            $doneResponse['run_duration'] = $request->run_duration;
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Done exercise created or updated successfully',
            'doneExercise' => $doneResponse,

        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/exercise/createWithPlan",
     *     operationId="createWithPlan",
     *     tags={"Exercise"},
     *     summary="Create an exercise with a plan",
     *     description="Create a new exercise entry associated with a plan",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="exercise_name", type="string", example="Push Up"),
     *             @OA\Property(property="duration", type="integer", example=30),
     *             @OA\Property(property="plan_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Exercise created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Exercise created successfully"),
     *             @OA\Property(property="exercise", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="exercise_name", type="string", example="Push Up"),
     *                 @OA\Property(property="duration", type="integer", example=30),
     *                 @OA\Property(property="plan_id", type="integer", example=1)
     *             )
     *         )
     *     ),
     *     @OA\Response(response=400, description="Bad Request"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    public function createWithPlan(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
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
            ->where('plan_id', $request->plan_id)
            ->whereDate('created_at', now()->toDateString())
            ->first();

        if ($doneExercise) {
            $doneExercise->update(['is_done' => !$doneExercise->is_done]);
        } else {
            $doneExercise = DoneExercise::create([
                'user_id' => auth()->id(),
                'plan_id' => $request->plan_id,
                'reps' => $request->reps,
                'kg' => $request->kg,
                'rir' => $request->rir,
                'tempo' => $request->tempo,
                'rest' => $request->rest,
                'run_duration' => $request->run_duration,
                'sets' => $request->sets,
                'status' => 1,
                'is_done' => 1,
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Done exercise created or updated successfully',
            'doneExercise' => [
                'id' => $doneExercise->id,
                'is_done' => $doneExercise->is_done
            ],
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
