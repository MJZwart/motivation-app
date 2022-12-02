<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GroupsController;
use App\Models\Group;

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
    Route::post('/', [GroupsController::class, 'store']);

    Route::get('/{group}', [GroupsController::class, 'show']);

    Route::group(['middleware' => ['can:join,group']], function () {
        Route::post('/join/{group}', [GroupsController::class, 'join']);
        Route::post('/apply/{group}', [GroupsController::class, 'apply']);
    });

    Route::post('/leave/{group}', [GroupsController::class, 'leave']);

    Route::group(['middleware' => ['can:joinPrivate,group']], function () {
        Route::post('/invite/{group}/accept/{invite}', [GroupsController::class, 'acceptGroupInvite']);
        Route::post('/invite/{group}/reject/{invite}', [GroupsController::class, 'rejectGroupInvite']);
    });
    
    Route::group(['middleware' => ['can:update,group']], function () {
        Route::delete('/{group}', [GroupsController::class, 'destroy']);
        Route::get('/applications/show/{group}', [GroupsController::class, 'showApplications']);

        Route::post('/invite/{group}', [GroupsController::class, 'sendGroupInvite']);

        Route::post('/applications/{group}/reject/{application}', [GroupsController::class, 'rejectGroupApplication']);
        Route::post('/applications/{group}/accept/{application}', [GroupsController::class, 'acceptGroupApplication']);
        Route::post('/applications/{group}/suspend/{application}', [GroupsController::class, 'suspendGroupApplication']);

        Route::put('/edit/{group}', [GroupsController::class, 'update']);
        Route::post('/kick/{group}', [GroupsController::class, 'removeUserFromGroup']);
        Route::post('/suspend/{group}', [GroupsController::class, 'suspendUserFromGroup']);
    });
});
