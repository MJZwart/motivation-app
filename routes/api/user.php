<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\OverviewController;
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

    Route::get('/dashboard', [DashboardController::class, 'getDashboard']);
    Route::get('/overview', [OverviewController::class, 'getOverview']);

    //Settings
    Route::put('/settings/email', [UserController::class, 'updateEmail']);
    Route::put('/settings/password', [UserController::class, 'updatePassword']);
    Route::put('/settings', [UserController::class, 'updateSettings']);
    Route::put('/settings/rewards', [UserController::class, 'updateRewardsType']);
    Route::put('/settings/language', [UserController::class, 'updateLanguage']);
    Route::put('/settings/tutorial', [UserController::class, 'toggleTutorial']);

    //Report user
    Route::post('/{user}/report', [UserController::class, 'reportUser']);

    //Blocklist
    Route::put('/{blockedUser}/block', [UserController::class, 'blockUser']);
    Route::get('/blocklist', [UserController::class, 'getBlocklist']);
    Route::put('/{blockedUser}/unblock', [UserController::class, 'unblockUser']);

    //Overview
    Route::get('/timeline/{user}', [OverviewController::class, 'getTimelineFromUser']);
});
