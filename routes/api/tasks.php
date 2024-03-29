<?php

use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskListController;
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
    Route::post('/', [TaskController::class, 'store']);
    Route::group(['middleware' => ['can:update,task']], function() {
        Route::delete('/{task}', [TaskController::class, 'destroy']);
        Route::put('/{task}', [TaskController::class, 'update']);
        Route::put('/complete/{task}', [TaskController::class, 'complete']);
    });
    
    Route::get('/templates', [TaskController::class, 'getTemplates']);
    Route::post('/templates', [TaskController::class, 'storeTemplate']);
    Route::put('/templates/{template}', [TaskController::class, 'updateTemplate']);
    Route::delete('/templates/{template}', [TaskController::class, 'deleteTemplate']);

    Route::post('/merge/{tasklist}', [TaskListController::class, 'mergeTasks']);

    Route::get('/completions', [TaskController::class, 'getCompletions']);
    // Route::get('/completions-by-range/{startDate}/{endDate}', [TaskController::class, 'getCompletions']);
});
