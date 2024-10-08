<?php

// Auth Controllers
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\ChechIn\FirstCheckInFormController;
use App\Http\Controllers\Dashboard\SubscriptionController;
use App\Http\Controllers\Diet\AppController;
use App\Http\Controllers\Diet\ItemController;
use App\Http\Controllers\Diet\ItemDetailsController;
use App\Http\Controllers\Diet\MealController;
use App\Http\Controllers\Diet\MealItemController;
use App\Http\Controllers\Diet\NoteController;
use App\Http\Controllers\Diet\PlanController;
use App\Http\Controllers\Diet\PlanMealController;
use App\Http\Controllers\Diet\StandardController;
use App\Http\Controllers\Diet\StandardTypeController;
use App\Http\Controllers\Exercise\DoneExerciseController;
use App\Http\Controllers\Exercise\ExerciseController;
use App\Http\Controllers\Exercise\ExerciseDetailsController;
use App\Http\Controllers\Exercise\ExercisePlanExerciseController;
use App\Http\Controllers\Exercise\NoteExerciseCotroller;
use App\Http\Controllers\Exercise\PlanExerciseController;
use App\Http\Controllers\Exercise\UserPlanExerciseController;
use App\Http\Controllers\Exercise\WeeklyPlanExerciseController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Diet Controllers

// Exercise Controllers

// Dashboard Controllers
use App\Http\Controllers\Dashboard\CheckIn\CheckInController;
use App\Http\Controllers\Dashboard\CheckIn\CheckInWorkoutController;

use App\Http\Controllers\Dashboard\SalesController;
use App\Http\Controllers\Dashboard\DoctorController;
use App\Http\Controllers\Dashboard\UserSubscriptionController;
use App\Http\Controllers\Dashboard\SubscriptionLogsController;
use App\Http\Controllers\Dashboard\CoachController;
use App\Http\Controllers\Dashboard\TeamLeaderController;

// Other Controllers

// Other Imports

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
Route::post('logout', [AuthController::class, 'logout']);
Route::post('change-password', [AuthController::class, 'changePassword']);
Route::get('profile', [AuthController::class, 'profile'])->middleware('auth:api');

Route::get('auth/google', [GoogleController::class, 'redirectToGoogle']);
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

Route::get('auth/apple', [GoogleController::class, 'redirectToGoogle']);
Route::get('auth/apple/callback', [GoogleController::class, 'handleGoogleCallback']);


