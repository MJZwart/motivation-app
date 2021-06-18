<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use App\Models\User;
use Illuminate\Http\Request;

class FriendController extends Controller
{
    public function show(){
        // #56
    }

    public function destroy(Friend $friend){
        // #57
    }

    public function sendFriendRequest(User $user){
        // #51
    }

    public function acceptFriendRequest(User $user){
        // #52
    }

    public function denyFriendRequest(User $user){
        // #52
    }
}
