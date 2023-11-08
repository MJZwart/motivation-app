<?php

namespace App\Http\Controllers;

use App\Events\NewNotification;
use App\Exceptions\GroupNotFoundException;
use App\Helpers\ActionTrackingHandler;
use App\Helpers\GroupRoleHandler;
use App\Helpers\NotificationHandler;
use App\Helpers\ResponseWrapper;
use App\Helpers\TimelineHandler;
use App\Models\Group;
use App\Models\User;
use App\Models\GroupApplication;
use App\Http\Requests\RemoveUserFromGroupRequest;
use App\Http\Requests\SendGroupInviteRequest;
use App\Http\Requests\SuspendUserFromGroupRequest;
use App\Http\Resources\BlockedUserFromGroupResource;
use App\Http\Resources\GroupApplicationResource;
use App\Http\Resources\GroupPageResource;
use App\Models\GroupInvite;
use App\Models\GroupUser;
use App\Models\Notification;
use Illuminate\Broadcasting\BroadcastException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Throwable;

class GroupUserController extends Controller
{
    /**
     * If the group is public and does not require application, instantly joins the group.
     * Returns a success message and the group with the updated membership.
     *
     * @param Request $request
     * @param Group $group
     * @return JsonResponse
     */
    public function join(Request $request, Group $group): JsonResponse
    {
        if ($group->require_application)
            return ResponseWrapper::errorResponse(__('messages.group.needs_application'));
        $user = Auth::user();
        $group->users()->attach($user, ['rank' => GroupRoleHandler::getMemberRank($group->id)->id]);

        TimelineHandler::addGroupJoiningToTimeline($group, $user->id);
        ActionTrackingHandler::registerAction($request, 'JOIN_GROUP', 'Joined group ' . $group->name);
        return ResponseWrapper::successResponse(
            __('messages.group.join_success', ['name' => $group->name]),
            ['group' => new GroupPageResource($group->fresh())]
        );
    }

    /**
     * Show all active applications to a group
     *
     * @param Group $group
     * @return JsonResponse
     */
    public function showApplications(Group $group)
    {
        $applications = GroupApplicationResource::collection($group->applications);
        return new JsonResponse(['applications' => $applications]);
    }

    /**
     * If group is public and requires application, the user is not already a member and does not have a pending application,
     * it sends an application to the group. This automatically sends a notification to the group admin. Returns the group.
     *
     * @param Request $request
     * @param Group $group
     * @return JsonResponse
     */
    public function apply(Request $request, Group $group): JsonResponse
    {
        if (!$group->require_application)
            return ResponseWrapper::errorResponse(__('messages.group.no_application'));
        $user = Auth::user();
        if (GroupApplication::where('group_id', $group->id)->where('user_id', $user->id)->exists())
            return ResponseWrapper::errorResponse(__('messages.group.already_applied'));
        GroupApplication::newApplication($group->id, $user->id);
        Notification::create([
            'user_id' => $group->getAdmin()->user_id,
            'title' => __('messages.group.new_application_title', ['name' => $group->name]),
            'text' => __('messages.group.new_application_text', ['username' => $user->username, 'groupname' => $group->name]),
        ]);

        try {
            NewNotification::broadcast($group->getAdmin()->user_id);
        } catch (BroadcastException $e) {
            error_log('Error broadcasting message: ' . $e->getMessage());
        }

        ActionTrackingHandler::registerAction($request, 'GROUP_APPLICATION', "User applied to group {$group->name}");
        return ResponseWrapper::successResponse(
            __('messages.group.successful_application', ['name' => $group->name]),
            ['group' => new GroupPageResource($group->fresh())]
        );
    }

    /**
     * Accept a group application
     *
     * @param Request $request
     * @param Group $group
     * @param GroupApplication $application
     * @return JsonResponse
     */
    public function acceptGroupApplication(Request $request, Group $group, GroupApplication $application): JsonResponse
    {
        $user = User::find($application->user_id);
        $application->delete();
        $group->users()->attach($user, ['rank' => GroupRoleHandler::getMemberRank($group->id)->id]);
        Notification::create([
            'user_id' => $user->id,
            'title' => __('messages.group.application_accepted_title', ['name' => $group->name]),
            'text' => __('messages.group.application_accepted_text', ['name' => $group->name]),
        ]);

        try {
            NewNotification::broadcast($user->id);
        } catch (BroadcastException $e) {
            error_log('Error broadcasting message: ' . $e->getMessage());
        }

        TimelineHandler::addGroupJoiningToTimeline($group, $user->id);
        ActionTrackingHandler::registerAction($request, 'ACCEPT_GROUP_APPLICATION', "User accepted {$user->username}'s group application into {$group->name}.");
        return ResponseWrapper::successResponse(
            __('messages.group.accepted_application', ['username' => $user->username]),
            ['group' => new GroupPageResource($group->fresh())]
        );
    }

