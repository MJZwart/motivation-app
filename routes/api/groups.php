<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GroupsController;

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
Route::group(['middleware' => ['valid-auth']], function () {
    Route::get('/dashboard', [GroupsController::class, 'dashboard']);
    Route::delete('/{group}', [GroupsController::class, 'destroy']);
    Route::post('/', [GroupsController::class, 'store']);

    Route::get('/{group}', [GroupsController::class, 'show']);

    Route::post('/join/{group}', [GroupsController::class, 'join']);
    Route::post('/apply/{group}', [GroupsController::class, 'apply']);
    Route::post('/leave/{group}', [GroupsController::class, 'leave']);

    Route::get('/applications/show/{group}', [GroupsController::class, 'showApplications']);
    Route::post('/applications/reject/{application}', [GroupsController::class, 'rejectGroupApplication']);
    Route::post('/applications/accept/{application}', [GroupsController::class, 'acceptGroupApplication']);
    Route::post('/applications/ban/{application}', [GroupsController::class, 'banGroupApplication']);
    
    Route::put('/edit/{group}', [GroupsController::class, 'update']);
    Route::post('/kick/{group}', [GroupsController::class, 'removeUserFromGroup']);
    Route::post('/ban/{group}', [GroupsController::class, 'banUserFromGroup']);
});
