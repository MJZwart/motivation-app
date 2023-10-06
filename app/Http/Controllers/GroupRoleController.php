<?php

namespace App\Http\Controllers;

use App\Helpers\ActionTrackingHandler;
use App\Helpers\GroupRoleHandler;
use App\Helpers\ResponseWrapper;
use App\Http\Requests\UpdateGroupRoleNameRequest;
use App\Http\Requests\UpdateGroupRoles;
use App\Http\Resources\GroupPageResource;
use App\Http\Resources\GroupRoleResource;
use App\Models\Group;
use App\Models\GroupRole;
use App\Models\GroupUser;
use Illuminate\Http\Request;

class GroupRoleController extends Controller
{
    /**
     * Fetches all roles belonging to a group
     *
     * @param Group $group
     * @return void
     */
    public function getRoles(Group $group)
    {
        return GroupRoleResource::collection($group->roles->sortBy('position'));
    }

    /**
     * Updates the role name
     *
     * @param Group $group
     * @param GroupRole $role
     * @param UpdateGroupRoleNameRequest $request
     * @return JsonResponse
     */
    public function updateRoleName(Group $group, GroupRole $role, UpdateGroupRoleNameRequest $request)
    {
        $validated = $request->validated();
        $role->update(['name' => $validated['name']]);
        ActionTrackingHandler::registerAction($request, 'UPDATE_GROUP_ROLE', 'Updated group role name ' . $validated['name'] . ' in group ' . $group->name);
        return ResponseWrapper::successResponse(__('messages.group.role.updated'), ['roles' => GroupRoleResource::collection($group->fresh()->roles->sortBy('position')), 'group' => new GroupPageResource($group->fresh())]);
    }

    /**
     * Updates the roles permissions
     *
     * @param Group $group
     * @param UpdateGroupRoles $request
     * @return JsonResponse
     */
    public function updateRoles(Group $group, UpdateGroupRoles $request)
    {
        $validated = $request->validated();
        foreach ($validated as $role) {
            $groupRole = GroupRole::find($role['id']);
            if ($groupRole->owner || $groupRole->member) continue;
            $groupRole->update($role);
        }
        ActionTrackingHandler::registerAction($request, 'UPDATE_GROUP_ROLE', 'Updated group roles permissions in group ' . $group->name);
        return ResponseWrapper::successResponse(__('messages.group.role.updated'), ['roles' => GroupRoleResource::collection($group->fresh()->roles->sortBy('position')), 'group' => new GroupPageResource($group->fresh())]);
    }

    /**
     * Creates a new role
     *
     * @param Group $group
     * @param UpdateGroupRoleNameRequest $request
     * @return JsonResponse
     */
    public function storeRole(Group $group, UpdateGroupRoleNameRequest $request)
    {
        $validated = $request->validated();
        GroupRoleHandler::createGroupRoleWithName($group->id, $validated['name']);
        ActionTrackingHandler::registerAction($request, 'UPDATE_GROUP_ROLE', 'Created role with name ' . $validated['name'] . ' in group ' . $group->name);
        return ResponseWrapper::successResponse(__('messages.group.role.created'), ['roles' => GroupRoleResource::collection($group->fresh()->roles->sortBy('position')), 'group' => new GroupPageResource($group->fresh())]);
    }

    /**
     * Deletes the role
     *
     * @param Group $group
     * @param GroupRole $role
     * @param Request $request
     * @return JsonResponse
     */
    public function destroyRole(Group $group, GroupRole $role, Request $request)
    {
        $usersWithRank = GroupUser::where('group_id', $group->id)->where('rank', $role->id)->get();
        $memberRank = GroupRoleHandler::getMemberRank($group->id);
        foreach ($usersWithRank as $groupUser) {
            $groupUser->update(['rank' => $memberRank->id]);
        }
        GroupRoleHandler::deleteRoleAtPosition($group->id, $role->position);
        ActionTrackingHandler::registerAction($request, 'UPDATE_GROUP_ROLE', 'Deleted role ' . $role->name . ' in group ' . $group->name);
        $role->delete();
        return ResponseWrapper::successResponse(__('messages.group.role.deleted'), ['roles' => GroupRoleResource::collection($group->fresh()->roles->sortBy('position')), 'group' => new GroupPageResource($group->fresh())]);
    }

    /**
     * Changes the position to a lower number (up in the ranking)
     *
     * @param Group $group
     * @param GroupRole $role
     * @param Request $request
     * @return void
     */
    public function rankUp(Group $group, GroupRole $role, Request $request)
    {
        $newPosition = $role->position - 1;
        $group->roles()->where('position', $newPosition)->first()->update(['position' => $newPosition + 1]);
        $role->update(['position' => $newPosition]);

        ActionTrackingHandler::registerAction($request, 'UPDATE_GROUP_ROLE', 'Role ' . $role->name . ' moved up a position in group ' . $group->name);
        return ResponseWrapper::successResponse(__('messages.group.role.updated'), ['roles' => GroupRoleResource::collection($group->fresh()->roles->sortBy('position'))]);
    }

    /**
     * Changes the position to a higher number (down in the ranking)
     *
     * @param Group $group
     * @param GroupRole $role
     * @param Request $request
     * @return void
     */
    public function rankDown(Group $group, GroupRole $role, Request $request)
    {
        $newPosition = $role->position + 1;
        $group->roles()->where('position', $newPosition)->first()->update(['position' => $newPosition - 1]);
        $role->update(['position' => $newPosition]);
        ActionTrackingHandler::registerAction($request, 'UPDATE_GROUP_ROLE', 'Role ' . $role->name . ' moved down a position in group ' . $group->name);
        return ResponseWrapper::successResponse(__('messages.group.role.updated'), ['roles' => GroupRoleResource::collection($group->fresh()->roles->sortBy('position'))]);
    }

    /**
     * Changes the user's role/rank in a group
     *
     * @param Group $group
     * @param GroupUser $groupUser
     * @param GroupRole $role
     * @param Request $request
     * @return JsonResponse with GroupPageResource of updated group
     */
    public function updateGroupUserRole(Group $group, GroupUser $groupUser, GroupRole $role, Request $request)
    {
        $groupUser->update(['rank' => $role->id]);
        ActionTrackingHandler::registerAction($request, 'GROUP_UPDATE_ROLES', 'Gave user ' . $groupUser->user->username . ' the role ' . $role->name . ' in group ' . $group->name);
        return ResponseWrapper::successResponse(__('messages.group.role.member_updated'), ['group' => new GroupPageResource($group->fresh())]);
    }
}
