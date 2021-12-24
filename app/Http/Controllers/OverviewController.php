<?php

namespace App\Http\Controllers;

use App\Helpers\CharacterHandler;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\CharacterResource;
use App\Http\Resources\StatsResource;

class OverviewController extends Controller
{
    /**
     * Collects the information needed for the Overview page: A reward if active, achievements and stats
     * Returns and parses this into a Json response
     */
    public function getOverview(){
        $user = Auth::user();
        $character = null;
        if($user->rewards == 'CHARACTER'){
            $character = new CharacterResource(CharacterHandler::findActiveCharacter($user->id));
        }
        $achievements = $user->achievements;
        $stats = new StatsResource($user);
        return new JsonResponse(['character' => $character, 'achievements' => $achievements, 'stats' => $stats]);
    }
}
