<?php

use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Notifications API Routes
|--------------------------------------------------------------------------
|
| All API calls that have the valid-auth middleware. Only logged in users 
| can reach these api calls. These calls are all about notifications.
|
*/

Route::group(['middleware' => ['valid-auth']], function () {
    Route::get('/', [NotificationController::class, 'show']);
    Route::group(['middleware' => ['can:update,notification']], function () {
        Route::delete('/{notification}', [NotificationController::class, 'destroy']);
        Route::put('/{notification}/disable-action', [NotificationController::class, 'disableAction']);
    });
});
Route::group(['middleware' => ['admin']], function () {
    Route::post('/all', [NotificationController::class, 'sendNotificationToAll']);
});