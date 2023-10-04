<?php

use App\Http\Controllers\FriendController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Friend API Routes
|--------------------------------------------------------------------------
|
| All API calls that have the valid-auth middleware. Only logged in users 
| can reach these api calls. These calls are all about friends.
|
*/

Route::group(['middleware' => ['valid-auth', 'not-guest']], function () {
    Route::get('/', [FriendController::class, 'getFriends']);
    Route::post('/request/{user}', [FriendController::class, 'sendFriendRequest']);
    Route::get('/requests/all', [FriendController::class, 'getAllRequests']);
    Route::post('/request/{friend}/accept', [FriendController::class, 'acceptFriendRequest']);
    Route::post('/request/{friend}/deny', [FriendController::class, 'denyFriendRequest']);
    Route::delete('/request/{friend}', [FriendController::class, 'removeFriendRequest']);
    Route::delete('/remove/{friend}', [FriendController::class, 'destroy']);
});
