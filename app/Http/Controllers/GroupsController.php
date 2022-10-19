<?php

namespace App\Http\Controllers;

use App\Exceptions\GroupNotAuthorizedException;
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
use App\Http\Requests\BanUserFromGroupRequest;
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
        $allGroups = GroupResource::collection(Group::where('is_public', true)->whereNotIn('id', $user->bannedGroupIds())->get());
        return new JsonResponse(['groups' => ['my' => $myGroups, 'all' => $allGroups]]);
    }

    public function showApplications(Group $group)
    {
        $this->isUserGroupAdmin($group);
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
        $this->isUserGroupAdmin($group);
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
        if (!$group->is_public)
            return ResponseWrapper::errorResponse(__('messages.group.not_public'));
        if ($group->require_application)
            return ResponseWrapper::errorResponse(__('messages.group.needs_application'));
        $user = Auth::user();
        if ($group->bannedUsers()->find($user))
            return ResponseWrapper::errorResponse(__('messages.group.banned'));
        $users = $group->users();
        if ($users->find($user))
            return ResponseWrapper::errorResponse(__('messages.group.already_member'));
        $users->attach($user);

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
        if (!$group->is_public)
            return ResponseWrapper::errorResponse(__('messages.group.not_public'));
        if (!$group->require_application)
            return ResponseWrapper::errorResponse(__('messages.group.no_application'));
        $user = Auth::user();
        if ($group->bannedUsers()->find($user))
            return ResponseWrapper::errorResponse(__('messages.group.banned'));
        if ($group->users()->find($user))
            return ResponseWrapper::errorResponse(__('messages.group.already_member'));
        $applications = $group->applications();
        if ($applications->find($user))
            return ResponseWrapper::errorResponse(__('messages.group.already_applied'));
        $applications->attach($user);
        Notification::create([
            'user_id' => $group->getAdmin()->id,
            'title' => __('messages.group.already_applied', ['name' => $group->name]),
            'text' => __('messages.group.already_applied', ['username' => $user->username, 'groupname' => $group->name]),
        ]);

        ActionTrackingHandler::handleAction($request, 'GROUP_APPLICATION', "User applied to group {$group->name}");
        return ResponseWrapper::successResponse(
            __('messages.group.successful_application', ['name' => $group->name]),
            ['group' => new GroupPageResource($group->fresh())]
        );
    }

    public function acceptGroupApplication(Request $request, GroupApplication $application): JsonResponse
    {
        $user = User::find($application->user_id);
        $group = Group::find($application->group_id);
        $this->isUserGroupAdmin($group);
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

    public function rejectGroupApplication(Request $request, GroupApplication $application): JsonResponse
    {
        $user = User::find($application->user_id);
        $group = Group::find($application->group_id);
        $this->isUserGroupAdmin($group);
        $group->applications()->detach($user);

        ActionTrackingHandler::handleAction($request, 'REJECT_GROUP_APPLICATION', "User rejected {$user->username}'s group application into {$group->name}.");
        return ResponseWrapper::successResponse(
            __('messages.group.rejected_application', ['username' => $user->username]),
            ['group' => new GroupPageResource($group->fresh())]
        );
    }

    public function banGroupApplication(Request $request, GroupApplication $application): JsonResponse
    {
        $group = Group::find($application->group_id);
        $user = User::find($application->user_id);
        $this->isUserGroupAdmin($group);
        $group->applications()->detach($user);
        $group->bannedUsers()->attach($user);
        $username = User::find($user)->username;
        ActionTrackingHandler::handleAction($request, 'BAN_GROUP_APPLICATION', $group->name . ' banned user id ' . $user);
        return ResponseWrapper::successResponse(
            __('messages.group.rejected_and_banned', ['username' => $username]),
            ['group' => new GroupPageResource($group->fresh())]
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
        $users = $group->users();
        if (!$user->groups()->find($group))
            return ResponseWrapper::errorResponse(__('messages.group.leave.not_member'));
        if ($users->count() == 1)
            return ResponseWrapper::errorResponse(__('messages.group.leave.only_member'));
        if ($group->isAdminById($user->id))
            return ResponseWrapper::errorResponse(__('messages.group.leave.admin'));
        $users->detach($user);
        ActionTrackingHandler::handleAction($request, 'LEAVE_GROUP', 'User left group ' . $group->name);
        return ResponseWrapper::successResponse(
            __('messages.group.leave.admin', ['name' => $group->name]),
            ['group' => new GroupPageResource($group->fresh())]
        );
    }

    public function update(Group $group, UpdateGroupsRequest $request)
    {
        $this->isUserGroupAdmin($group);
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
        $this->isUserGroupAdmin($group);
        if (!$group->hasMember($request['id']))
            return ResponseWrapper::errorResponse("This user is not a member of this group");
        $group->removeMemberFromGroup($request['id']);
        ActionTrackingHandler::handleAction($request, 'GROUP_USER_KICKED', $group->name . ' kicked user id ' . $request['id']);
        return ResponseWrapper::successResponse(
            __('messages.group.updated'),
            ['group' => new GroupPageResource($group->fresh())]
        );
    }

    public function banUserFromGroup(Group $group, BanUserFromGroupRequest $request)
    {
        $validated = $request->validated();
        $user = $validated['id'];
        $this->isUserGroupAdmin($group);
        $group->users()->detach($user);
        $group->bannedUsers()->attach($user);
        $username = User::find($user)->username;
        ActionTrackingHandler::handleAction($request, 'GROUP_USER_BANNED', $group->name . ' banned user id ' . $user);
        return ResponseWrapper::successResponse(
            __('messages.group.removed_and_banned', ['username' => $username]),
            ['group' => new GroupPageResource($group->fresh())]
        );
    }

    /**
     * 
     * Group invites
     * 
     */

    public function sendGroupInvite(SendGroupInviteRequest $request)
    {
        $group = Group::find($request['group_id']);
        $this->isUserGroupAdmin($group);
        if (GroupInvite::where('group_id', $group->id)->where('user_id', $request['user_id'])->exists())
            return ResponseWrapper::errorResponse('This user has already been invited.');
        if ($group->hasMember($request['user_id']))
            return ResponseWrapper::errorResponse('This user is already a member of this group');
        $validated = $request->validated();
        $groupInvite = GroupInvite::create($validated);
        NotificationHandler::createFromGroupInvite(
            $validated['user_id'],
            __('messages.group.invite.new_title'),
            __('messages.group.invite.new_title', ['name' => $group->name]),
            $groupInvite
        );
        return ResponseWrapper::successResponse(
            __('messages.group.invite.sent'),
            ['group' => new GroupPageResource($group->fresh())]
        );
    }

    public function acceptGroupInvite(int $invite, Request $request)
    {
        $groupInvite = $this->getGroupInviteOrFail($invite);
        /** @var User */
        $user = Auth::user();
        if ($user->id !== $groupInvite->user_id)
            return ResponseWrapper::errorResponse(__('messages.group.invite.not_yours'));
        if ($groupInvite->group->hasMember($user->id))
            return ResponseWrapper::errorResponse(__('messages.group.already_member'));
        $groupInvite->group->users()->attach($user);
        $groupInvite->delete();
        ActionTrackingHandler::handleAction($request, 'GROUP_INVITE_ACCEPTED', 'User accepted invite to group ' . $groupInvite->group->name);
        return ResponseWrapper::successResponse(__('messages.group.join_success'));
    }

    public function rejectGroupInvite(int $invite, Request $request)
    {
        $groupInvite = $this->getGroupInviteOrFail($invite);
        /** @var User */
        $user = Auth::user();
        if ($user->id !== $groupInvite->user_id)
            return ResponseWrapper::errorResponse(__('messages.group.invite.not_yours'));
        if ($groupInvite->group->hasMember($user->id))
            return ResponseWrapper::errorResponse(__('messages.group.already_member'));
        $groupInvite->delete();
        ActionTrackingHandler::handleAction($request, 'GROUP_INVITE_REJECTED', 'User rejected invite to group ' . $groupInvite->group->name);
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

    private function isUserGroupAdmin(Group $group)
    {
        if (!$group->isAdminById(Auth::user()->id))
            throw new GroupNotAuthorizedException();
    }
}
