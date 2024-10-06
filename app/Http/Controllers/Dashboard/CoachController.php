<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\Dashboard\ClientResource;
use App\Http\Resources\Dashboard\MessagesResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use App\Traits\PaginateResponseTrait;
class CoachController extends Controller
{

    use PaginateResponseTrait;
    /**
     * @OA\Get(
     *     path="/api/dashboard/sales",
     *     summary="Retrieve all clients",
     *     @OA\Response(response="200", description="Clients retrieved successfully")
     * )
     */
    public function index(): JsonResponse
    {
        $clients = User::query()
            ->where('type', 8) // client
            ->whereHas('subscriptions', function ($query) {
                $query->where('workout_coach_id', auth()->id());
            })->when(request('firstPlanNeeded'), function ($query) {
                $query->firstPlanNeeded();
            })->when(request('updateNeeded'), function ($query) {
                $query->clone()->updateNeeded();
            })->when(request('allReadyHasPlan'), function ($query) {
                $query->clone()->allReadyHasPlan();
            })->when(request('search'), function ($query) {
                $query->search(request('search'));
            })->paginate(10);

        $query = User::query()
            ->where('type', 8) // client
            ->whereHas('subscriptions', function ($query) {
                $query->where('workout_coach_id', auth()->id());
            });


        $firstPlanNeeded = User::query()
            ->where('type', 8) // client
            ->whereHas('subscriptions', function ($query) {
                $query->where('workout_coach_id', auth()->id());
            })->firstPlanNeeded()->get();
        $clientCount = [
            'allUsersCount' => $query->clone()->count(),
            'firstPlanNeededCount' => $query->clone()->firstPlanNeeded()->count(),
            'updateNeededCount' => $query->clone()->updateNeeded()->count(),
            'allReadyHasPlanCount' => $query->clone()->allReadyHasPlan()->count(),
        ];
        return response()->json([
            'status' => 'success',
            'message' => 'Clients retrieved successfully',
            'data' => ClientResource::collection($clients),
            'clientCount' => $clientCount,
            'firstPlanNeeded' => $firstPlanNeeded,
            'pagination' => [
                'total' => $clients->total(),
                'per_page' => $clients->perPage(),
                'current_page' => $clients->currentPage(),
                'last_page' => $clients->lastPage(),
                'from' => $clients->firstItem(),
                'to' => $clients->lastItem(),
            ],
        ]);
    }


    public function getUsersToMessages(): JsonResponse
    {
        $clients = User::query()
            ->select('id', 'name')
            ->where('type', 8) // client
            ->whereHas('subscriptions', function ($query) {
                $query->where('workout_coach_id', auth()->id());
            })->get();

        return response()->json([
            'data' => MessagesResource::collection($clients),
            'message' => 'Clients retrieved successfully'
        ]);
    }
}