    /**
     * Reject a group application
     *
     * @param Request $request
     * @param Group $group
     * @param GroupApplication $application
     * @return JsonResponse
     */
    public function rejectGroupApplication(Request $request, Group $group, GroupApplication $application): JsonResponse
    {
        $user = User::find($application->user_id);
        $application->delete();

        ActionTrackingHandler::registerAction($request, 'REJECT_GROUP_APPLICATION', "User rejected {$user->username}'s group application into {$group->name}.");
        return ResponseWrapper::successResponse(
            __('messages.group.rejected_application', ['username' => $user->username]),
            ['group' => new GroupPageResource($group->fresh())]
        );
    }
    /**
     * Rejects the application and bans the user from sending another request
     *
     * @param Request $request
     * @param GroupApplication $application
     * @return JsonResponse
     */
    public function suspendGroupApplication(Request $request, Group $group, GroupApplication $application): JsonResponse
    {
        $user = User::find($application->user_id);
        $application->delete();
        $group->suspendedUsers()->attach($user);
        $username = $user->username;
        ActionTrackingHandler::registerAction($request, 'SUSPEND_GROUP_APPLICATION', $group->name . ' suspended user id ' . $user);
        return ResponseWrapper::successResponse(
            __('messages.group.rejected_and_suspended', ['username' => $username]),
            ['group' => new GroupPageResource($group->fresh())]
        );
    }

    /**
     * Managing blocked users
     */

    /**
     * Fetches all suspended users
     *
     * @param Group $group
     * @return JsonResponse with blocked users
     */
    public function getBlockedUsers(Group $group)
    {
        return new JsonResponse(['blockedUsers' => BlockedUserFromGroupResource::collection($group->suspendedUsers)]);
    }

    /**
     * Lift the suspension from the group for the user
     *
     * @param Group $group
     * @param Request $request
     * @return void
     */
    public function unblockUserFromGroup(Group $group, Request $request)
    {
        if (!$group->isAdminById(Auth::user()->id))
            return ResponseWrapper::errorResponse(__('messages.group.not_admin'));
        $user = User::find($request->userId);
        if (!$group->suspendedUsers()->where('user_id', $request->userId)->exists())
            return ResponseWrapper::errorResponse(__('messages.group.user_not_blocked'));
        $group->suspendedUsers()->detach($user);
        ActionTrackingHandler::registerAction($request, 'GROUP_UNBLOCK', 'User ' . $user->username . ' unblocked from group ' . $group->name);
        return ResponseWrapper::successResponse(
            __('messages.group.unblocked', ['username' => $user->username]),
            ['blockedUsers' => BlockedUserFromGroupResource::collection($group->suspendedUsers)]
        );
    }

    /**
     * Assuming the user is a current member and is not admin, deletes the membership for the given group.
     * Returns the group with updated member list.
     *
     * @param Request $request
     * @param Group $group
     * @return JsonResponse
     */
    public function leave(Request $request, Group $group): JsonResponse
    {
        /** @var User */
        $user = Auth::user();
        $group->users()->detach($user);
        TimelineHandler::addGroupLeavingToTimeline($group, $user->id);
        ActionTrackingHandler::registerAction($request, 'LEAVE_GROUP', 'User left group ' . $group->name);
        return ResponseWrapper::successResponse(
            __('messages.group.leave.success', ['name' => $group->name]),
            ['group' => new GroupPageResource($group->fresh())]
        );
    }

    /**
     * Removes the user from the group
     *
     * @param Group $group
     * @param RemoveUserFromGroupRequest $request
     * @return JsonResponse
     */
    public function removeUserFromGroup(Group $group, RemoveUserFromGroupRequest $request)
    {
        if (!$group->hasMember($request['id']))
            return ResponseWrapper::errorResponse("This user is not a member of this group");
        $groupUser = GroupUser::find($request['id']);
        $group->removeMemberFromGroup($request['id']);
        TimelineHandler::addGroupLeavingToTimeline($group, $groupUser->user_id);
        ActionTrackingHandler::registerAction($request, 'GROUP_USER_KICKED', $group->name . ' kicked user id ' . $request['id']);
        return ResponseWrapper::successResponse(
            __('messages.group.updated'),
            ['group' => new GroupPageResource($group->fresh())]
        );
    }

