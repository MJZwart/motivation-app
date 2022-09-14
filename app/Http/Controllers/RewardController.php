<?php

namespace App\Http\Controllers;

use App\Helpers\ActionTrackingHandler;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Helpers\RewardObjectHandler;
use App\Models\Character;
use App\Models\Village;
use App\Models\User;
use App\Http\Resources\CharacterResource;
use App\Http\Resources\VillageResource;
use App\Http\Resources\UserResource;

class RewardController extends Controller
{
    public function updateRewardObj(Request $request)
    {
        $activeReward = RewardObjectHandler::updateActiveReward($request['rewardType'], $request['id'], $request['name']);
        ActionTrackingHandler::handleAction($request, 'UPDATE_INSTANCE', 'Updating ' . $request['rewardType'] . ' with new name ' . $request['name']);
        return new JsonResponse([
            'message' => ['success' => 'You have changed the name.'],
            'rewardObj' => $activeReward
        ]);
    }

    /**
     * Returns all the characters owned by the authenticated user.
     */
    public function fetchAllCharactersByUser()
    { //tested only when exists TODO
        $characters = Character::where('user_id', Auth::user()->id)->get();
        return $characters ? CharacterResource::collection($characters) : null;
    }
    /**
     * Returns all the villages owned by the authenticated user.
     */
    public function fetchAllVillagesByUser()
    { //tested only when exists TODO
        $villages = Village::where('user_id', Auth::user()->id)->get();
        return $villages ? VillageResource::collection($villages) : null;
    }

    public function fetchAllRewardInstancesByUser()
    {
        $user = Auth::user();
        $characters = Character::where('user_id', $user->id)->get();
        $villages = Village::where('user_id', $user->id)->get();
        return new JsonResponse(['rewards' => [
            'characters' => $characters ? CharacterResource::collection($characters) : null,
            'villages' => $villages ? VillageResource::collection($villages) : null
        ]]);
    }

    public function activateRewardInstance(Request $request)
    {
        /** @var User */
        $user = Auth::user();
        RewardObjectHandler::toggleActiveRewardObject($request['rewardType'], $user, $request['id']);
        ActionTrackingHandler::handleAction($request, 'ACTIVATE_INSTANCE', 'Activating ' . $request['rewardType'] . ' ' . $request['id']);
        if ($request['rewardType'] != $user->rewards)
            $user->update(['rewards' => $request['rewardType']]);
        ActionTrackingHandler::handleAction($request, 'UPDATE_USER', 'Updating reward type to ' . $request['rewardType']);
        return new JsonResponse(['message' => ['success' => 'You have activated ' . $request['name']], 'user' => new UserResource(Auth::user())]);
    }

    public function deleteInstance(Request $request)
    {
        RewardObjectHandler::deleteRewardObject($request['rewardType'], $request['id']);
        ActionTrackingHandler::handleAction($request, 'DELETE_INSTANCE', 'Deleting ' . $request['rewardType'] . ' ' . $request['id']);
        return new JsonResponse(['message' => ['success' => 'You have deleted ' . $request['name']]]);
    }
}
