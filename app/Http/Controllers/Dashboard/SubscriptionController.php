<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubscriptionRequest;
use App\Http\Resources\Dashboard\ClientSubscriptionsResource;
use App\Http\Resources\Dashboard\SubscriptionResource;
use App\Models\Dashboard\Subscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{


    /**
     * @OA\Get(
     *     path="/api/dashboard/subscription",
     *     summary="Retrieve paginated list of subscriptions",
     *     tags={"Dashboard"},
     *     @OA\Response(
     *         response=200,
     *         description="Subscriptions retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="subscriptions fetched successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Subscription")),
     *                 @OA\Property(property="first_page_url", type="string", example="http://example.com/api/dashboard/subscription?page=1"),
     *                 @OA\Property(property="from", type="integer", example=1),
     *                 @OA\Property(property="last_page", type="integer", example=10),
     *                 @OA\Property(property="last_page_url", type="string", example="http://example.com/api/dashboard/subscription?page=10"),
     *                 @OA\Property(property="next_page_url", type="string", example="http://example.com/api/dashboard/subscription?page=2"),
     *                 @OA\Property(property="path", type="string", example="http://example.com/api/dashboard/subscription"),
     *                 @OA\Property(property="per_page", type="integer", example=15),
     *                 @OA\Property(property="prev_page_url", type="string", example=null),
     *                 @OA\Property(property="to", type="integer", example=15),
     *                 @OA\Property(property="total", type="integer", example=100)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *     ),
     * )
     */

    public function index(): JsonResponse
    {
        $subscriptions = Subscription::query()->with(
            [
                'nutritionCoach',
                'workoutCoach',
                'client',
                'sale'
            ])->paginate(15);

        return response()->json([
            'status' => 'success',
            'message' => 'subscriptions fetched successfully',
            'data' => SubscriptionResource::collection($subscriptions),
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/dashboard/subscription/client/{id}",
     *     summary="Retrieve paginated list of subscriptions for a specific client",
     *     tags={"Dashboard"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Client's ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Client subscriptions retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="client subscriptions successfully"),
     *             @OA\Property(property="count", type="integer", example=10),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Subscription")),
     *                 @OA\Property(property="first_page_url", type="string", example="http://example.com/api/dashboard/subscription/client/1?page=1"),
     *                 @OA\Property(property="from", type="integer", example=1),
     *                 @OA\Property(property="last_page", type="integer", example=10),
     *                 @OA\Property(property="last_page_url", type="string", example="http://example.com/api/dashboard/subscription/client/1?page=10"),
     *                 @OA\Property(property="next_page_url", type="string", example="http://example.com/api/dashboard/subscription/client/1?page=2"),
     *                 @OA\Property(property="path", type="string", example="http://example.com/api/dashboard/subscription/client/1"),
     *                 @OA\Property(property="per_page", type="integer", example=15),
     *                 @OA\Property(property="prev_page_url", type="string", example=null),
     *                 @OA\Property(property="to", type="integer", example=15),
     *                 @OA\Property(property="total", type="integer", example=100)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *     ),
     * )
     */

    public function get_client_subscriptions(Request $request): JsonResponse
    {

        $subscriptions = Subscription::query()->where([
            ['client_id', $request->id]
        ])->with(['client' => function ($q) {
            $q->with('userDetails')->latest();
        }])->paginate(15);

        $user = $subscriptions->first()->client;
        $userDetails = $user->userDetails->first();


        return response()->json([
            'status' => 'success',
            'message' => 'client subscriptions  successfully',
            'count' => $subscriptions->count(),
            'data' => ClientSubscriptionsResource::collection($subscriptions),
            'user_details' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'age' =>$userDetails?->age,
                'weight' =>$userDetails?->weight,
                'height' => $userDetails?->height,
            ],
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/dashboard/subscription",
     *     summary="Create a new subscription",
     *     tags={"Dashboard"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nutrition_coach_id", type="integer", description="Must exist in nutrition coaches table"),
     *             @OA\Property(property="workout_coach_id", type="integer", description="Must exist in workout coaches table"),
     *             @OA\Property(property="client_id", type="integer", description="Must exist in clients table"),
     *             @OA\Property(property="sale_id", type="integer", description="Must exist in sales table"),
     *             @OA\Property(property="start_date", type="string", format="date", example="2022-01-01"),
     *             @OA\Property(property="end_date", type="string", format="date", example="2022-12-31"),
     *             @OA\Property(property="status", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Subscription created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Subscription")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation errors",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Validation errors"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *     ),
     * )
     */
    public function store(SubscriptionRequest $request): JsonResponse
    {
        $subscription = Subscription::create($request->validated());
        return response()->json(SubscriptionResource::make(
            $subscription->load('nutritionCoach', 'workoutCoach', 'client', 'sale')),
            201);
    }

    /**
     * @OA\Get(
     *     path="/api/dashboard/subscription/{id}",
     *     summary="Retrieve a specific subscription",
     *     tags={"Dashboard"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Subscription's ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Subscription retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Subscription")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Subscription not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Subscription not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *     ),
     * )
     */

    public function show($id): JsonResponse
    {
        $subscription = Subscription::findOrFail($id);
        if (!$subscription) {
            return response()->json(['message' => 'Subscription not found'], 404);
        }
        return response()->json(SubscriptionResource::make(
            $subscription->load('nutritionCoach', 'workoutCoach', 'client', 'sale')));
    }

    /**
     * @OA\Put(
     *     path="/api/dashboard/subscription/{id}",
     *     summary="Update a specific subscription",
     *     tags={"Dashboard"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Subscription's ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nutrition_coach_id", type="integer", description="Must exist in nutrition coaches table"),
     *             @OA\Property(property="workout_coach_id", type="integer", description="Must exist in workout coaches table"),
     *             @OA\Property(property="client_id", type="integer", description="Must exist in clients table"),
     *             @OA\Property(property="sale_id", type="integer", description="Must exist in sales table"),
     *             @OA\Property(property="start_date", type="string", format="date", example="2022-01-01"),
     *             @OA\Property(property="end_date", type="string", format="date", example="2022-12-31"),
     *             @OA\Property(property="status", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Subscription updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Subscription")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation errors",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Validation errors"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Subscription not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Subscription not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *     ),
     * )
     */

    public function update(SubscriptionRequest $request, $id): JsonResponse
    {
        $subscription = Subscription::findOrFail($id);
        if (!$subscription) {
            return response()->json(['message' => 'Subscription not found'], 404);
        }
        $subscription->update($request->validated());
        return response()->json(SubscriptionResource::make(
            $subscription->load('nutritionCoach', 'workoutCoach', 'client', 'sale')));
    }

    public function destroy($id): JsonResponse
    {
        $subscription = Subscription::find($id);
        if (!$subscription) {
            return response()->json(['message' => 'Subscription not found'], 404);
        }
        $subscription->delete();
        return response()->json(['message' => 'Subscription deleted successfully']);
    }


}