Route::middleware('auth:api')->group(function () {
    //diet apis
    Route::prefix('diet')->group(function () {
        Route::prefix('plan')->group(function () {
            Route::get('/', [PlanController::class, 'index']);
            Route::post('/', [PlanController::class, 'create']);
            Route::put('/{plan}', [PlanController::class, 'update']);
            Route::get('/{plan}', [PlanController::class, 'show']);
            Route::delete('/{plan}', [PlanController::class, 'delete']);
            Route::post('/assignPlanToUsers', [PlanController::class, 'assignPlanToUsers']);
            Route::get('/active/plan', [AppController::class, 'getActivePlan']);
            Route::post('/create-full-plan', [PlanController::class, 'createFullPlan']);
            Route::post('/create-edit-full-plan', [PlanController::class, 'createOrEditFullPlan']);
            Route::post('duplicate-plan/{id}', [PlanController::class, 'duplicatePlan']);
            Route::get('/get-client-plan/{user_id}', [PlanController::class, 'getClientPlans']);
            Route::delete('/delete-item-plan/{plan_id}/{meal_id}/{item_id}', [PlanController::class, 'deleteItemFromPlan']);
            Route::delete('/delete-meal-plan/{plan_id}/{meal_id}', [PlanController::class, 'deleteMealFromPlan']);
        });


        Route::prefix('meal')->group(function () {
            Route::get('/', [MealController::class, 'index']);
            Route::post('/', [MealController::class, 'create']);
            Route::put('/{meal}', [MealController::class, 'update']);
            Route::get('/{meal}', [MealController::class, 'show']);
            Route::delete('/{meal}', [MealController::class, 'delete']);
            Route::post('/assignMealToUser', [AppController::class, 'assignMealToUser']);
        });
        Route::prefix('mealitem')->group(function () {
            Route::get('/', [MealItemController::class, 'index']);
            Route::post('/', [MealItemController::class, 'create']);
            Route::put('/{mealItem}', [MealItemController::class, 'update']);
            Route::get('/{mealItem}', [MealItemController::class, 'show']);
            Route::delete('/{mealItem}', [MealItemController::class, 'delete']);
            Route::post('/storeMealItems', [MealItemController::class, 'storeMealItems']);
        });
        Route::prefix('item')->group(function () {
            Route::get('/', [ItemController::class, 'index']);
            Route::post('/', [ItemController::class, 'create']);
            Route::put('/{item}', [ItemController::class, 'update']);
            Route::get('/{item}', [ItemController::class, 'show']);
            Route::delete('/{item}', [ItemController::class, 'delete']);
        });
        Route::prefix('planMeal')->group(function () {
            Route::get('/', [PlanMealController::class, 'index']);
            Route::post('/', [PlanMealController::class, 'create']);
            Route::put('/{planMeal}', [PlanMealController::class, 'update']);
            Route::get('/{planMeal}', [PlanMealController::class, 'show']);
            Route::delete('/{planMeal}', [PlanMealController::class, 'delete']);
            Route::post('/storePlanMeals', [PlanMealController::class, 'storePlanMeals']);
        });
        Route::prefix('itemDetails')->group(function () {
            Route::get('/', [ItemDetailsController::class, 'index']);
            Route::post('/', [ItemDetailsController::class, 'create']);
            Route::put('/{itemDetails}', [ItemDetailsController::class, 'update']);
            Route::get('/{itemDetails}', [ItemDetailsController::class, 'show']);
            Route::delete('/{itemDetails}', [ItemDetailsController::class, 'delete']);
            Route::post('/createItemWithDetails', [ItemDetailsController::class, 'createItemWithDetails']);
        });
        Route::prefix('standard')->group(function () {
            Route::get('/', [StandardController::class, 'index']);
            Route::post('/', [StandardController::class, 'create']);
            Route::put('/{standard}', [StandardController::class, 'update']);
            Route::get('/{standard}', [StandardController::class, 'show']);
            Route::delete('/{standard}', [StandardController::class, 'delete']);
        });

        Route::prefix('standardType')->group(function () {
            Route::get('/', [StandardTypeController::class, 'index']);
            Route::post('/', [StandardTypeController::class, 'create']);
            Route::put('/{standardType}', [StandardTypeController::class, 'update']);
            Route::get('/{standardType}', [StandardTypeController::class, 'show']);
            Route::delete('/{standardType}', [StandardTypeController::class, 'delete']);
        });
        Route::prefix('note')->group(function () {
            Route::get('/', [NoteController::class, 'index']);
            Route::post('/', [NoteController::class, 'create']);
            Route::put('/{note}', [NoteController::class, 'update']);
            Route::get('/{note}', [NoteController::class, 'show']);
            Route::delete('/{note}', [NoteController::class, 'delete']);
        });


    });
    //end diet apis

    //exercise apis
    Route::prefix('exercise')->group(function () {
        Route::group(['prefix' => 'exercise'], function () {
            Route::get('/', [ExerciseController::class, 'index']);
            Route::post('/', [ExerciseController::class, 'create']);
            Route::put('/{exercise}', [ExerciseController::class, 'update']);
            Route::get('/{exercise}', [ExerciseController::class, 'show']);
            Route::delete('/{exercise}', [ExerciseController::class, 'delete']);
            Route::post('/add/assignExercisesToPlan', [ExercisePlanExerciseController::class, 'assignExercisesToPlan']);
            Route::post('/store-exercise-plan', [PlanExerciseController::class, 'storeExercisePlan']);
            Route::post('/assignPlanToUsers', [ExerciseController::class, 'assignPlanToUsers']);

        });
        Route::prefix('weekly-plan')->group(function () {
            Route::get('/', [WeeklyPlanExerciseController::class, 'index']);
            Route::get('/get-client-plans/{id}', [WeeklyPlanExerciseController::class, 'getClientPlan']);
            Route::get('/{id}', [WeeklyPlanExerciseController::class, 'show']);
            Route::delete('/{id}', [WeeklyPlanExerciseController::class, 'deleteWeeklyPlan']);
            Route::delete('/plan/{id}', [WeeklyPlanExerciseController::class, 'deletePlanExercises']);
            Route::delete('/exercise/{id}', [WeeklyPlanExerciseController::class, 'deleteExercise']);

        });
        Route::group(['prefix' => 'done'], function () {

            Route::post('/createWithDetails', [DoneExerciseController::class, 'createWithDetails']);
            Route::post('/createWithPlan', [DoneExerciseController::class, 'createWithPlan']);

            Route::get('/', [DoneExerciseController::class, 'index']);
            Route::get('/{id}', [DoneExerciseController::class, 'show']);
            Route::post('/', [DoneExerciseController::class, 'create']);
            Route::put('/{id}', [DoneExerciseController::class, 'update']);
            Route::delete('/{id}', [DoneExerciseController::class, 'delete']);
        });
        Route::group(['prefix' => 'note'], function () {
            Route::get('/', [NoteExerciseCotroller::class, 'index']);
            Route::get('/{id}', [NoteExerciseCotroller::class, 'show']);
            Route::post('/', [NoteExerciseCotroller::class, 'create']);
            Route::put('/{id}', [NoteExerciseCotroller::class, 'update']);
            Route::delete('/{id}', [NoteExerciseCotroller::class, 'delete']);
        });
        Route::group(['prefix' => 'user-plan-exercise'], function () {
            Route::get('/', [UserPlanExerciseController::class, 'index']);
            Route::get('/{id}', [UserPlanExerciseController::class, 'show']);
            Route::post('/', [UserPlanExerciseController::class, 'create']);
            Route::put('/{id}', [UserPlanExerciseController::class, 'update']);
            Route::delete('/{id}', [UserPlanExerciseController::class, 'delete']);
            Route::get('/get/todayPlan', [UserPlanExerciseController::class, 'getTodayPlan']);
            Route::get('/get/planByDate', [UserPlanExerciseController::class, 'getPlanByDate']);
        });
        Route::group(['prefix' => 'plan'], function () {
            Route::get('/', [PlanExerciseController::class, 'index']);
            Route::get('/{id}', [PlanExerciseController::class, 'show']);
            Route::post('/', [PlanExerciseController::class, 'create']);
            Route::put('/{id}', [PlanExerciseController::class, 'update']);
            Route::delete('/{id}', [PlanExerciseController::class, 'delete']);
            Route::get('/get/todayPlan', [PlanExerciseController::class, 'getTodayPlan']);
            Route::get('/get/planByDate', [PlanExerciseController::class, 'getPlanByDate']);
        });
        Route::group(['prefix' => 'exercise-details'], function () {
            Route::get('/', [ExerciseDetailsController::class, 'index']);
            Route::get('/{id}', [ExerciseDetailsController::class, 'show']);
            Route::post('/', [ExerciseDetailsController::class, 'create']);
            Route::put('/previous/{id}', [ExerciseDetailsController::class, 'updatePrevious']);
            Route::put('/{id}', [ExerciseDetailsController::class, 'update']);
            Route::delete('/{id}', [ExerciseDetailsController::class, 'delete']);
        });

        //end exercise apis

    });
    Route::prefix('dashboard')->group(function () {
        Route::group(['prefix' => 'subscriptions'], function () {
            Route::get('/', [SubscriptionController::class, 'index']);
            Route::get('/{id}', [SubscriptionController::class, 'show']);
            Route::post('/', [SubscriptionController::class, 'store']);
            Route::put('/{id}', [SubscriptionController::class, 'update']);
            Route::delete('/{id}', [SubscriptionController::class, 'destroy']);
            Route::get('/client/{id}', [SubscriptionController::class, 'get_client_subscriptions']);
            Route::post('refunded/{id}', [SubscriptionController::class, 'refunded']);
            Route::get('/get-client/logs/{id}', [SubscriptionLogsController::class , 'getClientLogs']);
        });
        Route::prefix('user-details')->group(function () {
            Route::post('/assignUserDetailsOfClient', [UserController::class, 'assignUserDetailsOfClient']);
        });
        Route::post('/first-check-in-form', [FirstCheckInFormController::class, 'store']);
        Route::post('/check-in-workout', [\App\Http\Controllers\ChechIn\CheckInWorkoutController::class, 'store']);
        Route::post('/check-in-diet', [\App\Http\Controllers\ChechIn\CheckInController::class, 'store']);
        Route::get('/get-all-client-check-in-forms', [FirstCheckInFormController::class, 'getAllClientCheckInForms']);
        Route::group(['prefix' => 'sales'], function () {
            Route::get('/', [SalesController::class, 'index']);
            Route::get('/get-users-to-messages', [SalesController::class, 'getUsersToMessages']);

        });
        Route::group(['prefix' => 'doctors'], function () {
            Route::get('/', [DoctorController::class, 'index']);
            Route::get('/get-users-to-messages', [DoctorController::class, 'getUsersToMessages']);

        });
        Route::group(['prefix' => 'coaches'], function () {
            Route::get('/', [CoachController::class, 'index']);
            Route::get('/get-users-to-messages', [CoachController::class, 'getUsersToMessages']);


        });
        Route::group(['prefix' => 'team-leaders'], function () {
            Route::get('/', [TeamLeaderController::class, 'index']);
            Route::get('/get-clients-as-coach', [TeamLeaderController::class, 'getClientsCoach']);
            Route::get('/get-users-to-messages', [TeamLeaderController::class, 'getUsersToMessages']);

        });

        Route::group(['prefix' => 'admin'], function () {
            Route::get('/get-all-users', [UserController::class, 'getStaff']);
            Route::post('/add-staff', [UserController::class, 'storeStaffFromAdmin']);
            Route::get('get-staff-types' , [UserController::class , 'getTypes']);
            Route::get('get-users-statstics' , [UserController::class , 'getUsersStatistics']);
            Route::get('get-admin-statstics' , [UserController::class , 'getAdminStatistics']);
            Route::get('get-all-clients' , [UserController::class , 'getAllClients']);
            Route::get('/get-users-to-messages', [UserController::class, 'getUsersToMessages']);

        });
    });
});
