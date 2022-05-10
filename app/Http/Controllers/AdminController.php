<?php

namespace App\Http\Controllers;

use App\Helpers\ActionTrackingHandler;
use App\Http\Requests\BanUserRequest;
use App\Http\Requests\UpdateExperiencePointsRequest;
use App\Http\Requests\UpdateCharacterExpGainRequest;
use App\Http\Requests\UpdateVillageExpGainRequest;
use App\Http\Requests\StoreNewLevelRequest;
use Illuminate\Http\Request;
use App\Models\Achievement;
use App\Models\AchievementTrigger;
use App\Http\Resources\AchievementResource;
use App\Models\BugReport;
use App\Models\User;
use App\Models\ReportedUser;
use App\Models\Conversation;
use App\Models\ExperiencePoint;
use App\Http\Resources\BugReportResource;
use App\Http\Resources\ReportedUserResource;
use App\Http\Resources\AdminConversationResource;
use App\Models\BannedUser;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function getAdminDashboard() {
        $achievements = AchievementResource::collection(Achievement::get());
        $achievementTriggers = AchievementTrigger::get();
        $bugReports = BugReportResource::collection(BugReport::all());
        $experiencePoints = ExperiencePoint::get();
        $characterExpGain = DB::table('character_exp_gain')->get();
        $villageExpGain = DB::table('village_exp_gain')->get();
        $reportedUsers = ReportedUserResource::collection(
            User::get()->filter(function ($user) {
                return $user->isReported();
            })
        );
        $balancing = ['experience_points' => $experiencePoints,
            'character_exp_gain' => $characterExpGain, 'village_exp_gain' => $villageExpGain];
       
        return new JsonResponse(
            ['achievements' => $achievements, 'achievementTriggers' => $achievementTriggers,
                'bugReports' => $bugReports, 'balancing' => $balancing, 'reportedUsers' => $reportedUsers], 
            Response::HTTP_OK);
    }

    public function updateExeriencePoints(UpdateExperiencePointsRequest $request) {
        $validated = $request->validated();
        ExperiencePoint::upsert($validated, ['id'], ['experience_points']);
        $experiencePoints = ExperiencePoint::get();
        ActionTrackingHandler::handleAction($request, 'ADMIN', 'Updated experience points');
        return new JsonResponse(
            ['message' => ['success' => 'Experience points updated'], 'data' => $experiencePoints], 
            Response::HTTP_OK);
    }

    public function addNewLevel(StoreNewLevelRequest $request) {
        $validated = $request->validated();
        ExperiencePoint::insert($validated);
        $experiencePoints = ExperiencePoint::get();
        ActionTrackingHandler::handleAction($request, 'ADMIN', 'Added new level to experience points');
        return new JsonResponse(
            ['message' => ['success' => 'Level added'], 'data' => $experiencePoints], 
            Response::HTTP_OK);
    }

    public function updateCharacterExpGain(UpdateCharacterExpGainRequest $request) {
        $validated = $request->validated();
        DB::table('character_exp_gain')->upsert($validated, ['id'], ['strength', 'agility', 'endurance', 'intelligence', 'charisma', 'level']);
        $characterExpGain = DB::table('character_exp_gain')->get();
        ActionTrackingHandler::handleAction($request, 'ADMIN', 'Updated character experience gain');
        return new JsonResponse(
            ['message' => ['success' => 'Character experience balancing updated'], 'data' => $characterExpGain], 
            Response::HTTP_OK);
    }

    public function updateVillageExpGain(UpdateVillageExpGainRequest $request) {
        $validated = $request->validated();
        DB::table('village_exp_gain')->upsert($validated, ['id'], ['economy', 'labour', 'craft', 'art', 'community', 'level']);
        $villageExpGain = DB::table('village_exp_gain')->get();
        ActionTrackingHandler::handleAction($request, 'ADMIN', 'Updated village experience gain');
        return new JsonResponse(
            ['message' => ['success' => 'Village experience balancing updated'], 'data' => $villageExpGain], 
            Response::HTTP_OK);
    }
    
    public function getConversationById($id) {
        return new AdminConversationResource(Conversation::where('conversation_id', $id)->first());
    }

    /**
     * Undocumented function untested
     *
     * @param BanUserRequest $request
     * @param User $user
     * @return void
     */
    public function banUser(BanUserRequest $request, User $user) {
        $validated = $request->validated();
        if ($validated['indefinite']) $validated['days'] = 99999;
        $bannedUntilTime = Carbon::now()->addDays($validated['days']);
        $user->active = $bannedUntilTime;
        $user->save();
        BannedUser::create([
            'user_id' => $user->id,
            'reason' => $validated['reason'],
            'admin_id' => Auth::user()->id,
            'days' => $validated['days'],
            'banned_until' => $bannedUntilTime,
        ]);
        return new JsonResponse(
            ['message' => ['success' => 'User banned until '. $bannedUntilTime]],
            Response::HTTP_OK);
    }
}
