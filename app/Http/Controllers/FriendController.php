<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class FriendController extends Controller
{
    public function show(){
        // #56
    }

    public function destroy(Friend $friend){
        // #57
    }

    public function sendFriendRequest(User $user):JsonResponse{
        if(DB::table('friend_requests')->where('user_id', Auth::user()->id)->where('friend_id', $user->id)->exists()){
            return new JsonResponse(['errors' => ['error' => ['You\'ve already sent a friend request to this user']]], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        DB::table('friend_requests')->insertOrIgnore([
            'user_id' => Auth::user()->id,
            'friend_id' => $user->id]);
        Notification::create([
            'user_id' => $user->id,
            'title' => 'New friend request!',
            'text' => 'You have a new friend request from '.Auth::user()->full_display_name.'. Would you like to accept?']);
        return new JsonResponse(['message' => ['message' => ['Friend request successfully sent.']]], Response::HTTP_OK);
    }

    public function acceptFriendRequest(User $user){
        // #52
    }

    public function denyFriendRequest(User $user){
        // #52
    }
}
