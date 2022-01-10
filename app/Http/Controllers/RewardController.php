<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Helpers\RewardObjectHandler;
use App\Models\Character;
use App\Models\Village;
use App\Http\Resources\CharacterResource;
use App\Http\Resources\VillageResource;

class RewardController extends Controller
{
    public function updateRewardObj(Request $request){
        $activeReward = RewardObjectHandler::updateActiveReward($request['type'], $request['id'], $request['name']);
        return new JsonResponse([
            'message' => ['success' => ['You have changed the name.']], 
            'rewardObj' => $activeReward]);
    }

    /**
     * Returns all the characters owned by the authenticated user.
     */
    public function fetchAllCharactersByUser(){ //tested only when exists TODO
        $characters = Character::where('user_id', Auth::user()->id)->get();
        return $characters ? CharacterResource::collection($characters) : null;
    }
    /**
     * Returns all the villages owned by the authenticated user.
     */
    public function fetchAllVillagesByUser(){ //tested only when exists TODO
        $villages = Village::where('user_id', Auth::user()->id)->get();
        return $villages ? VillageResource::collection($villages) : null;
    }
}
