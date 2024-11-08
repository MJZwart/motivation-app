<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\TaskListResource;
use App\Http\Resources\TaskResource;
use App\Http\Resources\VillageResource;
use App\Helpers\VillageHandler;

class DashboardController extends Controller
{
    /**
     * Fetches the authenticated user's active task list and reward option
     * Returns the task lists and village in an object
     */
    public function getDashboard()
    {
        /** @var User */
        $user = Auth::user();
        $taskListCollection = TaskListResource::collection($user->taskLists);
        $taskCollection = TaskResource::collection($user->getActiveTasks());
        $village = VillageHandler::findActiveVillage($user->id);
        return new JsonResponse(['taskLists' => $taskListCollection, 'village' => $village ? new VillageResource($village) : null, 'tasks' => $taskCollection]);
    }
}
