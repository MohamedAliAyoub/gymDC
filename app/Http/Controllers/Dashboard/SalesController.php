<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class SalesController extends Controller
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
        $clients = User::query()->paginate(10);
        return response()->json([
            'status' => 'success',
            'message' => 'client get successfully',
            'clients' => $clients,
        ]);
    }
}
