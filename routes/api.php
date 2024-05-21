<?php

use App\Http\Resources\Auth\AuthResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Diet\PlanController;
use App\Http\Controllers\Diet\MealController;
use App\Http\Controllers\Diet\MealItemController;
use App\Http\Controllers\Diet\ItemController;
use App\Http\Controllers\Diet\PlanMealController;
use App\Http\Controllers\Diet\ItemDetailsController;
use App\Http\Controllers\Diet\StandardController;
use App\Http\Controllers\Diet\StandardTypeController;
use App\Http\Controllers\Diet\NoteController;


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


Route::get('auth/google', [GoogleController::class, 'redirectToGoogle']);
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);



Route::middleware('auth:api')->group(function () {
    //diet apis
    Route::prefix('diet')->group(function () {
        Route::prefix('plan')->group(function () {
            Route::get('/', [PlanController::class, 'index']);
            Route::post('/', [PlanController::class, 'create']);
            Route::put('/{plan}', [PlanController::class, 'update']);
            Route::get('/{plan}', [PlanController::class, 'show']);
            Route::delete('/{plan}', [PlanController::class, 'delete']);
        });

        Route::prefix('meal')->group(function () {
            Route::get('/', [MealController::class, 'index']);
            Route::post('/', [MealController::class, 'create']);
            Route::put('/{meal}', [MealController::class, 'update']);
            Route::get('/{meal}', [MealController::class, 'show']);
            Route::delete('/{meal}', [MealController::class, 'delete']);
        });
        Route::prefix('mealitem')->group(function () {
            Route::get('/', [MealItemController::class, 'index']);
            Route::post('/', [MealItemController::class, 'create']);
            Route::put('/{mealItem}', [MealItemController::class, 'update']);
            Route::get('/{mealItem}', [MealItemController::class, 'show']);
            Route::delete('/{mealItem}', [MealItemController::class, 'delete']);
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

});
