<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserDetails;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use function App\Http\Helpers\uploadImage;

class UserController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/user/details",
     *     summary="Assign user details to a client",
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         description="User's ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         description="User details to assign to the client",
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="height", type="number", format="float", example=1.75),
     *             @OA\Property(property="weight", type="number", format="float", example=70),
     *             @OA\Property(property="age", type="integer", example=30),
     *             @OA\Property(property="activity_level", type="number", format="float", example=1.2),
     *             @OA\Property(property="in_body_image", type="string", format="binary")
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="User details assigned successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="User details assigned successfully"),
     *             @OA\Property(property="code", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(response="400", description="Bad Request")
     * )
     */
    public function assignUserDetailsOfClient(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'height' => 'required|numeric',
            'weight' => 'required|numeric',
            'age' => 'required|numeric',
            'activity_level' => 'required|numeric',
            'in_body_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        // Upload the profile picture and get the path
        //TODO activity_level add it in the database
        if ($request->hasFile('in_body_image'))
            $path = uploadImage($request->file('in_body_image'), 'public', 'clients/in_body');
        else
            $path = null;

        UserDetails::query()->create([
            'user_id' => $request->user_id,
            'height' => $request->height,
            'weight' => $request->weight,
            'age' => $request->age,
            'activity_level' => $request->activity_level,
            'in_body_image' => $path,
        ]);
        return response()->json(
            [
                'message' => 'User details assigned successfully',
                'status' => 'success',
                'code' => 200
            ]);

    }
}
