<?php

namespace App\Http\Controllers;

use App\Exceptions\GroupNotFoundException;
use App\Helpers\ActionTrackingHandler;
use App\Helpers\NotificationHandler;
use App\Helpers\ResponseWrapper;
use App\Models\Group;
use App\Models\User;
use App\Models\GroupApplication;
use App\Http\Requests\StoreGroupRequest;
use App\Http\Requests\UpdateGroupsRequest;
use App\Http\Requests\RemoveUserFromGroupRequest;
use App\Http\Requests\SendGroupInviteRequest;
use App\Http\Requests\SuspendUserFromGroupRequest;
use App\Http\Resources\BlockedUserFromGroupResource;
use App\Http\Resources\GroupApplicationResource;
use App\Http\Resources\GroupPageResource;
use App\Http\Resources\GroupResource;
use App\Http\Resources\MyGroupResource;
use App\Models\GroupInvite;
use App\Models\Notification;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Throwable;

class GroupsController extends Controller
{
    // public function show($argument){
    //     if ($argument == 'all')
    //         return GroupResource::collection(Group::where('is_public', true)->get());
    //     if ($argument == 'my')
    //         return MyGroupResource::collection(Auth::user()->groups);
    //     if ($argument == 'dashboard') {
    //         $myGroups = MyGroupResource::collection(Auth::user()->groups);
    //         $allGroups = GroupResource::collection(Group::where('is_public', true)->get());
    //         return new JsonResponse(['groups' => ['my' => $myGroups, 'all' => $allGroups]]);
    //     }
    //     return new JsonResponse(['message' => "Only 'all', 'my' and 'dashboard' are permitted."], Response::HTTP_BAD_REQUEST);
    // }

    public function show(Group $group)
    {
        return new JsonResponse(['group' => new GroupPageResource($group)]);
    }

    public function dashboard()
    {
        /** @var User */
        $user = Auth::user();
        $myGroups = MyGroupResource::collection($user->groups);
        $allGroups = GroupResource::collection(Group::where('is_public', true)->whereNotIn('id', $user->suspendedGroupIds())->get());
        return new JsonResponse(['groups' => ['my' => $myGroups, 'all' => $allGroups]]);
    }

    public function showApplications(Group $group)
    {
        $applications = GroupApplicationResource::collection(DB::table('group_applications')->where('group_id', $group->id)->get());
        return new JsonResponse(['applications' => $applications]);
    }

    public function store(StoreGroupRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $group = Group::create($validated);
        $group->users()->attach(Auth::user()->id, ['rank' => 'admin']);
        ActionTrackingHandler::handleAction($request, 'STORE_GROUP', 'Created group ' . $group->name);

        return ResponseWrapper::successResponse(__('messages.group.created', ['name' => $validated['name']]));
    }

    public function destroy(Request $request, Group $group): JsonResponse
    {
        $group->users()->detach();
        $group->applications()->detach();
        $group->delete();
        ActionTrackingHandler::handleAction($request, 'DELETE_GROUP', 'Deleted group ' . $group->name);
        return ResponseWrapper::successResponse(__('messages.group.created', ['name' => $group->name]));
    }

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
        $group->users()->attach($user);

        ActionTrackingHandler::handleAction($request, 'JOIN_GROUP', 'Joined group ' . $group->name);
        return ResponseWrapper::successResponse(
            __('messages.group.join_success', ['name' => $group->name]),
            ['group' => new GroupPageResource($group->fresh())]
        );
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
        $applications = $group->applications();
        if ($applications->find($user))
            return ResponseWrapper::errorResponse(__('messages.group.already_applied'));
        $applications->attach($user);
        Notification::create([
            'user_id' => $group->getAdmin()->id,
            'title' => __('messages.group.new_application_title', ['name' => $group->name]),
            'text' => __('messages.group.new_application_text', ['username' => $user->username, 'groupname' => $group->name]),
        ]);

