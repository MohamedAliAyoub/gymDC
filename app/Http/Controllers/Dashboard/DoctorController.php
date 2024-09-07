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
        $query = User::query()
            ->where('type', 8) // client
            ->whereHas('subscriptions', function ($query) {
                $query->where('nutrition_coach_id', auth()->id());
            })
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
            $clientCount = [
                'allUsersCount' => $query->count(),
                'firstPlanNeededCount' => $query->firstPlanNeeded()->count(),
                'updateNeededCount' => $query->updateNeeded()->count(),
                'allReadyHasPlanCount' => $query->allReadyHasPlan()->count(),
            ];
        return $this->paginateResponse($clients, 'Clients retrieved successfully' , $clientCount);
    }
}
