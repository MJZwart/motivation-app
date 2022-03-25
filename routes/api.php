<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\TaskListController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\AchievementController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\ExampleTaskController;
use App\Http\Controllers\BugReportController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OverviewController;
use App\Http\Controllers\RewardController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\GroupsController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\FeedbackController;

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

//Middleware for admin

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
    Route::post('/tasks/merge/{tasklist}', [TaskListController::class, 'mergeTasks']);

    Route::get('/rewards/all', [RewardController::class, 'fetchAllRewardInstancesByUser']);
    Route::put('/reward/activate', [RewardController::class, 'activateRewardInstance']);
    Route::put('/reward/delete', [RewardController::class, 'deleteInstance']);

    Route::put('/reward/update', [RewardController::class, 'updateRewardObj']);
    
    Route::get('/notifications', [NotificationController::class, 'show']);
    Route::post('/notifications/all', [NotificationController::class, 'sendNotificationToAll']);
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy']);
    Route::get('/profile/{user}', [UserController::class, 'show']);
  
    Route::post('/friend/request/{user}', [FriendController::class, 'sendFriendRequest']);
    Route::get('/friend/requests/all', [FriendController::class, 'getAllRequests']);
    Route::post('/friend/request/{friend}/accept', [FriendController::class, 'acceptFriendRequest']);
    Route::post('/friend/request/{friend}/deny', [FriendController::class, 'denyFriendRequest']);
    Route::delete('/friend/request/{friend}', [FriendController::class, 'removeFriendRequest']);
    Route::delete('/friend/remove/{friend}', [FriendController::class, 'destroy']);

    Route::put('/user/settings/email', [UserController::class, 'updateEmail']);
    Route::put('/user/settings/password', [UserController::class, 'updatePassword']);
    Route::put('/user/settings', [UserController::class, 'updateSettings']);
    Route::put('/user/settings/rewards', [UserController::class, 'updateRewardsType']);

    Route::get('/isadmin', [UserController::class, 'isAdmin']);

    Route::post('/search', [UserController::class, 'searchUser']);
    Route::post('/register/confirm', [RegisteredUserController::class, 'confirmRegister']);

    Route::resource('/bugreport', BugReportController::class)->only([
        'store', 'update',
    ]);
    Route::get('/dashboard', [DashboardController::class, 'getDashboard']);
    Route::get('/overview', [OverviewController::class, 'getOverview']);

    Route::get('/conversations', [MessageController::class, 'getConversations']);
    Route::post('/message', [MessageController::class, 'sendMessage']);
    Route::delete('/message/{message}', [MessageController::class, 'deleteMessage']);
    Route::put('/conversation/{conversation}/read', [MessageController::class, 'markConversationAsRead']);

    Route::post('/groups/join/{group}', [GroupsController::class, 'join']);
    Route::post('/groups/leave/{group}', [GroupsController::class, 'leave']);
    Route::resource('/groups', GroupsController::class)->only([
        'store', 'destroy', 'show',
    ]);
    Route::put('/user/{blockedUser}/block', [MessageController::class, 'blockUser']);

    Route::get('/unread', [UserController::class, 'hasUnread']);

    Route::post('/user/{user}/report', [UserController::class, 'reportUser']);
});

Route::group(['middleware' => ['admin']], function () {

    Route::resource('/achievements', AchievementController::class)->only([
        'store', 'update',
    ]);
    Route::get('/admin/dashboard', [AdminController::class, 'getAdminDashboard']);
    Route::put('/admin/experience_points', [AdminController::class, 'updateExeriencePoints']);
    Route::put('/admin/character_exp_gain', [AdminController::class, 'updateCharacterExpGain']);
    Route::put('/admin/village_exp_gain', [AdminController::class, 'updateVillageExpGain']);
    Route::get('/admin/conversation/{id}', [AdminController::class, 'getConversationById']);
    Route::post('/admin/experience_points', [AdminController::class, 'addNewLevel']);
});

// Route::get('/achievements', [AchievementController::class, 'showAll']);
// Route::get('/achievements/triggers', [AchievementController::class, 'showTriggers']);


Route::get('/examples/tasks', [ExampleTaskController::class, 'fetchExampleTasks']);
Route::post('/feedback', [FeedbackController::class, 'store']);

