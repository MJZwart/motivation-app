<?php

use App\Http\Controllers\RewardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Reward API Routes
|--------------------------------------------------------------------------
|
| All API calls that have the valid-auth middleware. Only logged in users can
| reach these api calls. These calls are all about villages.
|
*/

Route::group(['middleware' => ['valid-auth']], function () {
    Route::get('/all', [RewardController::class, 'fetchAllVillagesByUser']);
    Route::put('/activate', [RewardController::class, 'activateVillage']);
    Route::put('/delete', [RewardController::class, 'deleteVillage']);

    Route::put('/update', [RewardController::class, 'updateVillage']);
});
