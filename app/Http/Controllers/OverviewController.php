<?php

namespace App\Http\Controllers;

use App\Http\Resources\AchievementEarnedResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\StatsResource;
use App\Http\Resources\TimelineResource;
use App\Models\TimelineAction;
use App\Models\User;
use App\Helpers\VillageHandler;
use App\Http\Resources\VillageResource;

class OverviewController extends Controller
{
    /**
     * Collects the information needed for the Overview page: A village if active, achievements and stats
     * Returns and parses this into a Json response
     */
    public function getOverview()
    {
        $user = Auth::user();
        $village = VillageHandler::findActiveVillage($user->id);
        $achievements = AchievementEarnedResource::collection($user->achievements);
        $stats = new StatsResource($user);
        return new JsonResponse(['village' => $village ? new VillageResource($village) : null, 'achievements' => $achievements, 'stats' => $stats]);
    }

    public function getTimelineFromUser(User $user)
    {
        $timeline = $user->timeline->sortByDesc('timestamp');
        $types = TimelineAction::where('user_id', $user->id)->select('type')->distinct()->get();
        return new JsonResponse(['timeline' => TimelineResource::collection($timeline), 'types' => $types]);
    }
}
