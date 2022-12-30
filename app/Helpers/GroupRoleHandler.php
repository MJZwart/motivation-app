<?php

namespace App\Helpers;

use App\Models\GroupRole;

class GroupRoleHandler
{
    public static function createStandardGroupRoles(int $groupId)
    {
        GroupRole::create([
            'name' => 'Admin',
            'can_edit' => true,
            'can_manage_members' => true,
            'can_moderate_messages' => true,
            'owner' => true,
            'group_id' => $groupId,
            'position' => 1,
        ]);
        GroupRole::create([
            'name' => 'Moderator',
            'can_edit' => true,
            'can_manage_members' => true,
            'can_moderate_messages' => true,
            'group_id' => $groupId,
            'position' => 2,
        ]);
        GroupRole::create([
            'name' => 'Member',
            'can_edit' => false,
            'can_manage_members' => false,
            'can_moderate_messages' => false,
            'group_id' => $groupId,
            'member' => true,
            'position' => 3,
        ]);
    }

    public static function getAdminRank(int $groupId)
    {
        return GroupRole::where('group_id', $groupId)->where('owner', true)->first();
    }

    public static function getMemberRank(int $groupId)
    {
        return GroupRole::where('group_id', $groupId)->where('member', true)->first();
    }

    public static function createGroupRoleWithName(int $groupId, string $roleName)
    {
        $memberRole = GroupRoleHandler::getMemberRank($groupId);
        GroupRole::create([
            'name' => $roleName,
            'can_edit' => false,
            'can_manage_members' => false,
            'can_moderate_messages' => false,
            'group_id' => $groupId,
            'member' => false,
            'owner' => false,
            'position' => $memberRole->position,
        ]);
        $memberRole->update(['position' => $memberRole->position + 1]);
    }

    public static function deleteRoleAtPosition(int $groupId, int $position) {
        $lowerRoles = GroupRole::where('group_id', $groupId)->where('position', '>', $position);
        foreach($lowerRoles as $role) {
            $role->update(['position' => $role->position - 1]);
        }
    }

}
