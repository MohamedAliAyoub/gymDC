<?php

namespace App\Http\Controllers\Diet;

use App\Http\Controllers\Controller;
use App\Models\Diet\Item;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
class ItemController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/diet/item",
     *     summary="Retrieve all items",
     *     @OA\Response(response="200", description="Items retrieved successfully")
     * )
     */
    public function index(): JsonResponse
    {
        $items = Item::query()->paginate(15);

       return $this->paginateResponse($items, 'Items retrieved successfully');

    }


    /**
     * @OA\Post(
     *     path="/api/diet/item",
     *     summary="Create a new item",
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="Item's name",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *        @OA\Parameter(
     *         name="type",
     *         in="query",
     *         description="Item's tyoe",
     *         required=true,
     *         @OA\Schema(type="number")
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Item's status",
     *         required=false,
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Response(response="200", description="Item created successfully"),
     *     @OA\Response(response="400", description="Validation errors")
     * )
     */
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'type' => 'nullable|integer|between:0,2',
            'status' => 'nullable|boolean',
            'calories' => ['nullable', 'regex:/^\d+(\.\d{1,2})?$/']
        ]);

        $item = Item::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Item created successfully',
            'item' => $item,
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/diet/item/{item}",
     *     summary="Update an existing item",
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="Item's name",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Item's status",
     *         required=false,
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Response(response="200", description="Item updated successfully"),
     *     @OA\Response(response="400", description="Validation errors")
     * )
     */
    public function update(Request $request,$id)
    {
        try {
            $item = Item::query()->findOrFail($id);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Item not found',
            ], 404);
        }
        $request->validate([
            'name' => 'required|string',
            'type' => 'nullable|integer|between:0,2',
            'status' => 'nullable|boolean',
            'calories' => ['nullable', 'regex:/^\d+(\.\d{1,2})?$/']

        ]);

        $item->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Item updated successfully',
            'item' => $item,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/diet/item/{item}",
     *     summary="Retrieve an item",
     *     @OA\Response(response="200", description="Item retrieved successfully"),
     *     @OA\Response(response="404", description="Item not found")
     * )
     */
    public function show($id)
    {
        try {
            $item = Item::query()->findOrFail($id);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Item not found',
            ], 404);
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Item retrieved successfully',
            'item' => $item,
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/diet/item/{item}",
     *     summary="Delete an item",
     *     @OA\Response(response="200", description="Item deleted successfully"),
     *     @OA\Response(response="404", description="Item not found")
     * )
     */
    public function delete($id)
    {
        try {
            $item = Item::query()->findOrFail($id);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Item not found',
            ], 404);
        }
        $item->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Item deleted successfully',
        ]);
    }
}
