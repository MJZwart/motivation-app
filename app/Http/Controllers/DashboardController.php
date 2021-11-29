<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
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
            $character = new CharacterResource(Character::where('user_id', $user->id)->get()->first());
        }
        return new JsonResponse(['taskLists' => $taskLists, 'character' => $character]);
    }
}
