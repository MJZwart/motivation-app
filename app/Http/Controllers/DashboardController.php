<?php

namespace App\Http\Controllers;

use App\Helpers\CharacterHandler;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\TaskListResource;
use App\Http\Resources\CharacterResource;
use App\Helpers\VillageHandler;
use App\Helpers\RewardObjectHandler;
use App\Http\Resources\VillageResource;

class DashboardController extends Controller
{
    /**
     * Fetches the authenticated user's active task list and reward option
     * Returns the task lists and character in an object
     */
    public function getDashboard(){
        $user = Auth::user();
        $taskLists = TaskListResource::collection($user->taskLists);
        $rewardObj = RewardObjectHandler::getActiveRewardObjectResourceByUser($user->rewards, $user->id);
        return new JsonResponse(['taskLists' => $taskLists, 'rewardObj' => $rewardObj]);
    }
}
