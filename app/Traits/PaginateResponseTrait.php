<?php

namespace App\Traits;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Http\JsonResponse;

trait PaginateResponseTrait
{
    public function paginateResponse($data, string $message = 'Data retrieved successfully', array $clientCount = []): JsonResponse
    {
        if ($data instanceof LengthAwarePaginator) {
            if ($data->currentPage() > $data->lastPage()) {
                return response()->json([
                    'data' => [],
                    'status' => 'error',
                    'message' => 'No more pages available',
                ], 200);
            }

            $pagination = [
                'total' => $data->total(),
                'per_page' => $data->perPage(),
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'from' => $data->firstItem(),
                'to' => $data->lastItem(),
            ];
            $count = $data->count();
        } elseif ($data instanceof Collection) {
            $pagination = null;
            $count = $data->count();
        } else {
            throw new \InvalidArgumentException('The data must be an instance of LengthAwarePaginator or Collection.');
        }

        $response = [
            'status' => 'success',
            'message' => $message,
            'data' => $data->all(),
            'count' => $data->total(),
            'pagination' => $pagination,
        ];

        if (!empty($clientCount)) {
            $response['clientCount'] = $clientCount;
        }

        return response()->json($response);
    }
}
