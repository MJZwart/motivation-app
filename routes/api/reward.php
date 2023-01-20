<?php

use App\Http\Controllers\RewardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Reward API Routes
|--------------------------------------------------------------------------
|
| All API calls that have the valid-auth middleware. Only logged in users can
| reach these api calls. These calls are all about rewards (characters/villages).
|
*/

Route::group(['middleware' => ['valid-auth']], function () {
    Route::get('/all', [RewardController::class, 'fetchAllRewardInstancesByUser']);
    Route::put('/activate', [RewardController::class, 'activateRewardInstance']);
    Route::put('/delete', [RewardController::class, 'deleteInstance']);

    Route::put('/update', [RewardController::class, 'updateRewardObj']);
});
