<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Notifications API Routes
|--------------------------------------------------------------------------
|
| All API calls that have the valid-auth middleware. Only logged in users 
| can reach these api calls. These calls are all about users and their settings.
|
*/

Route::group(['middleware' => ['valid-auth']], function () {
    //Settings
    Route::put('/settings/email', [UserController::class, 'updateEmail']);
    Route::put('/settings/password', [UserController::class, 'updatePassword']);
    Route::put('/settings', [UserController::class, 'updateSettings']);
    Route::put('/settings/rewards', [UserController::class, 'updateRewardsType']);
    Route::put('/settings/language', [UserController::class, 'updateLanguage']);

    //Report user
    Route::post('/user/{user}/report', [UserController::class, 'reportUser']);

    //Blocklist
    Route::put('/user/{blockedUser}/block', [MessageController::class, 'blockUser']);
    Route::get('/user/blocklist', [UserController::class, 'getBlocklist']);
    Route::put('/user/{blockedUser}/unblock', [UserController::class, 'unblockUser']);
});
