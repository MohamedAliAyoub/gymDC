<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\Dashboard\SubscriptionLogsResource;
use App\Http\Resources\Dashboard\SubscriptionResource;
use App\Models\Dashboard\SubscriptionLogs;
use Illuminate\Http\JsonResponse;

class SubscriptionLogsController extends Controller
{
    public function getClientLogs($id): JsonResponse
    {
        $subscriptionLogs = SubscriptionLogs::query()
            ->where('client_id', $id)
            ->with('client', 'sale')
            ->get();
        return response()->json([
            'status' => 'success',
            'message' => 'Subscription logs retrieved successfully',
            'data' => $subscriptionLogs,
        ]);
    }
}
