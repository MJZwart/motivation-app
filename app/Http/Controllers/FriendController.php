<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use App\Models\User;
use App\Models\Notification;
use App\Http\Resources\IncomingFriendRequestResource;
use App\Http\Resources\OutgoingFriendRequestResource;
use App\Http\Resources\UserResource;
use App\Helpers\AchievementHandler;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class FriendController extends Controller
{
    public function show(){
        // #56
    }

    /**
     * Removes the friendship on both ends.
     * Returns the user, which includes friends.
     */
    public function destroy(Friend $friend){
        $inverseFriendship = Friend::where('user_id', $friend->friend_id)->where('friend_id', Auth::user()->id)->first();
        $friend->delete();
        $inverseFriendship->delete();
        return new JsonResponse(['message' => ['info' => ['Friend removed.']], 
            'user' => new UserResource(Auth::user())], 
            Response::HTTP_OK);
    }

    /**
     * Sends a friend request to another user. They will receive a notification and have an option to accept this request in the Social page
     */
    public function sendFriendRequest(User $user):JsonResponse{
        if(Friend::where('user_id', Auth::user()->id)->where('friend_id', $user->id)->exists()){
            return new JsonResponse(['errors' => ['error' => ['You\'ve already sent a friend request to this user']]], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        Friend::create(['user_id' => Auth::user()->id, 'friend_id' => $user->id]);
        Notification::create([
            'user_id' => $user->id,
            'title' => 'New friend request!',
            'text' => 'You have a new friend request from '.Auth::user()->username.'. Would you like to accept?']);
        return new JsonResponse(['message' => ['success' => ['Friend request successfully sent.']]], Response::HTTP_OK);
    }

    /**
     * When a user accepts the friend request, changes the request to a friendship and creates a friendship on the opposite site
     * The friendship uses two database entries for both sides of the friendship
     * Returns all open requests after updating
     */
    public function acceptFriendRequest(Friend $friend){
        $friend->accepted = true;
        $friend->update();
        Friend::create(['user_id' => $friend->friend_id, 'friend_id' => $friend->user_id, 'accepted' => true]);

        AchievementHandler::checkForAchievement('FRIENDS', Auth::user());
        AchievementHandler::checkForAchievement('FRIENDS', $friend->friend);

        $requests = $this->fetchRequests();
        return new JsonResponse(['message' => ['success' => ['Friend request accepted. You are now friends.']], 
            'user' => new UserResource(Auth::user()),
            'requests' => $requests], 
            Response::HTTP_OK);
    }

    /**
     * When the user denies the friend request, delete the request.
     * Returns the updated list of request
     */
    public function denyFriendRequest(Friend $friend){
        $friend->delete();
        $requests = $this->fetchRequests();
        return new JsonResponse(['message' => ['info' => ['Friend request denied.']], 
            'requests' => $requests], 
            Response::HTTP_OK);
    }

    public function removeFriendRequest(Friend $friend) {
        $friend->delete();
        $requests = $this->fetchRequests();
        return new JsonResponse(['message' => ['info' => ['Friend request cancelled.']], 
            'requests' => $requests], 
            Response::HTTP_OK);
    }

    /**
     * Returns all open friend requests
     */
    public function getAllRequests(){
        $requests = $this->fetchRequests();
        return new JsonResponse($requests, Response::HTTP_OK);
    }

    /**
     * Returns all friend requests, divided in incoming and outgoing
     */
    private function fetchRequests(){
        $incomingRequests = IncomingFriendRequestResource::collection(Friend::where('friend_id', Auth::user()->id)->where('accepted', false)->get());
        $outgoingRequests = OutgoingFriendRequestResource::collection(Friend::where('user_id', Auth::user()->id)->where('accepted', false)->get());
        return ['incoming' => $incomingRequests, 'outgoing' => $outgoingRequests];
    }

}
