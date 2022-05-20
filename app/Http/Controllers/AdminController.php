<?php

namespace App\Http\Controllers;

use App\Helpers\ActionTrackingHandler;
use App\Http\Requests\BanUserRequest;
use App\Http\Requests\EditUserBanRequest;
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
use App\Http\Resources\BannedUserResource;
use App\Models\BannedUser;
use Carbon\Carbon;
use App\Http\Resources\FeedbackResource;
use App\Models\Feedback;
use App\Models\Notification;
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
     * Bans a user: Changes 'banned_until' to current dateTime + the amount of days the user gets banned.
     * Created a BannedUser to document the suspension of the account, as well as deactivates the
     * user's account.
     *
     * @param BanUserRequest $request
     * @param User $user
     * @return JsonResponse
     */
    public function banUser(BanUserRequest $request, User $user) {
        $validated = $request->validated();
        if ($validated['indefinite'] == 'true') $validated['days'] = 99999;

        $bannedUntilTime = Carbon::now()->addDays($validated['days']);
        $user->banned_until = $bannedUntilTime;
        $user->save();

        BannedUser::create([
            'user_id' => $user->id,
            'reason' => $validated['reason'],
            'admin_id' => Auth::user()->id,
            'days' => $validated['days'],
            'banned_until' => $bannedUntilTime,
        ]);

        $reportedUsers = ReportedUserResource::collection(
            User::get()->filter(function ($user) {
                return $user->isReported();
            })
        );
        $bannedUsers = BannedUserResource::collection(BannedUser::get());

        return new JsonResponse(
            ['message' => ['success' => 'User banned until '. $bannedUntilTime],
            'reported_users' => $reportedUsers,
            'banned_users' => $bannedUsers],
            Response::HTTP_OK);
    }

    /**
     * Gets all banned users
     *
     * @return JsonResponse with BannedUserResource collection
     */
    public function getBannedUsers() {
        return new JsonResponse(['banned_users' => BannedUserResource::collection(BannedUser::get())]);
    }

    /**
     * Edits a user ban, keeping a log of events and unbans a user if applicable.
     *
     * @param BannedUser $bannedUser
     * @param EditUserBanRequest $request
     * @return JsonResponse with BannedUserResource collection
     */
    public function editUserBan(BannedUser $bannedUser, EditUserBanRequest $request) {
        $validated = $request->validated();
        if ($validated['end_ban']) {
            $newDate = Carbon::now();
            $user = $bannedUser->user;
            $user->banned_until = $newDate;
            $user->save();
            $bannedUser->early_release = $newDate;
            Notification::create(['user_id' => $user->id,
                'title' => 'Your suspension has been lifted.',
                'text' => Auth::user()->username . 
                    ' has ended your suspension. Reason given: ' . $request['comment'] .
                    ' You were originally banned for: ' . $bannedUser->reason]);
        } else {
            $newDate = $bannedUser->created_at->addDays($validated['days']);
            $user = $bannedUser->user;
            $user->banned_until = $newDate;
            $bannedUser->banned_until = $newDate;
        }
        $bannedUser->ban_edit_comment = $bannedUser->ban_edit_comment.$validated['comment'].' | ';
        $bannedUser->ban_edit_log = $bannedUser->ban_edit_log.$validated['log'].' | ';
        $bannedUser->save();
        
        return new JsonResponse(['banned_users' => BannedUserResource::collection(BannedUser::get())]);
    }

    /**
     * Fetches all existing feedback and returns it in a Resource collection
     * @return JsonResponse with FeedbackResource collection
     */
    public function getFeedback() {
        return new JsonResponse(['feedback' => FeedbackResource::collection(Feedback::get())]);
    }
            
    /**
     * Toggles the feedback's archive column. True to false and vice versa. Returns the updated collection.
     * @return JsonResponse with string and FeedbackResource collection
     */
    public function toggleArchiveFeedback(Feedback $feedback) {
        $feedback->archived = !$feedback->archived;
        $feedback->save();
        return new JsonResponse([
            'message' => ['success' => $feedback->archived ? 'Feedback archived': 'Feedback unarchived'],
            'feedback' => FeedbackResource::collection(Feedback::get())], 
            Response::HTTP_OK);
    }   
}
