<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GroupsController;

/*
|--------------------------------------------------------------------------
| Groups API Routes
|--------------------------------------------------------------------------
|
| All API calls that have the valid-auth middleware. Only logged in users 
| can reach these api calls. These calls are all about notifications.
|
*/

Route::group(['middleware' => ['valid-auth']], function () {
    Route::get('/dashboard', [GroupsController::class, 'dashboard']);
    Route::delete('/{group}', [GroupsController::class, 'destroy']);
    Route::post('/', [GroupsController::class, 'store']);

    Route::get('/{group}', [GroupsController::class, 'show']);

    Route::post('/join/{group}', [GroupsController::class, 'join']);
    Route::post('/apply/{group}', [GroupsController::class, 'apply']);
    Route::post('/leave/{group}', [GroupsController::class, 'leave']);

    Route::get('/applications/show/{group}', [GroupsController::class, 'showApplications']);

    Route::post('/invite', [GroupsController::class, 'sendGroupInvite']);

    Route::post('/invite/accept/{invite}', [GroupsController::class, 'acceptGroupInvite']);
    Route::post('/invite/reject/{invite}', [GroupsController::class, 'rejectGroupInvite']);
    Route::post('/applications/reject/{application}', [GroupsController::class, 'rejectGroupApplication']);
    Route::post('/applications/accept/{application}', [GroupsController::class, 'acceptGroupApplication']);
    Route::post('/applications/suspend/{application}', [GroupsController::class, 'suspendGroupApplication']);

    Route::put('/edit/{group}', [GroupsController::class, 'update']);
    Route::post('/kick/{group}', [GroupsController::class, 'removeUserFromGroup']);
    Route::post('/suspend/{group}', [GroupsController::class, 'suspendUserFromGroup']);

    Route::get('/blocked/{group}', [GroupsController::class, 'getBlockedUsers']);
    Route::post('/unblock/{group}', [GroupsController::class, 'unblockUserFromGroup']);
});
