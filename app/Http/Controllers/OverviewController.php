<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use App\Models\Character;
use App\Http\Resources\CharacterResource;
use App\Http\Resources\StatsResource;

class OverviewController extends Controller
{
    public function getOverview(){
        $user = Auth::user();
        $character = null;
        if($user->rewards == 'CHARACTER'){
            $character = new CharacterResource(Character::where('user_id', $user->id)->get()->first());
        }
        $achievements = $user->achievements;
        $stats = new StatsResource($user);
        return new JsonResponse(['character' => $character, 'achievements' => $achievements, 'stats' => $stats]);
    }
}
