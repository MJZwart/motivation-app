<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AchievementController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BugReportController;

/*
|--------------------------------------------------------------------------
| Admin API Routes
|--------------------------------------------------------------------------
|
| All API calls that have the admin middleware. Users should not be able 
| to reach any of these, as protected by both the router and the Requests
| validation.
|
*/

//Middleware for admin
Route::group(['middleware' => ['admin']], function () {

    Route::resource('/achievements', AchievementController::class)->only([
        'store', 'update',
    ]);
    Route::get('/dashboard', [AdminController::class, 'getAdminDashboard']);
    Route::get('/overview', [AdminController::class, 'getOverview']);  

    Route::get('/reported_users', [AdminController::class, 'getReportedUsers']);
    Route::post('/reported_users/{reportedUser}', [AdminController::class, 'closeReport']);
    Route::get('/conversation/{id}', [AdminController::class, 'getConversationById']);

    Route::put('/experience_points', [AdminController::class, 'updateExeriencePoints']);
    Route::put('/character_exp_gain', [AdminController::class, 'updateCharacterExpGain']);
    Route::put('/village_exp_gain', [AdminController::class, 'updateVillageExpGain']);

    Route::post('/experience_points', [AdminController::class, 'addNewLevel']);

    Route::get('/feedback', [AdminController::class, 'getFeedback']);
    Route::post('/feedback/archive/{feedback}', [AdminController::class, 'toggleArchiveFeedback']);
    
    Route::get('/suspendedusers', [AdminController::class, 'getSuspendedUsers']);
    Route::post('/suspend/{user}', [AdminController::class, 'suspendUser']);
    Route::post('/editsuspension/{suspendedUser}', [AdminController::class, 'editUserSuspension']);

    Route::get('/bugreport', [BugReportController::class, 'getBugReports']);
    Route::put('/bugreport/{bugReport}', [BugReportController::class, 'update']);

    Route::get('/action/filters', [AdminController::class, 'getActionFilters']);
    Route::post('/action/filters', [AdminController::class, 'getActionsWithFilters']);

    Route::put('/groups/{group}/messages/daterange', [AdminController::class, 'getGroupMessagesByDateRange']);
});
