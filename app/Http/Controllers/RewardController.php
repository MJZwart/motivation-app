<?php

namespace App\Http\Controllers;

use App\Helpers\ActionTrackingHandler;
use App\Helpers\ResponseWrapper;
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
        RewardObjectHandler::updateActiveReward($request['rewardType'], $request['id'], $request['name']);
        ActionTrackingHandler::registerAction($request, 'UPDATE_INSTANCE', 'Updating ' . $request['rewardType'] . ' with new name ' . $request['name']);
        return ResponseWrapper::successResponse(__('messages.reward.name_changed'));
    }

    /**
     * Returns all the characters owned by the authenticated user.
     */
    public function fetchAllCharactersByUser()
    {
        $characters = Character::where('user_id', Auth::user()->id)->get();
        return $characters ? CharacterResource::collection($characters) : null;
    }
    /**
     * Returns all the villages owned by the authenticated user.
     */
    public function fetchAllVillagesByUser()
    {
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
        ActionTrackingHandler::registerAction($request, 'ACTIVATE_INSTANCE', 'Activating ' . $request['rewardType'] . ' ' . $request['id']);
        if ($request['rewardType'] != $user->rewards)
            $user->update(['rewards' => $request['rewardType']]);
        ActionTrackingHandler::registerAction($request, 'UPDATE_USER', 'Updating reward type to ' . $request['rewardType']);
        return ResponseWrapper::successResponse(__('messages.reward.activated', ['name' => $request['name']]), ['user' => new UserResource(Auth::user())]);
    }

    public function deleteInstance(Request $request)
    {
        RewardObjectHandler::deleteRewardObject($request['rewardType'], $request['id']);
        ActionTrackingHandler::registerAction($request, 'DELETE_INSTANCE', 'Deleting ' . $request['rewardType'] . ' ' . $request['id']);
        return ResponseWrapper::successResponse(__('messages.reward.deleted', ['name' => $request['name']]));
    }
}
