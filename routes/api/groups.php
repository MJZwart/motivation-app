<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\GroupUserController;
use App\Http\Controllers\GroupRoleController;

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
    Route::get('/dashboard', [GroupController::class, 'dashboard']);

    Route::group(['middleware' => ['can:view,group']], function () {
        Route::get('/{group}', [GroupController::class, 'show']);
    });

    Route::post('/', [GroupController::class, 'store']);

    Route::group(['middleware' => ['can:join,group']], function () {
        Route::post('/join/{group}', [GroupUserController::class, 'join']);
        Route::post('/apply/{group}', [GroupUserController::class, 'apply']);
    });

    Route::post('/leave/{group}', [GroupUserController::class, 'leave'])->can('leave', 'group');

    Route::post('/invite/{group}/accept/{invite}', [GroupUserController::class, 'acceptGroupInvite'])->can('joinPrivate', 'group');
    Route::post('/invite/{group}/reject/{invite}', [GroupUserController::class, 'rejectGroupInvite']);
    
    Route::group(['middleware' => ['can:update,group']], function () {
        Route::put('/edit/{group}', [GroupController::class, 'update']);
        Route::get('/roles/{group}', [GroupRoleController::class, 'getRoles']);
        Route::put('/roles/{group}/update/{role}/name', [GroupRoleController::class, 'updateRoleName']);
        Route::put('/roles/{group}/update', [GroupRoleController::class, 'updateRoles']);
        Route::post('/roles/{group}', [GroupRoleController::class, 'storeRole']);
        Route::delete('/roles/{group}/delete/{role}', [GroupRoleController::class, 'destroyRole']);
        Route::put('/roles/{group}/role/{role}/up', [GroupRoleController::class, 'rankUp']);
        Route::put('/roles/{group}/role/{role}/down', [GroupRoleController::class, 'rankDown']);
    });

    Route::group(['middleware' => ['can:recruit,group']], function () {
        Route::get('/applications/show/{group}', [GroupUserController::class, 'showApplications']);

        Route::post('/invite/{group}', [GroupUserController::class, 'sendGroupInvite']);

        Route::post('/applications/{group}/reject/{application}', [GroupUserController::class, 'rejectGroupApplication']);
        Route::post('/applications/{group}/accept/{application}', [GroupUserController::class, 'acceptGroupApplication']);
        Route::post('/applications/{group}/suspend/{application}', [GroupUserController::class, 'suspendGroupApplication']);

        Route::post('/kick/{group}', [GroupUserController::class, 'removeUserFromGroup']);
        Route::post('/suspend/{group}', [GroupUserController::class, 'suspendUserFromGroup']);

        Route::get('/blocked/{group}', [GroupUserController::class, 'getBlockedUsers']);
        Route::post('/unblock/{group}', [GroupUserController::class, 'unblockUserFromGroup']);

    });

    Route::put('/roles/{group}/user/{groupUser}/role/{role}', [GroupRoleController::class, 'updateGroupUserRole'])->can('manageUserRoles', ['group', 'groupUser']);
    
    Route::delete('/{group}', [GroupController::class, 'destroy'])->can('delete', 'group');
    Route::put('/{group}/transfer/{groupUser}', [GroupController::class, 'transferOwnership'])->can('delete', 'group');

    Route::group(['middleware' => ['can:message,group']], function () {
        Route::get('/{group}/messages', [GroupController::class, 'getMessages']);
        Route::post('/{group}/messages', [GroupController::class, 'storeMessage']);
    });
    Route::delete('/{group}/messages/{groupMessage}', [GroupController::class, 'deleteMessage'])->can('manageMessage', ['group', 'groupMessage']);
});
