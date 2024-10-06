<?php

namespace App\Http\Controllers;

use App\Enums\UserTypeEnum;
use App\Http\Resources\Dashboard\ClientResource;
use App\Http\Resources\Dashboard\MessagesResource;
use App\Models\Dashboard\Subscription;
use App\Models\Exercise\UserPlanExercise;
use App\Models\User;
use App\Models\UserDetails;
use Carbon\Carbon;
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
            'mobile' => ['nullable', 'regex:/^(\+?\d{1,4}|\d{1,4})?\s?\d{7,14}$/'],
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
            'mobile' => $request->mobile,
            'password' => bcrypt($request->password),
            'type' => $request->type,
            'image' => $path,
            'team_leader_id' => auth()->id(),
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
            ->get(['id', 'name', 'type']);
        return response()->json([
            'status' => 'success',
            'message' => 'Staff retrieved successfully',
            'staff' => $staff,
        ]);
    }

    // get statics of sales, doctors, and coaches
    public function getUsersStatistics(Request $request): JsonResponse
    {
        $request->validate([
            'type' => ['nullable', 'integer', Rule::in(UserTypeEnum::Doctor, UserTypeEnum::Coach, UserTypeEnum::Sales)],
        ]);
        $users = User::query()
            ->when(request('type'), function ($query) {
                $query->type(request('type'));
            })->when(request('search'), function ($query) {
                $query->search(request('search'));
            })
            ->whereIn('type', [
                UserTypeEnum::Coach,
                UserTypeEnum::Doctor,
                UserTypeEnum::Sales
            ])->paginate(10);
        $userCount = User::query()->whereIn('type', [
            UserTypeEnum::Coach,
            UserTypeEnum::Doctor,
            UserTypeEnum::Sales
        ])->count();
        $coachesCount = User::query()->where('type', UserTypeEnum::Coach)->count();
        $doctorsCount = User::query()->where('type', UserTypeEnum::Doctor)->count();
        $salesCount = User::query()->where('type', UserTypeEnum::Sales)->count();

        $usersData = $users->map(function ($user) {
            $plansCount = match ($user->type) {
                UserTypeEnum::Coach => $user->getWorkoutPlans(),
                UserTypeEnum::Doctor => $user->getNutritionPlansCount(),
                UserTypeEnum::Sales => $user->getSalePlans(),
                default => 0,
            };
            return array_merge($user->toArray(), ['plans_count' => $plansCount]);
        });
        return response()->json([
            'status' => 'success',
            'message' => 'Users retrieved successfully',
            'data' => $usersData,
            'all' => $userCount,
            'coaches' => $coachesCount,
            'doctors' => $doctorsCount,
            'sales' => $salesCount,
            'pagination' => [
                'total' => $users->total(),
                'per_page' => $users->perPage(),
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'from' => $users->firstItem(),
                'to' => $users->lastItem(),
            ]
        ]);
    }

    public function getAdminStatistics(): JsonResponse
    {
        $users = User::query()
            ->where('type', UserTypeEnum::Client)->count();
        $currentMonthUsers = User::query()
            ->where('type', UserTypeEnum::Client)
            ->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
            ->count();

        $previousMonthStart = Carbon::now()->subMonth()->startOfMonth();
        $previousMonthEnd = Carbon::now()->subMonth()->endOfMonth();

        $previousMonthUsers = User::query()
            ->where('type', UserTypeEnum::Client)
            ->whereBetween('created_at', [$previousMonthStart, $previousMonthEnd])
            ->count();

        // Calculate the percentage change
        if ($previousMonthUsers > 0) {
            $percentageChange = (($currentMonthUsers - $previousMonthUsers) / $previousMonthUsers) * 100;
        } else {
            // If previous month count is 0, handle the percentage change
            $percentageChange = $currentMonthUsers > 0 ? 100 : 0; // Assuming 100% increase if there are users in the current month
        }

        // Determine if the ratio is higher or lower
        $ratioComparison = $percentageChange > 0 ? 'higher' : 'lower';

        $subscriptions = Subscription::query()->count();
        $currentMonthSubscriptions = Subscription::query()
            ->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
            ->count();

        $previousMonthStart = Carbon::now()->subMonth()->startOfMonth();
        $previousMonthEnd = Carbon::now()->subMonth()->endOfMonth();

        $previousMonthSubscriptions = Subscription::query()
            ->whereBetween('created_at', [$previousMonthStart, $previousMonthEnd])
            ->count();

        // Calculate the percentage change
        if ($previousMonthSubscriptions > 0) {
            $percentageChange = (($currentMonthSubscriptions - $previousMonthSubscriptions) / $previousMonthSubscriptions) * 100;
        } else {
            // If previous month count is 0, handle the percentage change
            $percentageChange = $currentMonthSubscriptions > 0 ? 100 : 0; // Assuming 100% increase if there are subscriptions in the current month
        }

// Determine if the ratio is higher or lower
        $ratioComparison = $percentageChange > 0 ? 'higher' : 'lower';

        // Calculate the rates
        $currentMonthRate = $currentMonthUsers > 0 ? ($currentMonthSubscriptions / $currentMonthUsers) * 100 : 0;
        $previousMonthRate = $previousMonthUsers > 0 ? ($previousMonthSubscriptions / $previousMonthUsers) * 100 : 0;

        return response()->json([
            'status' => 'success',
            'message' => 'Statistics retrieved successfully',
            'clients' => [
                'all' => $users,
                'current_month' => $currentMonthUsers,
                'previous_month' => $previousMonthUsers,
                'percentage_change' => abs($percentageChange),
                'comparison' => $ratioComparison,
            ],
            'subscriptions' => [
                'all' => $subscriptions,
                'current_month' => $currentMonthSubscriptions,
                'previous_month' => $previousMonthSubscriptions,
                'percentage_change' => abs($percentageChange),
                'comparison' => $ratioComparison,
            ],
            'rate' => [
                'current_month' => $currentMonthRate,
                'previous_month' => $previousMonthRate,
            ],
            'total' => [
                'all' => $this->getUserCountByType([UserTypeEnum::Coach, UserTypeEnum::Doctor, UserTypeEnum::Sales]),
                'coaches' => $this->getUserCountByType([UserTypeEnum::Coach]),
                'doctors' => $this->getUserCountByType([UserTypeEnum::Doctor]),
                'sales' => $this->getUserCountByType([UserTypeEnum::Sales]),
            ]
        ]);

    }

    private function getUserCountByType($type)
    {
        return User::query()->whereIn('type', $type)->count();
    }

    public function getAllClients(): JsonResponse
    {
        $clients = User::query()
            ->where('type', 8) // client
            ->whereHas('subscriptions')
            ->when(request('firstPlanNeeded'), function ($query) {
                $query->firstPlanNeeded();
            })->when(request('updateNeeded'), function ($query) {
                $query->updateNeeded();
            })->when(request('allReadyHasPlan'), function ($query) {
                $query->allReadyHasPlan();
            })->when(request('search'), function ($query) {
                $query->search(request('search'));
            })->paginate(10);


        $query = User::query()
            ->where('type', 8) // client
            ->whereHas('subscriptions');
        $clientCount = [
            'allUsersCount' => $query->count(),
            'firstPlanNeededCount' => $query->firstPlanNeeded()->count(),
            'updateNeededCount' => $query->updateNeeded()->count(),
            'allReadyHasPlanCount' => $query->allReadyHasPlan()->count(),
        ];
        return response()->json([
            'status' => 'success',
            'message' => 'Clients retrieved successfully',
            'data' => ClientResource::collection($clients),
            'clientCount' => $clientCount,
            'pagination' => [
                'total' => $clients->total(),
                'per_page' => $clients->perPage(),
                'current_page' => $clients->currentPage(),
                'last_page' => $clients->lastPage(),
                'from' => $clients->firstItem(),
                'to' => $clients->lastItem(),
            ]
        ]);
    }

    public function getUsersToMessages(): JsonResponse
    {
        $clients = User::query()
            ->select('id', 'name')
            ->where('type', 8)
            ->get();

        return response()->json([
            'data' => MessagesResource::collection($clients),
            'message' => 'Clients retrieved successfully'
        ]);
    }

}
