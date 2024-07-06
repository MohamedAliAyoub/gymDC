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
     *     summary="Retrieve paginated list of clients",
     *     tags={"Dashboard"},
     *     @OA\Response(
     *         response=200,
     *         description="Clients retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="client get successfully"),
     *             @OA\Property(property="clients", type="object",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/User")),
     *                 @OA\Property(property="first_page_url", type="string", example="http://example.com/api/dashboard/sales?page=1"),
     *                 @OA\Property(property="from", type="integer", example=1),
     *                 @OA\Property(property="last_page", type="integer", example=10),
     *                 @OA\Property(property="last_page_url", type="string", example="http://example.com/api/dashboard/sales?page=10"),
     *                 @OA\Property(property="next_page_url", type="string", example="http://example.com/api/dashboard/sales?page=2"),
     *                 @OA\Property(property="path", type="string", example="http://example.com/api/dashboard/sales"),
     *                 @OA\Property(property="per_page", type="integer", example=10),
     *                 @OA\Property(property="prev_page_url", type="string", example=null),
     *                 @OA\Property(property="to", type="integer", example=10),
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
        $clients = User::query()->paginate(10);
        return response()->json([
            'status' => 'success',
            'message' => 'client get successfully',
            'clients' => $clients,
        ]);
    }
}
