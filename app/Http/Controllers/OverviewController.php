<?php

namespace App\Http\Controllers;

use App\Helpers\RewardObjectHandler;
use App\Http\Resources\AchievementEarnedResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\StatsResource;
use App\Http\Resources\TimelineResource;
use App\Models\TimelineAction;
use App\Models\User;

class OverviewController extends Controller
{
    /**
     * Collects the information needed for the Overview page: A reward if active, achievements and stats
     * Returns and parses this into a Json response
     */
    public function getOverview()
    {
        $user = Auth::user();
        $rewardObj = RewardObjectHandler::getActiveRewardObjectResourceByUser($user->rewards, $user->id);
        $achievements = AchievementEarnedResource::collection($user->achievements);
        $stats = new StatsResource($user);
        return new JsonResponse(['rewardObj' => $rewardObj, 'achievements' => $achievements, 'stats' => $stats]);
    }

    public function getTimeline()
    {
        /** @var User */
        $user = Auth::user();
        $timeline = $user->timeline->sortByDesc('timestamp');
        $types = TimelineAction::where('user_id', $user->id)->select('type')->distinct()->get();
        return new JsonResponse(['timeline' => TimelineResource::collection($timeline), 'types' => $types]);
    }
    public function getTimelineFromUser(User $user) {
        $timeline = $user->timeline->sortByDesc('timestamp');
        $types = TimelineAction::where('user_id', $user->id)->select('type')->distinct()->get();
        return new JsonResponse(['timeline' => TimelineResource::collection($timeline), 'types' => $types]);
    }
}
