<?php

namespace App\Http\Controllers;

use App\Helpers\ActionTrackingHandler;
use App\Helpers\NotificationHandler;
use App\Models\Group;
use App\Models\User;
use App\Models\GroupApplication;
use App\Http\Requests\StoreGroupRequest;
use App\Http\Requests\UpdateGroupsRequest;
use App\Http\Requests\RemoveUserFromGroupRequest;
use App\Http\Requests\SendGroupInviteRequest;
use App\Http\Resources\GroupApplicationResource;
use App\Http\Resources\GroupPageResource;
use App\Http\Resources\GroupResource;
use App\Http\Resources\MyGroupResource;
use App\Models\GroupInvite;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

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
        $myGroups = MyGroupResource::collection(Auth::user()->groups);
        $allGroups = GroupResource::collection(Group::where('is_public', true)->get());
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

        return new JsonResponse(['message' => ['success' => "Your group \"{$validated['name']}\" has been created."]], Response::HTTP_OK);
    }

    public function destroy(Request $request, Group $group): JsonResponse
    {
        if (!$group->isAdminById(Auth::user()->id))
            return new JsonResponse(['message' => "You are not an admin of the group you are trying to delete."], Response::HTTP_BAD_REQUEST);
        $group->users()->detach();
        $group->applications()->detach();
        $group->delete();
        ActionTrackingHandler::handleAction($request, 'DELETE_GROUP', 'Deleted group ' . $group->name);
        return new JsonResponse(['message' => ['success' => "Your group \"{$group->name}\" has been deleted."]], Response::HTTP_OK);
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
            return new JsonResponse(['message' => "This group is not public."], Response::HTTP_BAD_REQUEST);
        if ($group->require_application)
            return new JsonResponse(['message' => "This group needs an application to join."], Response::HTTP_BAD_REQUEST);
        $user = Auth::user();
        $users = $group->users();
        if ($users->find($user))
            return new JsonResponse(['message' => "You are already a member of this group."], Response::HTTP_BAD_REQUEST);
        $users->attach($user);

        ActionTrackingHandler::handleAction($request, 'JOIN_GROUP', $user->username . ' joined group ' . $group->name);
        return new JsonResponse(
            [
                'message' => ['success' => "You successfully joined the group \"{$group->name}\"."],
                'group' => new GroupPageResource($group->fresh())
            ],
            Response::HTTP_OK
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
            return new JsonResponse(['message' => "This group is not public."], Response::HTTP_BAD_REQUEST);
        if (!$group->require_application)
            return new JsonResponse(['message' => "This group does not require applications to join."], Response::HTTP_BAD_REQUEST);
        $user = Auth::user();
        if ($group->users()->find($user))
            return new JsonResponse(['message' => "You are already a member of this group."], Response::HTTP_BAD_REQUEST);
        $applications = $group->applications();
        if ($applications->find($user))
            return new JsonResponse(['message' => "You already have a pending application for this group."], Response::HTTP_BAD_REQUEST);
        $applications->attach($user);
        Notification::create([
            'user_id' => $group->getAdmin()->id,
            'title' => "New group application to {$group->name}.",
            'text' => "{$user->username} has applied to your group {$group->name}. Head to the details of {$group->name} and click on \"Manage Applications\" to accept or reject the application.",
        ]);

        ActionTrackingHandler::handleAction($request, 'GROUP_APPLICATION', "{$user->username} applied to group {$group->name}");
        return new JsonResponse(
            [
                'message' => ['success' => "You successfully applied to the group \"{$group->name}\"."],
                'group' => new GroupPageResource($group->fresh())
            ],
            Response::HTTP_OK
        );
    }

    public function acceptGroupApplication(Request $request, $application_id): JsonResponse
    {
        $user = User::find(GroupApplication::find($application_id)->user_id);
        $group = Group::find(GroupApplication::find($application_id)->group_id);
        if (!$group->isAdminById(Auth::user()->id))
            return new JsonResponse(['message' => "You are not an administrator of this group."], Response::HTTP_BAD_REQUEST);
        $group->applications()->detach($user);
        $group->users()->attach($user);
        Notification::create([
            'user_id' => $user->id,
            'title' => "Your application to {$group->name} has been accepted.",
            'text' => "Your application to {$group->name} has been accepted. You can now see it under Social > Groups > My Groups.",
        ]);

        $admin = Auth::user();
        ActionTrackingHandler::handleAction($request, 'ACCEPT_GROUP_APPLICATION', "{$admin->username} accepted {$user->username}'s group application into {$group->name}.");
        return new JsonResponse(
            [
                'message' => ['success' => "You successfully accepted {$user->username}'s application."],
                'group' => new GroupPageResource($group->fresh())
            ],
            Response::HTTP_OK
        );
    }

    public function rejectGroupApplication(Request $request, $application_id): JsonResponse
    {
        $user = User::find(GroupApplication::find($application_id)->user_id);
        $group = Group::find(GroupApplication::find($application_id)->group_id);
        if (!$group->isAdminById(Auth::user()->id))
            return new JsonResponse(['message' => "You are not an administrator of this group."], Response::HTTP_BAD_REQUEST);
        $group->applications()->detach($user);

        $admin = Auth::user();
        ActionTrackingHandler::handleAction($request, 'REJECT_GROUP_APPLICATION', "{$admin->username} rejected {$user->username}'s group application into {$group->name}.");
        return new JsonResponse(
            [
                'message' => ['success' => "You successfully rejected {$user->username}'s application."],
                'group' => new GroupPageResource($group->fresh())
            ],
            Response::HTTP_OK
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
            return new JsonResponse(['message' => "You are not a member of the group you are trying to leave."], Response::HTTP_BAD_REQUEST);
        if ($users->count() == 1)
            return new JsonResponse(['message' => "You can't leave a group you are the only member of, please delete instead."], Response::HTTP_BAD_REQUEST);
        if ($group->isAdminById($user->id))
            return new JsonResponse(['message' => "You cannot leave a group where you are an admin."], Response::HTTP_BAD_REQUEST);
        $users->detach($user);
        ActionTrackingHandler::handleAction($request, 'LEAVE_GROUP', $user->username . ' left group ' . $group->name);
        return new JsonResponse(
            [
                'message' => ['success' => "You have successfully left the group \"{$group->name}\"."],
                'group' => new GroupPageResource($group->fresh())
            ],
            Response::HTTP_OK
        );
    }

    public function update(Group $group, UpdateGroupsRequest $request)
    {
        if (!$group->isAdminById(Auth::user()->id))
            return new JsonResponse(['message' => "You are not an admin of the group you are trying to update."], Response::HTTP_BAD_REQUEST);
        $validated = $request->validated();
        $group->update($validated);
        $myGroups = MyGroupResource::collection(Auth::user()->groups);
        ActionTrackingHandler::handleAction($request, 'UPDATE_GROUP', $group->name . ' updated.');
        return new JsonResponse(
            [
                'message' => ['success' => ['You have updated the group.']],
                'groups' => ['my' => $myGroups, 'current' => new GroupPageResource($group->fresh())]
            ],
            Response::HTTP_OK
        );
    }

    public function removeUserFromGroup(Group $group, RemoveUserFromGroupRequest $request)
    {
        if (!$group->isAdminById(Auth::user()->id))
            return new JsonResponse(['message' => "You are not an admin of the group you are trying to manage."], Response::HTTP_BAD_REQUEST);
        if (!$group->hasMember($request['id']))
            return new JsonResponse(['message' => "This user is not a member of this group"], Response::HTTP_BAD_REQUEST);
        $group->removeMemberFromGroup($request['id']);
        $myGroups = MyGroupResource::collection(Auth::user()->groups);
        ActionTrackingHandler::handleAction($request, 'GROUP_USER_KICKED', $group->name . ' kicked user id ' . $request['id']);
        return new JsonResponse(
            [
                'message' => ['success' => ['You have updated the group.']],
                'group' => new GroupPageResource($group->fresh())
            ],
            Response::HTTP_OK
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
        if ($group->getAdmin()->id !== Auth::user()->id) return new JsonResponse(['message' => 'You are not the admin of this group.'], Response::HTTP_BAD_REQUEST);
        $validated = $request->validated();
        $groupInvite = GroupInvite::create($validated);
        NotificationHandler::createFromGroupInvite(
            $request['user_id'],
            "You have a new group invite.",
            "You have been invited to join the group $group->name.",
            $groupInvite
        );
        //Update the group and send it back updated.
        return new JsonResponse([
            'message' => ['success' => ['You have invited this user.']],
            'group' => new GroupPageResource($group->fresh())
        ], Response::HTTP_OK);
    }

    public function acceptGroupInvite(GroupInvite $invite, Request $request)
    {
        /** @var User */
        $user = Auth::user();
        if ($user->id !== $invite->user_id) return new JsonResponse(['message' => 'This is not your invitation.'],  Response::HTTP_BAD_REQUEST);
        if ($invite->group->hasMember($user->id)) return new JsonResponse(['message' => 'You are already a member of this group.'], Response::HTTP_BAD_REQUEST);
        $invite->group->users()->attach($user);
        $invite->delete();
        ActionTrackingHandler::handleAction($request, 'GROUP_INVITE_ACCEPTED', 'User id ' . $invite->user_id . ' accepted invite to group ' . $invite->group->name);
        return new JsonResponse(['message' => ['success' => ['You are now a member of ' . $invite->group->name]]], Response::HTTP_OK);
    }

    public function rejectGroupInvite(GroupInvite $invite, Request $request)
    {
        /** @var User */
        $user = Auth::user();
        if ($user->id !== $invite->user_id) return new JsonResponse(['message' => 'This is not your invitation.'],  Response::HTTP_BAD_REQUEST);
        if ($invite->group->hasMember($user->id)) return new JsonResponse(['message' => 'You are already a member of this group.'], Response::HTTP_BAD_REQUEST);
        $invite->delete();
        //Test if the invite gets deleted
        ActionTrackingHandler::handleAction($request, 'GROUP_INVITE_REJECTED', 'User id ' . $invite->user_id . ' rejected invite to group ' . $invite->group->name);
        return new JsonResponse(['message' => ['success' => ['You have rejected the invitation.']]], Response::HTTP_OK);
    }
}
