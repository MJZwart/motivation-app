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

    Route::group(['middleware' => ['can:view,group']], function () {
        Route::get('/{group}', [GroupsController::class, 'show']);
    });

    Route::post('/', [GroupsController::class, 'store']);

    Route::group(['middleware' => ['can:join,group']], function () {
        Route::post('/join/{group}', [GroupsController::class, 'join']);
        Route::post('/apply/{group}', [GroupsController::class, 'apply']);
    });

    Route::post('/leave/{group}', [GroupsController::class, 'leave'])->can('leave', 'group');

    Route::post('/invite/{group}/accept/{invite}', [GroupsController::class, 'acceptGroupInvite'])->can('joinPrivate', 'group');
    Route::post('/invite/{group}/reject/{invite}', [GroupsController::class, 'rejectGroupInvite']);
    
    Route::group(['middleware' => ['can:update,group']], function () {
        Route::put('/edit/{group}', [GroupsController::class, 'update']);
        Route::get('/roles/{group}', [GroupsController::class, 'getRoles']);
        Route::put('/roles/{group}/update/{role}/name', [GroupsController::class, 'updateRoleName']);
        Route::put('/roles/{group}/update/', [GroupsController::class, 'updateRoles']);
        Route::post('/roles/{group}', [GroupsController::class, 'storeRole']);
        Route::delete('/roles/{group}/delete/{role}', [GroupsController::class, 'destroyRole']);
    });

    Route::group(['middleware' => ['can:recruit,group']], function () {
        Route::get('/applications/show/{group}', [GroupsController::class, 'showApplications']);

        Route::post('/invite/{group}', [GroupsController::class, 'sendGroupInvite']);

        Route::post('/applications/{group}/reject/{application}', [GroupsController::class, 'rejectGroupApplication']);
        Route::post('/applications/{group}/accept/{application}', [GroupsController::class, 'acceptGroupApplication']);
        Route::post('/applications/{group}/suspend/{application}', [GroupsController::class, 'suspendGroupApplication']);

        Route::post('/kick/{group}', [GroupsController::class, 'removeUserFromGroup']);
        Route::post('/suspend/{group}', [GroupsController::class, 'suspendUserFromGroup']);

        Route::get('/blocked/{group}', [GroupsController::class, 'getBlockedUsers']);
        Route::post('/unblock/{group}', [GroupsController::class, 'unblockUserFromGroup']);

        Route::put('/roles/{group}/user/{groupUser}/role/{role}', [GroupsController::class, 'updateGroupUserRole']);
    });
    
    Route::delete('/{group}', [GroupsController::class, 'destroy'])->can('delete', 'group');
    Route::put('/{group}/transfer/{groupUser}', [GroupsController::class, 'transferOwnership'])->can('delete', 'group');

    Route::group(['middleware' => ['can:message,group']], function () {
        Route::get('/{group}/messages', [GroupsController::class, 'getMessages']);
        Route::post('/{group}/messages', [GroupsController::class, 'storeMessage']);
    });
    Route::delete('/{group}/messages/{groupMessage}', [GroupsController::class, 'deleteMessage'])->can('manageMessage', ['group', 'groupMessage']);
});
