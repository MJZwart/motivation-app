<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AchievementController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BugReportController;
use App\Http\Controllers\GlobalSettingController;
use App\Http\Controllers\MaintenanceBannerController;

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
    
    Route::get('/isadmin', [AdminController::class, 'isAdmin']);

    Route::resource('/achievements', AchievementController::class)->only([
        'store', 'update', 'destroy',
    ]);
    Route::get('/achievements', [AchievementController::class, 'getAllAchievements']);

    Route::get('/overview', [AdminController::class, 'getOverview']);  

    Route::get('/reported-users', [AdminController::class, 'getReportedUsers']);
    Route::post('/reported-users/{reportedUser}', [AdminController::class, 'closeReport']);
    Route::get('/conversation/{id}', [AdminController::class, 'getConversationById']);

    //* Balancing 
    Route::get('/experience-points', [AdminController::class, 'getExperiencePoints']);
    Route::post('/experience-points', [AdminController::class, 'addNewLevel']);
    Route::get('/group-exp', [AdminController::class, 'getGroupExp']);
    Route::post('/group-exp', [AdminController::class, 'storeGroupExp']);

    Route::get('/character-exp-gain', [AdminController::class, 'getCharacterExpGain']);
    Route::get('/village-exp-gain', [AdminController::class, 'getVillageExpGain']);
    Route::put('/experience-points', [AdminController::class, 'updateExperiencePoints']);
    Route::put('/character-exp-gain', [AdminController::class, 'updateCharacterExpGain']);
    Route::put('/village-exp-gain', [AdminController::class, 'updateVillageExpGain']);

    //* Feedback
    Route::get('/feedback', [AdminController::class, 'getFeedback']);
    Route::post('/feedback/archive/{feedback}', [AdminController::class, 'toggleArchiveFeedback']);
    
    //* Suspension
    Route::get('/suspendedusers', [AdminController::class, 'getSuspendedUsers']);
    Route::post('/suspend/{user}', [AdminController::class, 'suspendUser']);
    Route::post('/editsuspension/{suspendedUser}', [AdminController::class, 'editUserSuspension']);

    //* Bug reports
    Route::get('/bugreport', [BugReportController::class, 'getBugReports']);
    Route::get('/bugreport/closed', [BugReportController::class, 'getClosedBugReports']);
    Route::put('/bugreport/{bugReport}', [BugReportController::class, 'update']);
    Route::put('/bugreport/delete/{bugReport}', [BugReportController::class, 'closeBugReport']);
    Route::put('/bugreport/restore/{bugReport}', [BugReportController::class, 'restoreBugReport']);

    Route::get('/action/filters', [AdminController::class, 'getActionFilters']);
    Route::post('/action/filters', [AdminController::class, 'getActionsWithFilters']);

    Route::put('/groups/{group}/messages/daterange', [AdminController::class, 'getGroupMessagesByDateRange']);

    Route::get('/settings', [GlobalSettingController::class, 'getGlobalSettings']);
    Route::post('/settings', [GlobalSettingController::class, 'updateGlobalSettings']);

    //* Maintenance banner
    Route::post('/maintenance-banner', [MaintenanceBannerController::class, 'store']);
    Route::put('/maintenance-banner/{banner}', [MaintenanceBannerController::class, 'update']);
    Route::delete('/maintenance-banner/{banner}', [MaintenanceBannerController::class, 'destroy']);
    Route::get('/maintenance-banner/all', [MaintenanceBannerController::class, 'getAll']);
});
