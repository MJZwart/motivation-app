<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use App\Models\User;
use App\Models\Notification;
use App\Http\Resources\IncomingFriendRequestResource;
use App\Http\Resources\OutgoingFriendRequestResource;
use App\Http\Resources\UserResource;
use App\Helpers\AchievementHandler;
use App\Helpers\ActionTrackingHandler;
use App\Helpers\NotificationHandler;
use App\Http\Resources\FriendResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
    public function destroy(Request $request, Friend $friend){
        $inverseFriendship = Friend::where('user_id', $friend->friend_id)->where('friend_id', Auth::user()->id)->first();
        $friend->delete();
        $inverseFriendship->delete();
        
        ActionTrackingHandler::handleAction($request, 'DELETE_FRIEND', 'Friendship removed');
        return new JsonResponse(['message' => ['info' => 'Friend removed.'], 
            'friends' => FriendResource::collection(Auth::user()->friends->sortBy('username'))], 
            Response::HTTP_OK);
    }

    /**
     * Sends a friend request to another user. They will receive a notification and have an option to accept this request in the Social page
     */
    public function sendFriendRequest(Request $request, User $user):JsonResponse{
        if(Friend::where('user_id', Auth::user()->id)->where('friend_id', $user->id)->exists()){
            return new JsonResponse(['message' => 'You\'ve already sent a friend request to this user'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $friendRequest = Friend::create(['user_id' => Auth::user()->id, 'friend_id' => $user->id]);
        NotificationHandler::createFromFriendRequest(
            $user->id, 
            'New friend request!',
            'You have a new friend request from '.Auth::user()->username.'. Would you like to accept?',
            $friendRequest,
            true
        );
        ActionTrackingHandler::handleAction($request, 'FRIEND_REQUEST', 'Friend request sent to '.$user->username);
        return new JsonResponse(['message' => ['success' => 'Friend request successfully sent.']], Response::HTTP_OK);
    }

    /**
     * When a user accepts the friend request, changes the request to a friendship and creates a friendship on the opposite site
     * The friendship uses two database entries for both sides of the friendship
     * Returns all open requests after updating
     */
    public function acceptFriendRequest(Request $request, Friend $friend){
        if ($friend->accepted)
            return new JsonResponse(['message' => ['error' => 'You have already accepted this request.']], Response::HTTP_UNPROCESSABLE_ENTITY);
        $friend->accepted = true;
        $friend->update();
        Friend::create(['user_id' => $friend->friend_id, 'friend_id' => $friend->user_id, 'accepted' => true]);

        AchievementHandler::checkForAchievement('FRIENDS', Auth::user());
        AchievementHandler::checkForAchievement('FRIENDS', $friend->friend);
        ActionTrackingHandler::handleAction($request, 'FRIEND_REQUEST', 'Friend request accepted');

        $requests = $this->fetchRequests();
        return new JsonResponse(['message' => ['success' => 'Friend request accepted. You are now friends.'], 
            'friends' => FriendResource::collection(Auth::user()->friends->sortBy('username')),
            'requests' => $requests], 
            Response::HTTP_OK);
    }

    /**
     * When the user denies the friend request, delete the request.
     * Returns the updated list of request
     */
    public function denyFriendRequest(Request $request, Friend $friend){
        if ($friend->accepted)
            return new JsonResponse(['message' => ['error' => 'You have already accepted this request.']], Response::HTTP_UNPROCESSABLE_ENTITY);
        $friend->delete();
        $requests = $this->fetchRequests();
        ActionTrackingHandler::handleAction($request, 'FRIEND_REQUEST', 'Friend request denied');
        return new JsonResponse(['message' => ['info' => 'Friend request denied.'], 
            'requests' => $requests], 
            Response::HTTP_OK);
    }

    public function removeFriendRequest(Request $request, Friend $friend) {
        $friend->delete();
        $requests = $this->fetchRequests();
        ActionTrackingHandler::handleAction($request, 'FRIEND_REQUEST', 'Friend request cancelled');
        return new JsonResponse(['message' => ['info' => 'Friend request cancelled.'], 
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
        $incomingRequests = IncomingFriendRequestResource::collection(
            Friend::where('friend_id', Auth::user()->id)->where('accepted', false)->with('friend')->with('user')->get());
        $outgoingRequests = OutgoingFriendRequestResource::collection(
            Friend::where('user_id', Auth::user()->id)->where('accepted', false)->with('friend')->get());
        return ['incoming' => $incomingRequests, 'outgoing' => $outgoingRequests];
    }

}
