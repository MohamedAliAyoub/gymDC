<?php

namespace App\Http\Controllers\Diet;

use App\Http\Controllers\Controller;
use App\Http\Resources\Diet\ItemDetailsResource;
use App\Models\Diet\ItemDetails;
use Illuminate\Http\Request;

class ItemDetailsController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/diet/item",
     *     summary="Retrieve all items",
     *     @OA\Response(response="200", description="Items retrieved successfully")
     * )
     */
    public function index()
    {
        $items = ItemDetails::query()->with('item')->paginate(15);

        return response()->json([
            'status' => 'success',
            'message' => 'Items retrieved successfully',
            'items' => ItemDetailsResource::collection($items),
        ]);
    }
    /**
     * @OA\Post(
     *     path="/api/diet/itemdetails",
     *     summary="Create a new item detail",
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="Item detail's name",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="item_id",
     *         in="query",
     *         description="Item id",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Item detail's status",
     *         required=true,
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Response(response="200", description="Item detail created successfully"),
     *     @OA\Response(response="400", description="Validation errors")
     * )
     */
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'item_id' => 'required|integer|exists:items,id',
            'status' => 'nullable|boolean',
        ]);

        $itemDetails = ItemDetails::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Item detail created successfully',
            'itemDetails' => $itemDetails,
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/diet/itemdetails/{itemDetails}",
     *     summary="Update an existing item detail",
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="Item detail's name",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="item_id",
     *         in="query",
     *         description="Item id",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Item detail's status",
     *         required=true,
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Response(response="200", description="Item detail updated successfully"),
     *     @OA\Response(response="400", description="Validation errors")
     * )
     */
    public function update(Request $request, ItemDetails $itemDetails)
    {
        $request->validate([
            'name' => 'required|string',
            'item_id' => 'required|integer|exists:items,id',
            'status' => 'nullable|boolean',
        ]);

        $itemDetails->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Item detail updated successfully',
            'itemDetails' => $itemDetails,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/diet/itemdetails/{itemDetails}",
     *     summary="Retrieve an item detail",
     *     @OA\Response(response="200", description="Item detail retrieved successfully"),
     *     @OA\Response(response="404", description="Item detail not found")
     * )
     */
    public function show(ItemDetails $itemDetails)
    {
        $itemDetails->load('item');
        return response()->json([
            'status' => 'success',
            'message' => 'Item detail retrieved successfully',
            'itemDetails' => ItemDetailsResource::make($itemDetails),
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/diet/itemdetails/{itemDetails}",
     *     summary="Delete an item detail",
     *     @OA\Response(response="200", description="Item detail deleted successfully"),
     *     @OA\Response(response="404", description="Item detail not found")
     * )
     */
    public function delete(ItemDetails $itemDetails)
    {
        $itemDetails->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Item detail deleted successfully',
        ]);
    }
}
