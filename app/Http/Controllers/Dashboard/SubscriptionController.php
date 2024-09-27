<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\SubscriptionStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\SubscriptionRequest;
use App\Http\Requests\SubscriptionUpdateRequest;
use App\Http\Resources\Dashboard\ClientSubscriptionsResource;
use App\Http\Resources\Dashboard\SubscriptionResource;
use App\Http\Resources\Dashboard\SubscriptionUserDetailsResource;
use App\Models\Dashboard\Subscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{


    /**
     * @OA\Schema(
     *     schema="Subscription",
     *     type="object",
     *     @OA\Property(property="id", type="integer", example=1),
     *     @OA\Property(property="nutrition_coach_id", type="integer", example=1),
     *     @OA\Property(property="workout_coach_id", type="integer", example=1),
     *     @OA\Property(property="client_id", type="integer", example=1),
     *     @OA\Property(property="sale_id", type="integer", example=1),
     *     @OA\Property(property="duration", type="integer", example=30),
     *     @OA\Property(property="status", type="boolean", example=true),
     *     @OA\Property(property="package", type="string", example="Basic"),
     *     @OA\Property(property="started_at", type="string", format="date", example="2022-01-01"),
     *     @OA\Property(property="paid_amount", type="number", format="float", example=100.00),
     *     @OA\Property(property="freeze_start_at", type="string", format="date", example="2022-01-01"),
     *     @OA\Property(property="freeze_duration", type="integer", example=7),
     *     @OA\Property(property="paid_at", type="string", format="date", example="2022-01-01"),
     *     @OA\Property(property="created_at", type="string", format="date", example="2022-01-01"),
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

        return $this->paginateResponse($subscriptions, 'Clients retrieved successfully');
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
     *             @OA\Property(property="message", type="string", example="client subscriptions fetched successfully"),
     *             @OA\Property(property="count", type="integer", example=10),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Subscription")),
     *             @OA\Property(property="active_subscription", type="object", @OA\Schema(ref="#/components/schemas/Subscription")),
     *             @OA\Property(property="user_details", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", example="john.doe@example.com"),
     *                 @OA\Property(property="phone", type="string", example="1234567890"),
     *                 @OA\Property(property="age", type="integer", example=30),
     *                 @OA\Property(property="weight", type="integer", example=70),
     *                 @OA\Property(property="height", type="integer", example=180),
     *                 @OA\Property(property="in_body_image", type="string", example="image_url")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No subscriptions found for the given client_id",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="No subscriptions found for the given client_id")
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

        $subscriptions = Subscription::query()
            ->where('client_id', $request->id)
            ->with(['client' => function ($q) {
                $q->with('userDetails', function ($q) {
                    $q->latest();
                });
            }])
            ->orderByDesc('id')->get();


        if ($subscriptions->isEmpty()) {
            return response()->json([
                'data' => [],
                'status' => 'error',
                'message' => 'No subscriptions found for the given client_id',
            ], 200);
        }

        $activeSubscription = $subscriptions->where('status', 1)->first() ;

        $user =  $subscriptions->first()?->client->with(['getLatestUserDetails'])->first();

        return response()->json([
            'status' => 'success',
            'message' => 'client subscriptions  successfully',
            'count' => $subscriptions->count(),
            'data' => ClientSubscriptionsResource::collection($subscriptions),
            'active_subscription' => $activeSubscription != null ? ClientSubscriptionsResource::make($activeSubscription) : "No subscriptions found for the given client_id",
            'user_details' =>  SubscriptionUserDetailsResource::make($user),
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

        $validatedData = $request->validated();
        $validatedData['team_leader_id'] = auth()->id();
        if (!$request->has('status')) {
            $validatedData['status'] = SubscriptionStatusEnum::NotStarted->value;
        }
        $subscription = Subscription::query()->create($validatedData);

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

    public function update(SubscriptionUpdateRequest $request, $id): JsonResponse
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

    public function refunded($id): JsonResponse
    {
        $subscription = Subscription::query()->find($id);
        if (!$subscription) {
            return response()->json(['message' => 'Subscription not found'], 404);
        }

        $subscription->update([
            'status' => SubscriptionStatusEnum::Refunded->value,
            'paid_amount' => 0
        ]);
        return response()->json(['message' => 'Subscription refunded successfully']);
    }


}
