<?php

namespace App\Http\Controllers\Diet;

use App\Http\Controllers\Controller;
use App\Models\Diet\StandardType;
use Illuminate\Http\Request;

class StandardTypeController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/diet/standardtype",
     *     summary="Retrieve all standard types",
     *     @OA\Response(response="200", description="Standard types retrieved successfully")
     * )
     */
    public function index()
    {
        $standardTypes = StandardType::query()->paginate(15);

        return response()->json([
            'status' => 'success',
            'message' => 'Standard types retrieved successfully',
            'standardTypes' => $standardTypes,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/diet/standardtype",
     *     summary="Create a new standard type",
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="Standard type's name",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="standard_id",
     *         in="query",
     *         description="Standard id",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Standard type's status",
     *         required=true,
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Response(response="200", description="Standard type created successfully"),
     *     @OA\Response(response="400", description="Validation errors")
     * )
     */
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'standard_id' => 'required|integer',
            'status' => 'required|boolean',
        ]);

        $standardType = StandardType::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Standard type created successfully',
            'standardType' => $standardType,
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/diet/standardtype/{standardType}",
     *     summary="Update an existing standard type",
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="Standard type's name",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="standard_id",
     *         in="query",
     *         description="Standard id",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Standard type's status",
     *         required=true,
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Response(response="200", description="Standard type updated successfully"),
     *     @OA\Response(response="400", description="Validation errors")
     * )
     */
    public function update(Request $request, StandardType $standardType)
    {
        $request->validate([
            'name' => 'required|string',
            'standard_id' => 'required|integer',
            'status' => 'required|boolean',
        ]);

        $standardType->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Standard type updated successfully',
            'standardType' => $standardType,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/diet/standardtype/{standardType}",
     *     summary="Retrieve a standard type",
     *     @OA\Response(response="200", description="Standard type retrieved successfully"),
     *     @OA\Response(response="404", description="Standard type not found")
     * )
     */
    public function show(StandardType $standardType)
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Standard type retrieved successfully',
            'standardType' => $standardType,
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/diet/standardtype/{standardType}",
     *     summary="Delete a standard type",
     *     @OA\Response(response="200", description="Standard type deleted successfully"),
     *     @OA\Response(response="404", description="Standard type not found")
     * )
     */
    public function delete(StandardType $standardType)
    {
        $standardType->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Standard type deleted successfully',
        ]);
    }
}
