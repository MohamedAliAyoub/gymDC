<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\Dashboard\ClientResource;
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
        $query = User::query()
            ->where('type', 8) // client
           ->where('team_leader_id', auth()->id())
            ->when(request('firstPlanNeeded'), function ($query) {
                $query->firstPlanNeeded();
            })->when(request('updateNeeded'), function ($query) {
                $query->updateNeeded();
            })->when(request('allReadyHasPlan'), function ($query) {
                $query->allReadyHasPlan();
            })->when(request('search'), function ($query) {
                $query->search(request('search'));
            });
            $clients =$query->paginate(10);
        return response()->json([
            'status' => 'success',
            'message' => 'client get successfully',
            'clients' => ClientResource::collection($clients),
            'count' => $clients->count(),
            'pagination' => [
                'total' => $clients->total(),
                'per_page' => $clients->perPage(),
                'current_page' => $clients->currentPage(),
                'last_page' => $clients->lastPage(),
                'from' => $clients->firstItem(),
                'to' => $clients->lastItem(),
            ]
        ]);
    }
}
