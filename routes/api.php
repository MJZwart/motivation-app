<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\TaskListController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ExampleTaskController;
use App\Http\Controllers\BugReportController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OverviewController;
use App\Http\Controllers\RewardController;
use App\Http\Controllers\MessageController;
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
    Route::post('/bugreport', [BugReportController::class, 'store']);

    Route::post('/send-reset-password', [AuthenticationController::class, 'getResetPasswordLink']);
    Route::post('/password/reset', [AuthenticationController::class, 'resetPassword'])->name('password.reset');
});

Route::group(['middleware' => ['valid-auth']], function () {
    
    Route::post('/tasklists', [TaskListController::class, 'store']);

    Route::group(['middleware' => ['can:update,tasklist']], function () {
        Route::put('/tasklists/{tasklist}', [TaskListController::class, 'update']);
        Route::delete('/tasklists/{tasklist}', [TaskListController::class, 'destroy']);
    });

    Route::get('/rewards/all', [RewardController::class, 'fetchAllRewardInstancesByUser']);
    Route::put('/reward/activate', [RewardController::class, 'activateRewardInstance']);
    Route::put('/reward/delete', [RewardController::class, 'deleteInstance']);

    Route::put('/reward/update', [RewardController::class, 'updateRewardObj']);

    Route::get('/profile/{user}', [UserController::class, 'show']);

    Route::get('/isadmin', [UserController::class, 'isAdmin']);

    Route::post('/search', [UserController::class, 'searchUser']);
    Route::post('/register/confirm', [RegisteredUserController::class, 'confirmRegister']);

    Route::get('/dashboard', [DashboardController::class, 'getDashboard']);
    Route::get('/overview', [OverviewController::class, 'getOverview']);

    Route::get('/conversations', [MessageController::class, 'getConversations']);
    Route::post('/message', [MessageController::class, 'sendMessage']);
    Route::delete('/message/{message}', [MessageController::class, 'deleteMessage']);
    Route::put('/conversation/{conversation}/read', [MessageController::class, 'markConversationAsRead']);

    Route::get('/unread', [UserController::class, 'hasUnread']);
});
