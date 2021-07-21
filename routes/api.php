<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\TaskListController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\AchievementController;
use App\Http\Controllers\CharacterController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FriendController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [AuthenticationController::class, 'authenticate']);
Route::post('/logout', [AuthenticationController::class, 'logout']);
Route::post('/register', [RegisteredUserController::class, 'store']);

Route::group(['middleware' => ['auth']], function () {
    Route::resource('/tasks', TaskController::class)->only([
        'store', 'show', 'update', 'destroy'
    ]);
    Route::put('/tasks/complete/{task}', [TaskController::class, 'complete']);

    Route::resource('/tasklists', TaskListController::class)->only([
        'store', 'show', 'update', 'destroy'
    ]);
    Route::get('/tasklists', [TaskListController::class, 'showTaskLists']);
    Route::post('/tasks/merge/{tasklist}', [TaskListController::class, 'mergeTasks']);

    Route::resource('/character', CharacterController::class)->only([
        'store', 'update', 'destroy',
    ]);
    Route::get('/character', [CharacterController::class, 'show']);
    Route::get('/notifications', [NotificationController::class, 'show']);
    Route::get('/notifications/unread', [NotificationController::class, 'hasUnreadNotifications']);
    Route::get('/profile/{user}', [UserController::class, 'show']);

    Route::post('/friend/request/{user}', [FriendController::class, 'sendFriendRequest']);
    Route::get('/achievements/{user}', [AchievementController::class, 'show']);
    Route::get('/user/stats', [UserController::class, 'showStats']);
});

Route::get('/experience', [CharacterController::class, 'getExperienceTable']);