<?php

use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\SubDistrictController;
use App\Models\Notification;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Profiler\Profile;

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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/select-option', [PropertyController::class, 'selectOption']);
Route::get('/cities', [PropertyController::class, 'getCityByProvince']);
Route::get('/sub-districts', [PropertyController::class, 'getSubDistrictByCity']);
Route::get('/sub-type', [PropertyController::class, 'getSubCategoryByCategory']);
Route::get('/properties', [PropertyController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']); 

    Route::get('/profile', [ProfileController::class, 'index']);
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::put('/profile/change-password', [ProfileController::class, 'changePassword']);

    Route::prefix('properties')->group(function () {
        Route::get('/my-property', [PropertyController::class, 'myProperty']);
        Route::get('/my-favorite', [PropertyController::class, 'myFavorite']);
        Route::get('{id}', [PropertyController::class, 'show']);
        Route::post('/', [PropertyController::class, 'store']);
        Route::put('{id}', [PropertyController::class, 'update']);
        Route::post('{id}/review', [PropertyController::class, 'review']);
        Route::put('{id}/like', [PropertyController::class, 'likeProperty']);
        Route::put('{id}/view', [PropertyController::class, 'addView']);
    });

    Route::prefix('chats')->group(function () {
        Route::get('/', [ChatController::class, 'index']);
        Route::get('{id}', [ChatController::class, 'chatMessages']);
    });

    Route::post('/send-message', [ChatController::class, 'sendMessage']);

    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index']);
        Route::put('read-all', [NotificationController::class, 'readAllNotif']);
        Route::put('{id}/read', [NotificationController::class, 'readNotif']);
    });
});