<?php

namespace App\Http\Controllers;

use App\Helpers\ActionTrackingHandler;
use App\Helpers\ResponseWrapper;
use App\Http\Requests\SuspendUserRequest;
use App\Http\Requests\EditUserSuspensionRequest;
use App\Http\Requests\FetchActionsWithFilters;
use App\Http\Requests\UpdateExperiencePointsRequest;
use App\Http\Requests\UpdateCharacterExpGainRequest;
use App\Http\Requests\UpdateVillageExpGainRequest;
use App\Http\Requests\StoreNewLevelRequest;
use App\Models\Achievement;
use App\Http\Resources\AchievementResource;
use App\Http\Resources\ActionTrackingResource;
use App\Models\BugReport;
use App\Models\User;
use App\Models\ReportedUser;
use App\Models\Conversation;
use App\Models\ExperiencePoint;
use App\Http\Resources\BugReportResource;
use App\Http\Resources\AdminConversationResource;
use App\Http\Resources\SuspendedUserResource;
use Carbon\Carbon;
use App\Http\Resources\FeedbackResource;
use App\Http\Resources\UserReportResource;
use App\Models\ActionTracking;
use App\Models\Feedback;
use App\Models\Notification;
use App\Models\SuspendedUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function getAdminDashboard()
    {
        $achievements = AchievementResource::collection(Achievement::get());
        $bugReports = BugReportResource::collection(BugReport::all());
        $experiencePoints = ExperiencePoint::get();
        $characterExpGain = DB::table('character_exp_gain')->get();
        $villageExpGain = DB::table('village_exp_gain')->get();
        $balancing = [
            'experience_points' => $experiencePoints,
            'character_exp_gain' => $characterExpGain, 
            'village_exp_gain' => $villageExpGain
        ];

        return new JsonResponse(
            [
                'achievements' => $achievements,
                'bugReports' => $bugReports, 
                'balancing' => $balancing
            ],
            Response::HTTP_OK
        );
    }

    /** 
     * Gets all reported users, sorted by User (id). Each report has the user linked to the report.
     * Then parses it into a UserReportResource, with all relevant user information as its base (UserReportResourceCollection)
     * and all reports as an array in the resource (UserReportResource).
     * @return JsonResponse with a UserReportResource collection
     */
    public function getReportedUsers()
    {
        $reportedUsersCollection = ReportedUser::orderBy('created_at', 'desc')->with('user.suspendedUser')->get()->groupBy('reported_user_id');
        $reportedUsers = [];
        foreach ($reportedUsersCollection as $userReport) {
            array_push($reportedUsers, UserReportResource::collection($userReport)->setUser($userReport[0]->user));
        }
        return new JsonResponse(['reportedUsers' => $reportedUsers], Response::HTTP_OK);
    }

    /**
     * Closes a ReportedUser, as in it trashes it. Also returns all reported users, sorted by User (id).
     * Each report has the user linked to the report. Then parses it into a UserReportResource,
     * with all relevant user information as its base (UserReportResourceCollection)
     * and all reports as an array in the resource (UserReportResource).
     */
    public function closeReport(ReportedUser $reportedUser)
    {
        $reportedUser->delete();
        return $this->getReportedUsers();
    }

    public function updateExeriencePoints(UpdateExperiencePointsRequest $request)
    {
        $validated = $request->validated();
        ExperiencePoint::upsert($validated, ['id'], ['experience_points']);
        $experiencePoints = ExperiencePoint::get();
        ActionTrackingHandler::handleAction($request, 'ADMIN', 'Updated experience points');
        return ResponseWrapper::successResponse(__('messages.exp.updated'), ['experience_points' => $experiencePoints]);
    }

    public function addNewLevel(StoreNewLevelRequest $request)
    {
        $validated = $request->validated();
        ExperiencePoint::insert($validated);
        $experiencePoints = ExperiencePoint::get();
        ActionTrackingHandler::handleAction($request, 'ADMIN', 'Added new level to experience points');
        return ResponseWrapper::successResponse(__('messages.exp.added'), ['experience_points' => $experiencePoints]);
    }

    public function updateCharacterExpGain(UpdateCharacterExpGainRequest $request)
    {
        $validated = $request->validated();
        DB::table('character_exp_gain')->upsert($validated, ['id'], ['strength', 'agility', 'endurance', 'intelligence', 'charisma', 'level', 'coins']);
        $characterExpGain = DB::table('character_exp_gain')->get();
        ActionTrackingHandler::handleAction($request, 'ADMIN', 'Updated character experience gain');
        return ResponseWrapper::successResponse(__('messages.exp.char_updated'), ['data' => $characterExpGain]);
    }

    public function updateVillageExpGain(UpdateVillageExpGainRequest $request)
    {
        $validated = $request->validated();
        DB::table('village_exp_gain')->upsert($validated, ['id'], ['economy', 'labour', 'craft', 'art', 'community', 'level', 'coins']);
        $villageExpGain = DB::table('village_exp_gain')->get();
        ActionTrackingHandler::handleAction($request, 'ADMIN', 'Updated village experience gain');
        return ResponseWrapper::successResponse(__('messages.exp.vill_updated'), ['data' => $villageExpGain]);
    }

    public function getConversationById($id)
    {
        return new AdminConversationResource(Conversation::where('conversation_id', $id)->first());
    }

    /**
     * Suspends a user: Changes 'suspended_until' to current dateTime + the amount of days the user gets suspended.
     * Created a suspendedUser to document the suspension of the account, as well as deactivates the
     * user's account.
     *
     * @param SuspendUserRequest $request
     * @param User $user
     * @return JsonResponse
     */
    public function suspendUser(SuspendUserRequest $request, User $user)
    {
        $validated = $request->validated();
        if ($validated['indefinite'] == 'true') $validated['days'] = 99999;

        $suspendedUntilTime = Carbon::now()->addDays($validated['days']);
        $user->suspended_until = $suspendedUntilTime;
        $user->save();

        SuspendedUser::create([
            'user_id' => $user->id,
            'reason' => $validated['reason'],
            'admin_id' => Auth::user()->id,
            'days' => $validated['days'],
            'suspended_until' => $suspendedUntilTime,
        ]);

        //delete all reports of the user if checkbox 'close all reports' is checked
        if ($validated['close_reports'] == 'true') {
            ReportedUser::where('reported_user_id', $user->id)->delete();
        }

        $reportedUsersCollection = ReportedUser::orderBy('created_at', 'desc')->get()->groupBy('reported_user_id');
        $reportedUsers = [];
        foreach ($reportedUsersCollection as $userReport) {
            array_push($reportedUsers, UserReportResource::collection($userReport)->setUser($userReport[0]->user));
        }
        $suspendedUsers = SuspendedUserResource::collection(SuspendedUser::get());

        return ResponseWrapper::successResponse(__('messages.user.suspension.until', ['time' => $suspendedUntilTime]), ['reported_users' => $reportedUsers, 'suspended_users' => $suspendedUsers]);
    }

    /**
     * Gets all suspended users
     *
     * @return JsonResponse with SuspendedUserResource collection
     */
    public function getsuspendedUsers()
    {
        return new JsonResponse(['suspended_users' => SuspendedUserResource::collection(SuspendedUser::get())]);
    }

    /**
     * Edits a user suspension, keeping a log of events and unsuspends a user if applicable.
     *
     * @param suspendedUser $suspendedUser
     * @param EditUserSuspensionRequest $request
     * @return JsonResponse with SuspendedUserResource collection
     */
    public function editUserSuspension(SuspendedUser $suspendedUser, EditUserSuspensionRequest $request)
    {
        $validated = $request->validated();
        $newDate = Carbon::now();
        if (array_key_exists('end_suspension', $validated) && $validated['end_suspension']) {
            $user = $suspendedUser->user;
            $user->suspended_until = $newDate;
            $user->save();
            $suspendedUser->early_release = $newDate;
            //NOTE This is currently the only place where suspension_edit_comment is used, it may be removed later
            Notification::create([
                'user_id' => $user->id,
                'title' => __('messages.user.suspension.ended_notification_title'),
                'text' => __('messages.user.suspension.ended_notification', 
                    ['admin' => Auth::user()->username,
                    'comment' => $request['suspension_edit_comment'],
                    'reason' => $suspendedUser->reason])
            ]);
        } else {
            $newDate = $suspendedUser->created_at->addDays($validated['days']);
            $user = $suspendedUser->user;
            $user->suspended_until = $newDate;
        }
        $suspendedUser->days = $request['days'];
        $suspendedUser->suspended_until = $newDate;
        $suspendedUser->suspension_edit_log = $validated['suspension_edit_log'];
        $suspendedUser->save();

        return new JsonResponse(['suspended_users' => SuspendedUserResource::collection(SuspendedUser::get())]);
    }

    /**
     * Fetches all existing feedback and returns it in a Resource collection
     * @return JsonResponse with FeedbackResource collection
     */
    public function getFeedback()
    {
        return new JsonResponse(['feedback' => FeedbackResource::collection(Feedback::get())]);
    }

    /**
     * Toggles the feedback's archive column. True to false and vice versa. Returns the updated collection.
     * @return JsonResponse with string and FeedbackResource collection
     */
    public function toggleArchiveFeedback(Feedback $feedback)
    {
        $feedback->archived = !$feedback->archived;
        $feedback->save();
        return ResponseWrapper::successResponse(
            $feedback->archived ? __('messages.feedback.archived') : __('messages.feedback.unarchived'),
            ['feedback' => FeedbackResource::collection(Feedback::get())]
        );
    }

    /**
     * Gets an overview of stats related to the website, relevant for admins
     */
    public function getOverview()
    {
        $yesterday = Carbon::now()->subDay();
        $lastWeek = Carbon::now()->subWeek();
        $totalUsers = User::count();
        $activeUsers = User::where('last_login', '>', $yesterday)->count();
        $newUsers = User::where('created_at', '>', $lastWeek)->count();
        $unarchivedFeedback = Feedback::where('archived', false)->count();
        $unresolvedBugs = BugReport::where('status', '!=', 3)->count();
        $newFeedback = Feedback::where('created_at', '>', $lastWeek)->count();
        $newBugs = BugReport::where('created_at', '>', $lastWeek)->count();
        $newUserReports = ReportedUser::where('created_at', '>', $lastWeek)->count();
        $overview = [
            'total-users' => $totalUsers,
            'new-users' => $newUsers,
            'active-users' => $activeUsers,
            'unarchived-feedback' => $unarchivedFeedback,
            'unresolved-bugs' => $unresolvedBugs,
            'new-feedback' => $newFeedback,
            'new-bugs' => $newBugs,
            'new-user-reports' => $newUserReports
        ];
        return new JsonResponse(['overview' => $overview]);
    }

    /**
     * Gets all applicable action tracking filters and returns them.
     */
    public function getActionFilters()
    {
        return new JsonResponse([
            'types' => ActionTracking::select('action_type')->distinct()->get(),
            'users' => User::select('id', 'username')->get(),
            'minDate' => ActionTracking::select('created_at')->first()->created_at,
        ]);
    }

    /**
     * Gets all actions tracked with given filters
     *
     * @param FetchActionsWithFilters $request
     * @return ActionTrackingResourceCollection
     */
    public function getActionsWithFilters(FetchActionsWithFilters $request) 
    {
        $validated = $request->validated();

        $actions = ActionTracking::when(!empty($validated['users']), function ($query) use ($validated){
            $query->whereIn('user_id', $validated['users']);
        })->when(!empty($validated['type']), function ($query) use ($validated){
            $query->whereIn('action_type', $validated['type']);
        })->when(!empty($validated['date']), function ($query) use ($validated){
            $query->whereBetween('created_at', $validated['date']);
        })->with('user')->orderBy('created_at', 'desc')->get();

        return ActionTrackingResource::collection($actions);
    }
}
