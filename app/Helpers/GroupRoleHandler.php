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
        ]);
        GroupRole::create([
            'name' => 'Moderator',
            'can_edit' => true,
            'can_manage_members' => true,
            'can_moderate_messages' => true,
            'group_id' => $groupId,
        ]);
        GroupRole::create([
            'name' => 'Member',
            'can_edit' => false,
            'can_manage_members' => false,
            'can_moderate_messages' => false,
            'group_id' => $groupId,
            'member' => true,
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
        GroupRole::create([
            'name' => $roleName,
            'can_edit' => false,
            'can_manage_members' => false,
            'can_moderate_messages' => false,
            'group_id' => $groupId,
            'member' => false,
            'owner' => false,
        ]);
    }
}
