<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\Dashboard\ClientResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class DoctorController extends Controller
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

        //TODO ->where('role', 'client')
        $clients = User::query()
            ->where('type', 8) // client
            ->whereHas('subscriptions', function ($query) {
                $query->where('nutrition_coach_id', auth()->id());
            })
            ->when(request('scopeHasFirstPlanNeeded'), function ($query) {
                $query->scopeHasFirstPlanNeeded();
            })->when(request('scopeUpdateNeeded'), function ($query) {
                $query->scopeUpdateNeeded();
            })->when(request('scopeAllReadyHasPlan'), function ($query) {
                $query->scopeAllReadyHasPlan();
            })->paginate(10);
        return response()->json([
            'status' => 'success',
            'message' => 'client get successfully',
            'clients' => ClientResource::collection($clients),
        ]);
    }
}
