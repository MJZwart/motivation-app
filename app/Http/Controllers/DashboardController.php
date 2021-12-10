<?php

namespace App\Http\Controllers;

use App\Helpers\CharacterHandler;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use App\Models\Character;
use App\Http\Resources\TaskListResource;
use App\Http\Resources\CharacterResource;

class DashboardController extends Controller
{
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
