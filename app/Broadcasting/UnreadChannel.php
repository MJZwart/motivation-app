<?php

namespace App\Broadcasting;

use App\Models\User;

class UnreadChannel
{
    /**
     * Create a new channel instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Authenticate the user's access to the channel.
     *
     * @param  \App\Models\User  $user
     * @return array|bool
     */
    public function join(User $user, int $id)
    {
        return $id === $user->id;
    }
}
