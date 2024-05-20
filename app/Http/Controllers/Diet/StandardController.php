<?php

namespace App\Http\Controllers\Diet;

use App\Http\Controllers\Controller;
use App\Models\Diet\Standard;
use Illuminate\Http\Request;

class StandardController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/diet/standard",
     *     summary="Retrieve all standards",
     *     @OA\Response(response="200", description="Standards retrieved successfully")
     * )
     */
    public function index()
    {
        $standards = Standard::query()->paginate(15);

        return response()->json([
            'status' => 'success',
            'message' => 'Standards retrieved successfully',
            'standards' => $standards,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/diet/standard",
     *     summary="Create a new standard",
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="Standard's name",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="carbohydrate",
     *         in="query",
     *         description="Standard's carbohydrate",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="protein",
     *         in="query",
     *         description="Standard's protein",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="fat",
     *         in="query",
     *         description="Standard's fat",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="item_id",
     *         in="query",
     *         description="Item id",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="item_details_id",
     *         in="query",
     *         description="Item details id",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Standard's status",
     *         required=true,
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Response(response="200", description="Standard created successfully"),
     *     @OA\Response(response="400", description="Validation errors")
     * )
     */
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string',
            'carbohydrate' => 'required|string',
            'protein' => 'required|string',
            'fat' => 'required|string',
            'item_id' => 'nullable|integer|exists:items,id',
            'item_details_id' => 'nullable|integer|exists:item_details,id',
            'status' => 'required|boolean',
        ]);

        $standard = Standard::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Standard created successfully',
            'standard' => $standard,
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/diet/standard/{standard}",
     *     summary="Update an existing standard",
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="Standard's name",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="carbohydrate",
     *         in="query",
     *         description="Standard's carbohydrate",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="protein",
     *         in="query",
     *         description="Standard's protein",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="fat",
     *         in="query",
     *         description="Standard's fat",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="item_id",
     *         in="query",
     *         description="Item id",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="item_details_id",
     *         in="query",
     *         description="Item details id",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Standard's status",
     *         required=true,
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Response(response="200", description="Standard updated successfully"),
     *     @OA\Response(response="400", description="Validation errors")
     * )
     */
    public function update(Request $request, Standard $standard)
    {
        $request->validate([
            'name' => 'required|string',
            'carbohydrate' => 'required|string',
            'protein' => 'required|string',
            'fat' => 'required|string',
            'item_id' => 'required|integer',
            'item_details_id' => 'required|integer',
            'status' => 'required|boolean',
        ]);

        $standard->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Standard updated successfully',
            'standard' => $standard,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/diet/standard/{standard}",
     *     summary="Retrieve a standard",
     *     @OA\Response(response="200", description="Standard retrieved successfully"),
     *     @OA\Response(response="404", description="Standard not found")
     * )
     */
    public function show(Standard $standard)
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Standard retrieved successfully',
            'standard' => $standard,
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/diet/standard/{standard}",
     *     summary="Delete a standard",
     *     @OA\Response(response="200", description="Standard deleted successfully"),
     *     @OA\Response(response="404", description="Standard not found")
     * )
     */
    public function delete(Standard $standard)
    {
        $standard->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Standard deleted successfully',
        ]);
    }
}
