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
     * Returns the task lists and character in an object
     */
    public function getDashboard()
    {
        /** @var User */
        $user = Auth::user();
        $taskListCollection = TaskListResource::collection($user->taskLists);
        $taskCollection = TaskResource::collection($user->getActiveTasks());
        $village = new VillageResource(VillageHandler::findActiveVillage($user->id)); // Test this, may fail when creating resource on null
        return new JsonResponse(['taskLists' => $taskListCollection, 'village' => $village, 'tasks' => $taskCollection]);
    }
}
