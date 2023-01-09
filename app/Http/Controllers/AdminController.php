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
use App\Http\Resources\GroupMessageResource;
use App\Http\Resources\UserReportResource;
use App\Models\ActionTracking;
use App\Models\Feedback;
use App\Models\Group;
use App\Models\Notification;
use App\Models\SuspendedUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
/**
 * Balancing
 */

    /**
     * Fetches the experience points table
     *
     * @return JsonResponse
     */
    public function getExperiencePoints() 
    {
        return ResponseWrapper::successResponse(null, ExperiencePoint::orderBy('level')->get());
    }
    /**
     * Fetches the exp-gain tables for characters
     *
     * @return JsonResponse
     */
    public function getCharacterExpGain() 
    {
        return ResponseWrapper::successResponse(null, DB::table('character_exp_gain')->get()->toArray());
    }
    /**
     * Fetches the exp-gain tables for villages
     *
     * @return JsonResponse
     */
    public function getVillageExpGain() 
    {
        return ResponseWrapper::successResponse(null, DB::table('village_exp_gain')->get()->toArray());
    }
    /**
     * Updates the experience points table
     *
     * @param UpdateExperiencePointsRequest $request
     * @return JsonResponse
     */
    public function updateExperiencePoints(UpdateExperiencePointsRequest $request)
    {
        $validated = $request->validated();
        ExperiencePoint::upsert($validated, ['id'], ['experience_points']);
        $experiencePoints = ExperiencePoint::orderBy('level')->get();
        ActionTrackingHandler::handleAction($request, 'ADMIN', 'Updated experience points');
        return ResponseWrapper::successResponse(__('messages.exp.updated'), ['experience_points' => $experiencePoints]);
    }

    /**
     * Adds new level to the experience points table
     *
     * @param StoreNewLevelRequest $request
     * @return JsonResponse
     */
    public function addNewLevel(StoreNewLevelRequest $request)
    {
        $validated = $request->validated();
        ExperiencePoint::insert($validated);
        $experiencePoints = ExperiencePoint::orderBy('level')->get();
        ActionTrackingHandler::handleAction($request, 'ADMIN', 'Added new level to experience points');
        return ResponseWrapper::successResponse(__('messages.exp.added'), ['experience_points' => $experiencePoints]);
    }

    /**
     * Updates the balancing in character exp gain
     *
     * @param UpdateCharacterExpGainRequest $request
     * @return JsonResponse
     */
    public function updateCharacterExpGain(UpdateCharacterExpGainRequest $request)
    {
        $validated = $request->validated();
        DB::table('character_exp_gain')->upsert($validated, ['id'], ['strength', 'agility', 'endurance', 'intelligence', 'charisma', 'level', 'coins']);
        $characterExpGain = DB::table('character_exp_gain')->get()->toArray();
        ActionTrackingHandler::handleAction($request, 'ADMIN', 'Updated character experience gain');
        return ResponseWrapper::successResponse(__('messages.exp.char_updated'), ['balancing' => $characterExpGain]);
    }

    /**
     * Updates the balancing in village exp gain
     *
     * @param UpdateVillageExpGainRequest $request
     * @return JsonResponse
     */
    public function updateVillageExpGain(UpdateVillageExpGainRequest $request)
    {
        $validated = $request->validated();
        DB::table('village_exp_gain')->upsert($validated, ['id'], ['economy', 'labour', 'craft', 'art', 'community', 'level', 'coins']);
        $villageExpGain = DB::table('village_exp_gain')->get()->toArray();
        ActionTrackingHandler::handleAction($request, 'ADMIN', 'Updated village experience gain');
        return ResponseWrapper::successResponse(__('messages.exp.vill_updated'), ['balancing' => $villageExpGain]);
    }

/**
 * Reported users / Suspended users
 */

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



    /**
     * Fetches the conversation by the given conversation ID. Only one is necessary, so we pick the first
     *
     * @param String $id
     * @return ResourceCollection
     */
    public function getConversationById(String $id)
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

    /**
     * Gets all group messages in a range around a given date
     *
     * @param Request $request
     * @param Group $group
     * @return GroupMessageResourceCollection
     */
    public function getGroupMessagesByDateRange(Request $request, Group $group)
    {
        $startDate = Carbon::parse($request['date'])->startOf('day');
        $endDate = Carbon::parse($request['date'])->endOf('day');
        $messages = $group->messages()->where('created_at', '>', $startDate)->where('created_at', '<', $endDate)->get();
        return ResponseWrapper::successResponse(null, ['messages' => GroupMessageResource::collection($messages)]);
    }
}
