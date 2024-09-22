<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\Dashboard\ClientResource;
use App\Http\Resources\Dashboard\MessagesResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;


class TeamLeaderController extends Controller
{


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
                $teamLeaderId = auth()->id();
                $coachIds = User::where('team_leader_id', $teamLeaderId)->pluck('id')->toArray();
                $query->whereIn('workout_coach_id', $coachIds);
            })
            ->when(request('firstPlanNeeded'), function ($query) {
                $query->firstPlanNeeded();
            })
            ->when(request('updateNeeded'), function ($query) {
                $query->updateNeeded();
            })
            ->when(request('allReadyHasPlan'), function ($query) {
                $query->allReadyHasPlan();
            })
            ->when(request('search'), function ($query) {
                $query->search(request('search'));
            })->paginate(10);

        $query = User::query()
            ->where('type', 8) // client
            ->whereHas('subscriptions', function ($query) {
                $teamLeaderId = auth()->id();
                $coachIds = User::where('team_leader_id', $teamLeaderId)->pluck('id')->toArray();
                $query->whereIn('workout_coach_id', $coachIds);
            });

        $clientCount = [
            'allUsersCount' => $query->count(),
            'firstPlanNeededCount' => $query->firstPlanNeeded()->count(),
            'updateNeededCount' => $query->updateNeeded()->count(),
            'allReadyHasPlanCount' => $query->allReadyHasPlan()->count(),
        ];
      return $this->paginateResponse($clients, 'Clients retrieved successfully' , $clientCount);
    }

    public function getUsersToMessages(): JsonResponse
    {
        $clients = User::query()
            ->select('id', 'name')
            ->whereHas('subscriptions', function ($query) {
                $teamLeaderId = auth()->id();
                $coachIds = User::where('team_leader_id', $teamLeaderId)->pluck('id')->toArray();
                $query->whereIn('workout_coach_id', $coachIds);
            })
            ->where('type', 8)
            ->get();

        return response()->json([
            'data' => MessagesResource::collection($clients),
            'message' => 'Clients retrieved successfully'
        ]);
    }
}
