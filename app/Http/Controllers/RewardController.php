<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\RewardObjectHandler;
use Illuminate\Http\JsonResponse;

class RewardController extends Controller
{
    public function updateRewardObj(Request $request){
        $activeReward = RewardObjectHandler::updateActiveReward($request['type'], $request['id'], $request['name']);
        return new JsonResponse([
            'message' => ['success' => ['You have changed the name.']], 
            'rewardObj' => $activeReward]);
    }
}
