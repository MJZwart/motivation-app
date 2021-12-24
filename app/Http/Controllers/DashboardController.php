<?php

namespace App\Http\Controllers;

use App\Helpers\CharacterHandler;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\TaskListResource;
use App\Http\Resources\CharacterResource;

class DashboardController extends Controller
{
    /**
     * Fetches the authenticated user's active task list and reward option
     * Returns the task lists and character in an object
     */
    public function getDashboard(){
        $user = Auth::user();
        $taskLists = TaskListResource::collection($user->taskLists);
        $character = null;
        if($user->rewards == 'CHARACTER'){
            $character = new CharacterResource(CharacterHandler::findActiveCharacter($user->id));
        }
        return new JsonResponse(['taskLists' => $taskLists, 'character' => $character]);
    }
}
