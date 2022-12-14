<?php

namespace App\Policies;

use App\Helpers\GroupRoleHandler;
use App\Models\Group;
use App\Models\GroupMessage;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class GroupPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    /**
     * Whether or not the user can view a group
     *
     * @param User $user
     * @param Group $group
     * @return Boolean
     */
    public function view(User $user, Group $group)
    {
        if ($this->blockedUser($user, $group)) return Response::deny(__('gate.group.not_visible'));
        return $this->alreadyMember($user, $group) || $group->is_public ? Response::allow() : Response::deny(__('gate.group.not_visible'));
    }
    /**
     * Whether or not the user can update a group, eg change name, description, visibility
     *
     * @param User $user
     * @param Group $group
     * @return Boolean
     */
    public function update(User $user, Group $group) 
    {
        return $group->rankOfMemberById($user->id)->can_edit ? Response::allow() : Response::denyWithStatus(422, __('gate.group.not_allowed_update'));
    }
    /**
     * Whether or not the user can delete a group. Generally admin only
     *
     * @param User $user
     * @param Group $group
     * @return Boolean
     */
    public function delete(User $user, Group $group)
    {
        return $group->rankOfMemberById($user->id)->can_delete ? Response::allow() : Response::denyWithStatus(422, __('gate.group.not_owner'));
    }
    /**
     * Whether or not the user can recruit members, eg invite, manage applications
     *
     * @param User $user
     * @param Group $group
     * @return Boolean
     */
    public function recruit(User $user, Group $group)
    {
        return $group->rankOfMemberById($user->id)->can_manage_members ? Response::allow() : Response::denyWithStatus(422, __('gate.group.not_allowed_recruit'));
    }
    
    /**
     * Whether or not the user can join the group. The user must not already be member,
     * must not be blocked, and the group is public
     *
     * @param User $user
     * @param Group $group
     * @return Boolean
     */
    public function join(User $user, Group $group)
    {
        return !$this->alreadyMember($user, $group) 
            && !$this->blockedUser($user, $group)
            && $group->is_public
            ? Response::allow() : Response::denyWithStatus(422, __('gate.group.not_allowed_join'));
    }
    /**
     * Whether or not the user canjoin a private group. The user must not already be member
     * and must not be blocked
     *
     * @param User $user
     * @param Group $group
     * @return Boolean
     */
    public function joinPrivate(User $user, Group $group)
    {
        return !$this->alreadyMember($user, $group) 
            && !$this->blockedUser($user, $group)
            ? Response::allow() : Response::denyWithStatus(422, __('gate.group.not_allowed_join'));
    }
    /**
     * Whether or not the user can leave the group. The user must not be an admin or be the
     * last member of the group (this last thing should never happen)
     *
     * @param User $user
     * @param Group $group
     * @return Boolean
     */
    public function leave(User $user, Group $group)
    {
        if (!$this->alreadyMember($user, $group)) return Response::denyWithStatus(422, __('gate.groups.not_member'));
        return $group->isAdminById($user->id) ? Response::denyWithStatus(422, __('gate.groups.leave_admin')) : Response::allow();
    }

    /**
     * Whether the user can view and send messages
     *
     * @param User $user
     * @param Group $group
     * @return Boolean
     */
    public function message(User $user, Group $group)
    {
        return $this->alreadyMember($user, $group) ? Response::allow() : Response::denyWithStatus(422, __('gate.groups.not_member'));
    }

    public function manageMessage(User $user, Group $group, GroupMessage $groupMessage)
    {
        if ($groupMessage->user_id === $user->id) return Response::allow();
        return $group->rankOfMemberById($user->id)->can_moderate_messages ? Response::allow() : Response::denyWithStatus(422, __('gate.groups.not_allowed_moderate'));
    }

    /**
     * Whether or not the user is already a member
     *
     * @param User $user
     * @param Group $group
     * @return Boolean
     */
    private function alreadyMember(User $user, Group $group)
    {
        return $group->users->find($user);
    }
    /**
     * Whether or not the user is blocked from joining the group
     *
     * @param User $user
     * @param Group $group
     * @return Boolean
     */
    private function blockedUser(User $user, Group $group)
    {
        return $group->suspendedUsers()->find($user);
    }
}
