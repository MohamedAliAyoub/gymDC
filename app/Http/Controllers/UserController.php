<?php

namespace App\Http\Controllers;

use App\Enums\UserTypeEnum;
use App\Models\Exercise\UserPlanExercise;
use App\Models\User;
use App\Models\UserDetails;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
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

    public function storeStaffFromAdmin(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string',
            'type' => ['required', 'integer', Rule::in(UserTypeEnum::getValues())],
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        // Upload the profile picture and get the path
        if ($request->hasFile('image'))
            $path = uploadImage($request->file('image'), 'public', 'users');
        else
            $path = null;


        $user = User::query()->create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'type' => $request->type,
            'image' => $path,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Staff created successfully',
            'user' => $user,
        ]);
    }

    public function getTypes(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Types retrieved successfully',
            'types' => UserTypeEnum::getKeyValuePairs(),
        ]);
    }

    public function getStaff(): JsonResponse
    {
        $staff = User::query()
            ->when(request('filter'), function ($query) {
                $query->filter(request('filter'));
            })
            ->paginate(10);
        return response()->json([
            'status' => 'success',
            'message' => 'Staff retrieved successfully',
            'staff' => $staff,
        ]);
    }


    public function getUsersStatistics(Request $request): JsonResponse
    {
        $request->validate([
            'type' => ['nullable', 'integer', Rule::in(UserTypeEnum::Doctor , UserTypeEnum::Coach , UserTypeEnum::Sales )],
        ]);
        $users = User::query()
            ->when(request('type'), function ($query ) {
                $query->type(request('type'));
            })->when(request('search'), function ($query) {
                $query->search(request('search'));
            })
            ->whereIn('type', [UserTypeEnum::Coach, UserTypeEnum::Doctor, UserTypeEnum::Sales])->paginate(10);
        if ($users->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No users found',
            ], 404);
        }

        $usersData = $users->map(function ($user) {
            $plansCount = 0;
            switch ($user->type) {
                case UserTypeEnum::Coach:
                    $plansCount = $user->getWorkoutPlans();
                    break;
                case UserTypeEnum::Doctor:
                    $plansCount = $user->getNutritionPlansCount();
                    break;
                case UserTypeEnum::Sales:
                    $plansCount = $user->getSalePlans();
                    break;
            }

            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'created_at' => $user->created_at,
                'plansCount' => $plansCount,
            ];
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Statistics retrieved successfully',
            'users' => $usersData,
            'pagination' => [
                'total' => $users->total(),
                'count' => $users->count(),
                'per_page' => $users->perPage(),
                'current_page' => $users->currentPage(),
                'total_pages' => $users->lastPage(),
            ]
        ]);
    }

}
