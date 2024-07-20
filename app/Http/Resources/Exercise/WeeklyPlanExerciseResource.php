<?php

namespace App\Http\Resources\Exercise;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WeeklyPlanExerciseResource extends JsonResource
{
    /**
     * @OA\Schema(
     *     schema="PlanExercise",
     *     type="object",
     *     @OA\Property(property="id", type="integer", example=1),
     *     @OA\Property(property="plan_id", type="string", example="bulk_plan"),
     *     @OA\Property(property="is_done", type="boolean", example=false),
     *     @OA\Property(property="status", type="integer", example=1),
     *     @OA\Property(property="exercises_count", type="integer", example=3),
     *     @OA\Property(
     *         property="exercises",
     *         type="array",
     *         @OA\Items(ref="#/components/schemas/Exercise")
     *     ),
     *     @OA\Property(property="note", type="string", nullable=true),
     *     @OA\Property(property="done", type="boolean", nullable=true),
     *     @OA\Property(property="rest_day", type="boolean", example=false)
     * )
     *
     * @OA\Schema(
     *     schema="Exercise",
     *     type="object",
     *     @OA\Property(property="id", type="integer", example=1),
     *     @OA\Property(property="name", type="string", example="shoulder"),
     *     @OA\Property(property="is_done", type="boolean", example=false),
     *     @OA\Property(property="status", type="integer", example=1),
     *     @OA\Property(
     *         property="details",
     *         type="array",
     *         @OA\Items(ref="#/components/schemas/ExerciseDetail")
     *     ),
     *     @OA\Property(property="note", type="string", nullable=true)
     * )
     *
     * @OA\Schema(
     *     schema="ExerciseDetail",
     *     type="object",
     *     @OA\Property(property="id", type="integer", example=1),
     *     @OA\Property(property="exercise_id", type="integer", example=1),
     *     @OA\Property(property="exercise", type="string", example="shoulder"),
     *     @OA\Property(property="is_done", type="boolean", example=false),
     *     @OA\Property(property="sets", type="integer", example=0),
     *     @OA\Property(property="rir", type="integer", example=15),
     *     @OA\Property(property="reps", type="integer", example=12),
     *     @OA\Property(property="rest", type="integer", example=5),
     *     @OA\Property(property="weight", type="number", format="float", nullable=true),
     *     @OA\Property(property="unit", type="string", nullable=true),
     *     @OA\Property(property="day_names", type="string", nullable=true),
     *     @OA\Property(property="is_full", type="boolean", example=true),
     *     @OA\Property(property="duration", type="integer", example=10)
     * )
     */

    public function toArray(Request $request): array
    {

        return [
            'id' => $this->id,
            'name' => $this->name,
            'plans' => PlanExerciseResource::collection($this->planExercises),
        ];
    }
}