        ActionTrackingHandler::handleAction($request, 'GROUP_APPLICATION', "User applied to group {$group->name}");
        return ResponseWrapper::successResponse(
            __('messages.group.successful_application', ['name' => $group->name]),
            ['group' => new GroupPageResource($group->fresh())]
        );
    }

    public function acceptGroupApplication(Request $request, Group $group, GroupApplication $application): JsonResponse
    {
        $user = User::find($application->user_id);
        $group->applications()->detach($user);
        $group->users()->attach($user);
        Notification::create([
            'user_id' => $user->id,
            'title' => __('messages.group.application_accepted_title', ['name' => $group->name]),
            'text' => __('messages.group.application_accepted_text', ['name' => $group->name]),
        ]);

        ActionTrackingHandler::handleAction($request, 'ACCEPT_GROUP_APPLICATION', "User accepted {$user->username}'s group application into {$group->name}.");
        return ResponseWrapper::successResponse(
            __('messages.group.accepted_application', ['username' => $user->username]),
            ['group' => new GroupPageResource($group->fresh())]
        );
    }

    public function rejectGroupApplication(Request $request, Group $group, GroupApplication $application): JsonResponse
    {
        $user = User::find($application->user_id);
        $group->applications()->detach($user);

        ActionTrackingHandler::handleAction($request, 'REJECT_GROUP_APPLICATION', "User rejected {$user->username}'s group application into {$group->name}.");
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
        $group->applications()->detach($user);
        $group->suspendedUsers()->attach($user);
        $username = $user->username;
        ActionTrackingHandler::handleAction($request, 'SUSPEND_GROUP_APPLICATION', $group->name . ' suspended user id ' . $user);
        return ResponseWrapper::successResponse(
            __('messages.group.rejected_and_suspended', ['username' => $username]),
            ['group' => new GroupPageResource($group->fresh())]
        );
    }

    /**
     * Fetches all suspended users
     *
     * @param Group $group
     * @return JsonResponse with blocked users
     */
    public function getBlockedUsers(Group $group) {
        return new JsonResponse(['blockedUsers' => BlockedUserFromGroupResource::collection($group->suspendedUsers)]);
    }

    public function unblockUserFromGroup(Group $group, Request $request) {
        if (!$group->isAdminById(Auth::user()->id)) 
            return ResponseWrapper::errorResponse(__('messages.group.not_admin'));
        $user = User::find($request->userId);
        if (!$group->suspendedUsers()->where('user_id', $request->userId)->exists()) 
            return ResponseWrapper::errorResponse(__('messages.group.user_not_blocked'));
        $group->suspendedUsers()->detach($user);
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
        ActionTrackingHandler::handleAction($request, 'LEAVE_GROUP', 'User left group ' . $group->name);
        return ResponseWrapper::successResponse(
            __('messages.group.leave.admin', ['name' => $group->name]),
            ['group' => new GroupPageResource($group->fresh())]
        );
    }

    public function update(Group $group, UpdateGroupsRequest $request)
    {
        $validated = $request->validated();
        $group->update($validated);
        $myGroups = MyGroupResource::collection(Auth::user()->groups);
        ActionTrackingHandler::handleAction($request, 'UPDATE_GROUP', $group->name . ' updated.');
        return ResponseWrapper::successResponse(
            __('messages.group.updated'),
            ['groups' => ['my' => $myGroups, 'current' => new GroupPageResource($group->fresh())]]
        );
    }

    public function removeUserFromGroup(Group $group, RemoveUserFromGroupRequest $request)
    {
        if (!$group->hasMember($request['id']))
            return ResponseWrapper::errorResponse("This user is not a member of this group");
        $group->removeMemberFromGroup($request['id']);
        ActionTrackingHandler::handleAction($request, 'GROUP_USER_KICKED', $group->name . ' kicked user id ' . $request['id']);
        return ResponseWrapper::successResponse(
            __('messages.group.updated'),
            ['group' => new GroupPageResource($group->fresh())]
        );
    }

    public function suspendUserFromGroup(Group $group, SuspendUserFromGroupRequest $request)
    {
        $validated = $request->validated();
        $user = $validated['id'];
        $group->users()->detach($user);
        $group->suspendedUsers()->attach($user);
        $username = User::find($user)->username;
        ActionTrackingHandler::handleAction($request, 'GROUP_USER_SUSPENDED', $group->name . ' suspended user id ' . $user);
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
        return ResponseWrapper::successResponse(
            __('messages.group.invite.sent'),
            ['group' => new GroupPageResource($group->fresh())]
        );
    }

    public function acceptGroupInvite(Group $group, int $invite, Request $request)
    {
        $groupInvite = $this->getGroupInviteOrFail($invite);
        /** @var User */
        $user = Auth::user();
        if ($user->id !== $groupInvite->user_id)
            return ResponseWrapper::errorResponse(__('messages.group.invite.not_yours'));
        $group->users()->attach($user);
        $groupInvite->delete();
        ActionTrackingHandler::handleAction($request, 'GROUP_INVITE_ACCEPTED', 'User accepted invite to group ' . $group->name);
        return ResponseWrapper::successResponse(__('messages.group.join_success', ['name' => $groupInvite->group->name]));
    }

    public function rejectGroupInvite(Group $group, int $invite, Request $request)
    {
        $groupInvite = $this->getGroupInviteOrFail($invite);
        /** @var User */
        $user = Auth::user();
        if ($user->id !== $groupInvite->user_id)
            return ResponseWrapper::errorResponse(__('messages.group.invite.not_yours'));
        $groupInvite->delete();
        ActionTrackingHandler::handleAction($request, 'GROUP_INVITE_REJECTED', 'User rejected invite to group ' . $group->name);
        return ResponseWrapper::successResponse(__('messages.group.invite.rejected'));
    }

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
