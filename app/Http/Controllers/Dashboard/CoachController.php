<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\Dashboard\ClientResource;
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
            })
            ->when(request('firstPlanNeeded'), function ($query) {
                $query->firstPlanNeeded();
            })->when(request('updateNeeded'), function ($query) {
                $query->updateNeeded();
            })->when(request('allReadyHasPlan'), function ($query) {
                $query->allReadyHasPlan();
            })->when(request('search'), function ($query) {
                $query->search(request('search'));
            })->paginate(10);

        $query = User::query()
            ->where('type', 8) // client
            ->whereHas('subscriptions', function ($query) {
                $query->where('workout_coach_id', auth()->id());
            });
            $clientCount = [
                'allUsersCount' => $query->count(),
                'firstPlanNeededCount' => $query->firstPlanNeeded()->count(),
                'updateNeededCount' => $query->updateNeeded()->count(),
                'allReadyHasPlanCount' => $query->allReadyHasPlan()->count(),
            ];
        return $this->paginateResponse($clients, 'Clients retrieved successfully' , $clientCount);
    }
}
