<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\TaskListResource;
use App\Http\Resources\TaskResource;
use App\Helpers\RewardObjectHandler;

class DashboardController extends Controller
{
    /**
     * Fetches the authenticated user's active task list and reward option
     * Returns the task lists and character in an object
     */
    public function getDashboard()
    {
        /** @var User */
        $user = Auth::user();
        $taskListCollection = TaskListResource::collection($user->taskLists);
        $taskCollection = TaskResource::collection($user->getActiveTasks());
        $rewardObj = RewardObjectHandler::getActiveRewardObjectResourceByUser($user->rewards, $user->id);
        return new JsonResponse(['taskLists' => $taskListCollection, 'rewardObj' => $rewardObj, 'tasks' => $taskCollection]);
    }
}
