<?php

namespace App\Traits;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;

trait PaginateResponseTrait
{
    public function paginateResponse(LengthAwarePaginator $data, string $message = 'Data retrieved successfully'): JsonResponse
    {
        if ($data->currentPage() > $data->lastPage()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No more pages available',
            ], 400);
        }

        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data->items(),
            'count' => $data->count(),
            'pagination' => [
                'total' => $data->total(),
                'per_page' => $data->perPage(),
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'from' => $data->firstItem(),
                'to' => $data->lastItem(),
            ],
        ]);
    }
}
