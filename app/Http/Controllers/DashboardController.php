<?php

namespace App\Http\Controllers;

use App\Helpers\CharacterHandler;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\TaskListResource;
use App\Http\Resources\CharacterResource;
use App\Helpers\VillageHandler;
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
        $rewardObj = null;
        if($user->rewards == 'CHARACTER'){
            $rewardObj = new CharacterResource(CharacterHandler::findActiveCharacter($user->id));
        } else if ($user->rewards == 'VILLAGE') {
            $rewardObj = new VillageResource(VillageHandler::findActiveVillage($user->id));
        }
        return new JsonResponse(['taskLists' => $taskLists, 'rewardObj' => $rewardObj]);
    }
}
