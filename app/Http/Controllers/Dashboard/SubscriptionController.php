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

    public function get_client_subscriptions(Request $request): JsonResponse
    {
        $subscriptions = Subscription::query()->where([
            ['client_id', $request->id]
        ])->paginate(15);

        return response()->json([
            'status' => 'success',
            'message' => 'client subscriptions  successfully',
            'count' => $subscriptions->count(),
            'data' => ClientSubscriptionsResource::collection($subscriptions),
        ]);
    }

    public function store(SubscriptionRequest $request): JsonResponse
    {
        $subscription = Subscription::create($request->validated());
        return response()->json(SubscriptionResource::make(
            $subscription->load('nutritionCoach', 'workoutCoach', 'client', 'sale')),
            201);
    }

    public function show($id): JsonResponse
    {
        $subscription = Subscription::findOrFail($id);
        if (!$subscription) {
            return response()->json(['message' => 'Subscription not found'], 404);
        }
        return response()->json(SubscriptionResource::make(
            $subscription->load('nutritionCoach', 'workoutCoach', 'client', 'sale')));
    }

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
