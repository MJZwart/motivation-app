<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\TaskList;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskPolicy
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
     * Whether or not the user can update the given task
     *
     * @param User $user
     * @param Task $task
     * @return void
     */
    public function update(User $user, Task $task)
    {
        return $task->user_id == $user->id;
    }
}
