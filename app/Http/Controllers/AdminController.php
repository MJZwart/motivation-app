<?php

namespace App\Http\Controllers;

use App\Events\NewNotification;
use App\Helpers\ActionTrackingHandler;
use App\Helpers\ResponseWrapper;
use App\Http\Requests\SuspendUserRequest;
use App\Http\Requests\EditUserSuspensionRequest;
use App\Http\Requests\FetchActionsWithFilters;
use App\Http\Requests\StoreGroupExperienceRequest;
use App\Http\Requests\UpdateExperiencePointsRequest;
use App\Http\Requests\UpdateCharacterExpGainRequest;
use App\Http\Requests\UpdateVillageExpGainRequest;
use App\Http\Requests\StoreNewLevelRequest;
use App\Http\Resources\ActionTrackingResource;
use App\Models\BugReport;
use App\Models\User;
use App\Models\ReportedUser;
use App\Models\Conversation;
use App\Models\ExperiencePoint;
use App\Http\Resources\AdminConversationResource;
use App\Http\Resources\SuspendedUserResource;
use Carbon\Carbon;
use App\Http\Resources\FeedbackResource;
use App\Http\Resources\GroupMessageResource;
use App\Http\Resources\UserReportResource;
use App\Models\ActionTracking;
use App\Models\Feedback;
use App\Models\Group;
use App\Models\GroupExperiencePoint;
use App\Models\Notification;
use App\Models\SuspendedUser;
use Illuminate\Broadcasting\BroadcastException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function isAdmin()
    {
        //Empty function, check is already done by middleware
    }

    /*
    * *
    * * Balancing
    * *
    */

    public function getExperiencePoints(): JsonResponse
    {
        return ResponseWrapper::successResponse(null, ExperiencePoint::orderBy('level')->get());
    }

    public function getGroupExp(): JsonResponse
    {
        return ResponseWrapper::successResponse(null, GroupExperiencePoint::orderBy('level')->get());
    }

    public function storeGroupExp(StoreGroupExperienceRequest $request): JsonResponse
    {
        $validated = $request->validated();

        GroupExperiencePoint::truncate();
        GroupExperiencePoint::insert($validated);
        return ResponseWrapper::successResponse(__('messages.group_exp.updated'), GroupExperiencePoint::orderBy('level')->get());
    }

    public function getCharacterExpGain(): JsonResponse
    {
        return ResponseWrapper::successResponse(null, DB::table('character_exp_gain')->get()->toArray());
    }

    public function getVillageExpGain(): JsonResponse
    {
        return ResponseWrapper::successResponse(null, DB::table('village_exp_gain')->get()->toArray());
    }

    /**
     * Updates the entire experience points table and returns the entire Experience Point table
     */
    public function updateExperiencePoints(UpdateExperiencePointsRequest $request): JsonResponse
    {
        $validated = $request->validated();
        ExperiencePoint::upsert($validated, ['id'], ['experience_points']);
        $experiencePoints = ExperiencePoint::orderBy('level')->get();
        ActionTrackingHandler::handleAction($request, 'ADMIN', 'Updated experience points');
        return ResponseWrapper::successResponse(__('messages.exp.updated'), ['experience_points' => $experiencePoints]);
    }

    /**
     * Adds new level to the experience points table and returns the entire Experience Point table
     */
    public function addNewLevel(StoreNewLevelRequest $request): JsonResponse
    {
        $validated = $request->validated();
        ExperiencePoint::insert($validated);
        $experiencePoints = ExperiencePoint::orderBy('level')->get();
        ActionTrackingHandler::handleAction($request, 'ADMIN', 'Added new level to experience points');
        return ResponseWrapper::successResponse(__('messages.exp.added'), ['experience_points' => $experiencePoints]);
    }

    /**
     * Updates the balancing in character exp gain and returns the character exp gain table
     */
    public function updateCharacterExpGain(UpdateCharacterExpGainRequest $request): JsonResponse
    {
        $validated = $request->validated();
        DB::table('character_exp_gain')->upsert($validated, ['id'], ['strength', 'agility', 'endurance', 'intelligence', 'charisma', 'level', 'coins']);
        $characterExpGain = DB::table('character_exp_gain')->get()->toArray();
        ActionTrackingHandler::handleAction($request, 'ADMIN', 'Updated character experience gain');
        return ResponseWrapper::successResponse(__('messages.exp.char_updated'), ['balancing' => $characterExpGain]);
    }

    /**
     * Updates the balancing in village exp gain and returns the village exp gain table
     */
    public function updateVillageExpGain(UpdateVillageExpGainRequest $request): JsonResponse
    {
        $validated = $request->validated();
        DB::table('village_exp_gain')->upsert($validated, ['id'], ['economy', 'labour', 'craft', 'art', 'community', 'level', 'coins']);
        $villageExpGain = DB::table('village_exp_gain')->get()->toArray();
        ActionTrackingHandler::handleAction($request, 'ADMIN', 'Updated village experience gain');
        return ResponseWrapper::successResponse(__('messages.exp.vill_updated'), ['balancing' => $villageExpGain]);
    }

    /*
    * *
    * * Reported users / Suspended users
    * *
    */

    /** 
     * Gets all reported users, sorted by User (id). Each report has the user linked to the report.
     * Then parses it into a UserReportResource, with all relevant user information as its base (UserReportResourceCollection)
     * and all reports as an array in the resource (UserReportResource).
     * @return JsonResponse with all reported users
     */
    public function getReportedUsers(): JsonResponse
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
     * @return JsonResponse with all reported users
     */
    public function closeReport(ReportedUser $reportedUser)
    {
        $reportedUser->delete();
        return $this->getReportedUsers();
    }



    /**
     * Fetches the conversation by the given conversation ID. Only one is necessary, so we pick the first
     * @return AdminConversationResource with one conversation
     */
    public function getConversationById(String $id): AdminConversationResource
    {
        return new AdminConversationResource(Conversation::where('conversation_id', $id)->first());
    }

    /**
     * Suspends a user: Changes 'suspended_until' to current dateTime + the amount of days the user gets suspended.
     * Created a suspendedUser to document the suspension of the account, as well as deactivates the
     * user's account.
     * @return JsonResponse with all reported users
     */
    public function suspendUser(SuspendUserRequest $request, User $user): JsonResponse
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

        return ResponseWrapper::successResponse(__('messages.user.suspension.until', ['time' => $suspendedUntilTime]), ['reported_users' => $reportedUsers]);
    }

    /**
     * @return SuspendedUserResource with all suspended users
     */
    public function getsuspendedUsers()
    {
        return new JsonResponse(['suspended_users' => SuspendedUserResource::collection(SuspendedUser::get())]);
    }

    /**
     * Edits a user suspension, keeping a log of events and unsuspends a user if applicable.
     * @return SuspendedUserResource with all suspended users
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
                'text' => __(
                    'messages.user.suspension.ended_notification',
                    [
                        'admin' => Auth::user()->username,
                        'comment' => $request['suspension_edit_comment'],
                        'reason' => $suspendedUser->reason
                    ]
                )
            ]);

            try {
                NewNotification::broadcast($user->id);
            } catch (BroadcastException $e) {
                error_log('Error broadcasting message: ' . $e->getMessage());
            }
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

    /*
    * *
    * * Feedback
    * *
    */

    /**
     * @return FeedbackResource with all feedback
     */
    public function getFeedback()
    {
        return new JsonResponse(['feedback' => FeedbackResource::collection(Feedback::with('user')->get())]);
    }

    /**
     * Toggles the feedback's archive column. True to false and vice versa. Returns the updated collection.
     * @return FeedbackResource with all feedback
     */
    public function toggleArchiveFeedback(Feedback $feedback)
    {
        $feedback->archived = !$feedback->archived;
        $feedback->save();
        return ResponseWrapper::successResponse(
            $feedback->archived ? __('messages.feedback.archived') : __('messages.feedback.unarchived'),
            ['feedback' => FeedbackResource::collection(Feedback::with('user')->get())]
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
        $firstAction = ActionTracking::select('created_at')->first();
        return new JsonResponse([
            'types' => ActionTracking::select('action_type')->distinct()->get(),
            'users' => User::select('id', 'username')->get(),
            'minDate' => $firstAction !== null ? $firstAction->created_at : null,
        ]);
    }

    /**
     * @return ActionTrackingResourceCollection with all actions that fit within the given filters
     */
    public function getActionsWithFilters(FetchActionsWithFilters $request)
    {
        $validated = $request->validated();

        $actions = ActionTracking::when(!empty($validated['users']), function ($query) use ($validated) {
            $query->whereIn('user_id', $validated['users']);
        })->when(!empty($validated['type']), function ($query) use ($validated) {
            $query->whereIn('action_type', $validated['type']);
        })->when(!empty($validated['date']), function ($query) use ($validated) {
            $query->whereBetween('created_at', $validated['date']);
        })->with('user')->orderBy('created_at', 'desc')->get();

        return ActionTrackingResource::collection($actions);
    }

    /**
     * @return GroupMessageResourceCollection with all group messages in a range around a given date - for admin purpose
     */
    public function getGroupMessagesByDateRange(Request $request, Group $group)
    {
        $startDate = Carbon::parse($request['date'])->startOf('day');
        $endDate = Carbon::parse($request['date'])->endOf('day');
        $messages = $group->messages()->where('created_at', '>', $startDate)->where('created_at', '<', $endDate)->get();
        return ResponseWrapper::successResponse(null, ['messages' => GroupMessageResource::collection($messages)]);
    }
}
