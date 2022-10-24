<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\TaskListController;
use App\Http\Controllers\TaskController;
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

$routes = str_replace(".php", "", array_diff(scandir(base_path() . '/routes/api'), array('.', '..')));
foreach ($routes as $route) {
    Route::prefix($route)->group(base_path('routes/api/' . $route . '.php'));
}

Route::group(['middleware' => ['web']], function () {
    Route::get('/me', [AuthenticationController::class, 'me']);

    Route::post('/login', [AuthenticationController::class, 'authenticate']);
    Route::post('/logout', [AuthenticationController::class, 'logout']);
    Route::post('/register', [RegisteredUserController::class, 'store']);

    Route::get('/examples/tasks', [ExampleTaskController::class, 'fetchExampleTasks']);
    Route::post('/feedback', [FeedbackController::class, 'store']);

    Route::post('/send-reset-password', [AuthenticationController::class, 'getResetPasswordLink']);
    Route::post('/password/reset', [AuthenticationController::class, 'resetPassword'])->name('password.reset');
});

Route::group(['middleware' => ['valid-auth']], function () {
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

    Route::get('/profile/{user}', [UserController::class, 'show']);

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

    Route::put('/user/{blockedUser}/block', [MessageController::class, 'blockUser']);
    Route::get('/user/blocklist', [UserController::class, 'getBlocklist']);
    Route::put('/user/{blockedUser}/unblock', [UserController::class, 'unblockUser']);

    Route::get('/unread', [UserController::class, 'hasUnread']);

    Route::post('/user/{user}/report', [UserController::class, 'reportUser']);
});
