<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\Dashboard\SubscriptionLogsResource;
use App\Http\Resources\Dashboard\SubscriptionResource;
use App\Models\Dashboard\SubscriptionLogs;
use Illuminate\Http\JsonResponse;

class SubscriptionLogsController extends Controller
{
    public function getSalesLogs(): JsonResponse
    {
        $subscriptionLogs = SubscriptionLogs::query()
            ->where('sale_id', auth()->id())
            ->with('client', 'sale')
            ->paginate(10);

        return response()->json([
            'status' => 'success',
            'message' => 'Clients logs retrieved successfully',
            'logs' => SubscriptionLogsResource::collection($subscriptionLogs),
        ]);
    }
}
