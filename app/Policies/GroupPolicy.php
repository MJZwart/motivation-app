<?php

namespace App\Policies;

use App\Models\Group;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

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
    public function update(User $user, Group $group) 
    {
        return $group->isAdminById($user->id);
    }
    public function join(User $user, Group $group)
    {
        return !$this->alreadyMember($user, $group) 
            && !$this->blockedUser($user, $group)
            && $group->is_public;
    }
    public function joinPrivate(User $user, Group $group)
    {
        return !$this->alreadyMember($user, $group) 
            && !$this->blockedUser($user, $group);
    }

    private function alreadyMember(User $user, Group $group)
    {
        return $group->users->find($user);
    }
    private function blockedUser(User $user, Group $group)
    {
        return $group->suspendedUsers()->find($user);
    }
}
