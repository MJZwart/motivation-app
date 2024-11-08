<?php

namespace App\Http\Controllers;

use App\Helpers\ActionTrackingHandler;
use App\Helpers\ResponseWrapper;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\Village;
use App\Models\User;
use App\Http\Resources\VillageResource;
use App\Http\Resources\UserResource;
use App\Helpers\VillageHandler;

class RewardController extends Controller
{
    public function updateVillage(Request $request)
    {
        VillageHandler::updateActiveVillage($request['id'], $request['name']);
        ActionTrackingHandler::registerAction($request, 'UPDATE_INSTANCE', 'Updating ' . $request['rewardType'] . ' with new name ' . $request['name']);
        return ResponseWrapper::successResponse(__('messages.reward.name_changed'));
    }
    
    public function fetchAllVillagesByUser()
    {
        $user = Auth::user();
        $villages = Village::where('user_id', $user->id)->get();
        return new JsonResponse(['villages' => $villages ? VillageResource::collection($villages) : null]);
    }

    public function activateVillage(Request $request)
    {
        /** @var User */
        $user = Auth::user();
        VillageHandler::toggleVillageActive($request['rewardType'], $user, $request['id']);
        ActionTrackingHandler::registerAction($request, 'ACTIVATE_INSTANCE', 'Activating ' . $request['rewardType'] . ' ' . $request['id']);
        if ($request['rewardType'] != $user->rewards)
            $user->update(['rewards' => $request['rewardType']]);
        ActionTrackingHandler::registerAction($request, 'UPDATE_USER', 'Updating reward type to ' . $request['rewardType']);
        return ResponseWrapper::successResponse(__('messages.reward.activated', ['name' => $request['name']]), ['user' => new UserResource(Auth::user())]);
    }

    public function deleteVillage(Request $request)
    {
        VillageHandler::deleteVillage($request['id']);
        ActionTrackingHandler::registerAction($request, 'DELETE_INSTANCE', 'Deleting ' . $request['rewardType'] . ' ' . $request['id']);
        return ResponseWrapper::successResponse(__('messages.reward.deleted', ['name' => $request['name']]));
    }
}