    /**
     * Removes the user from the group and bans it from reapplying or joining
     *
     * @param Group $group
     * @param SuspendUserFromGroupRequest $request
     * @return JsonResponse
     */
    public function suspendUserFromGroup(Group $group, SuspendUserFromGroupRequest $request)
    {
        $validated = $request->validated();
        $userId = $validated['id'];
        $group->users()->detach($userId);
        $group->suspendedUsers()->attach($userId);
        $username = User::find($userId)->username;
        TimelineHandler::addGroupLeavingToTimeline($group, $userId);
        ActionTrackingHandler::registerAction($request, 'GROUP_USER_SUSPENDED', $group->name . ' suspended user id ' . $userId);
        return ResponseWrapper::successResponse(
            __('messages.group.removed_and_suspended', ['username' => $username]),
            ['group' => new GroupPageResource($group->fresh())]
        );
    }

    /**
     * 
     * Group invites
     * 
     */

    /**
     * Sends a group invite and automatically sends them a notification
     *
     * @param SendGroupInviteRequest $request
     * @param Group $group
     * @return JsonResponse
     */
    public function sendGroupInvite(SendGroupInviteRequest $request, Group $group)
    {
        if (GroupInvite::where('group_id', $group->id)->where('user_id', $request['user_id'])->exists())
            return ResponseWrapper::errorResponse('This user has already been invited.');
        if ($group->hasMember($request['user_id']))
            return ResponseWrapper::errorResponse('This user is already a member of this group');
        $validated = $request->validated();
        $groupInvite = GroupInvite::create($validated);
        NotificationHandler::createFromGroupInvite(
            $validated['user_id'],
            __('messages.group.invite.new_title'),
            __('messages.group.invite.new_text', ['name' => $group->name]),
            $groupInvite
        );
        ActionTrackingHandler::registerAction($request, 'GROUP_INVITE', 'Invited user to group ' . $group->name);
        return ResponseWrapper::successResponse(
            __('messages.group.invite.sent'),
            ['group' => new GroupPageResource($group->fresh())]
        );
    }

    /**
     * Accepts the sent invite and becomes a part of the group
     *
     * @param Group $group
     * @param integer $invite
     * @param Request $request
     * @return JsonResponse
     */
    public function acceptGroupInvite(Group $group, int $invite, Request $request)
    {
        $groupInvite = $this->getGroupInviteOrFail($invite);
        /** @var User */
        $user = Auth::user();
        if ($user->id !== $groupInvite->user_id)
            return ResponseWrapper::errorResponse(__('messages.group.invite.not_yours'));
        $group->users()->attach($user, ['rank' => GroupRoleHandler::getMemberRank($group->id)->id]);
        $groupInvite->delete();
        TimelineHandler::addGroupJoiningToTimeline($group, $user->id);
        ActionTrackingHandler::registerAction($request, 'GROUP_INVITE_ACCEPTED', 'User accepted invite to group ' . $group->name);
        return ResponseWrapper::successResponse(__('messages.group.join_success', ['name' => $groupInvite->group->name]));
    }

    /**
     * Rejects the group invite and removes the invitation
     *
     * @param Group $group
     * @param integer $invite
     * @param Request $request
     * @return JsonResponse
     */
    public function rejectGroupInvite(Group $group, int $invite, Request $request)
    {
        $groupInvite = $this->getGroupInviteOrFail($invite);
        /** @var User */
        $user = Auth::user();
        if ($user->id !== $groupInvite->user_id)
            return ResponseWrapper::errorResponse(__('messages.group.invite.not_yours'));
        $groupInvite->delete();
        ActionTrackingHandler::registerAction($request, 'GROUP_INVITE_REJECTED', 'User rejected invite to group ' . $group->name);
        return ResponseWrapper::successResponse(__('messages.group.invite.rejected'));
    }
    /**
     * Fetches the group invite or throws a GroupNotFoundException if it no longer exists
     *
     * @param integer $invite
     * @return GroupInvite or void
     */
    private function getGroupInviteOrFail(int $invite)
    {
        try {
            return GroupInvite::findOrFail($invite);
        } catch (Throwable $e) {
            if ($e instanceof ModelNotFoundException) {
                throw new GroupNotFoundException();
            }
        }
    }
}
