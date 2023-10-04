<?php

use App\Http\Controllers\MessageController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Message API Routes
|--------------------------------------------------------------------------
|
| All API calls that have the valid-auth middleware. Only logged in users can
| reach these api calls. These calls are all about messages.
|
*/

Route::group(['middleware' => ['valid-auth', 'not-guest']], function () {
    Route::get('/conversations', [MessageController::class, 'getConversations']);
    Route::post('/', [MessageController::class, 'sendMessage']);
    Route::put('/conversation/{conversation}/read', [MessageController::class, 'markConversationAsRead']);
    Route::delete('/{message}', [MessageController::class, 'deleteMessage'])->can('update', ['message']);
});
