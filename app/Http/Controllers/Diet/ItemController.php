<?php

namespace App\Http\Controllers\Diet;

use App\Http\Controllers\Controller;
use App\Models\Diet\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
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
        $items = Item::query()->paginate(15);

        return response()->json([
            'status' => 'success',
            'message' => 'Items retrieved successfully',
            'items' => $items,
        ]);
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
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Item's status",
     *         required=true,
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
            'status' => 'required|boolean',
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
     *         required=true,
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Response(response="200", description="Item updated successfully"),
     *     @OA\Response(response="400", description="Validation errors")
     * )
     */
    public function update(Request $request, Item $item)
    {
        $request->validate([
            'name' => 'required|string',
            'status' => 'required|boolean',
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
    public function show(Item $item)
    {
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
    public function delete(Item $item)
    {
        $item->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Item deleted successfully',
        ]);
    }
}
