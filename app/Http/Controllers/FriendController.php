<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use App\Models\User;
use App\Http\Resources\IncomingFriendRequestResource;
use App\Http\Resources\OutgoingFriendRequestResource;
use App\Helpers\AchievementHandler;
use App\Helpers\ActionTrackingHandler;
use App\Helpers\NotificationHandler;
use App\Helpers\ResponseWrapper;
use App\Http\Resources\FriendResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FriendController extends Controller
{
    public function getFriends()
    {
        return new JsonResponse(['friends' => FriendResource::collection(Auth::user()->friends->sortBy('username'))]);
    }

    /**
     * Removes the friendship on both ends.
     * Returns the user, which includes friends.
     */
    public function destroy(Request $request, Friend $friend)
    {
        $inverseFriendship = Friend::where('user_id', $friend->friend_id)->where('friend_id', Auth::user()->id)->first();
        $friend->delete();
        $inverseFriendship->delete();

        ActionTrackingHandler::handleAction($request, 'DELETE_FRIEND', 'Friendship removed');
        return ResponseWrapper::successResponse(
            'Friend removed.',
            ['friends' => FriendResource::collection(Auth::user()->friends->sortBy('username'))]
        );
    }

    /**
     * Sends a friend request to another user. They will receive a notification and have an option to accept this request in the Social page
     */
    public function sendFriendRequest(Request $request, User $user): JsonResponse
    {
        /** @var User */
        $activeUser = Auth::user();
        if (Friend::where('user_id', $activeUser->id)->where('friend_id', $user->id)->exists()) {
            return ResponseWrapper::errorResponse('You\'ve already sent a friend request to this user');
        }
        if ($activeUser->isBlocked($user->id)) {
            return ResponseWrapper::errorResponse('Unable to send a friend request to this user.');
        }
        if ($user->isBlocked($activeUser->id)) {
            return ResponseWrapper::errorResponse('You have blocked this user.');
        }
        $friendRequest = Friend::create(['user_id' => $activeUser->id, 'friend_id' => $user->id]);
        NotificationHandler::createFromFriendRequest(
            $user->id,
            'New friend request!',
            'You have a new friend request from ' . $activeUser->username . '. Would you like to accept?',
            $friendRequest,
            true
        );
        ActionTrackingHandler::handleAction($request, 'FRIEND_REQUEST', 'Friend request sent to ' . $user->username);
        return ResponseWrapper::successResponse('Friend request successfully sent.');
    }

    /**
     * When a user accepts the friend request, changes the request to a friendship and creates a friendship on the opposite site
     * The friendship uses two database entries for both sides of the friendship
     * Returns all open requests after updating
     */
    public function acceptFriendRequest(Request $request, Friend $friend)
    {
        if ($friend->accepted)
            return ResponseWrapper::errorResponse('You have already accepted this request.');
        $friend->accepted = true;
        $friend->update();
        Friend::create(['user_id' => $friend->friend_id, 'friend_id' => $friend->user_id, 'accepted' => true]);

        AchievementHandler::checkForAchievement('FRIENDS', Auth::user());
        AchievementHandler::checkForAchievement('FRIENDS', $friend->friend);
        ActionTrackingHandler::handleAction($request, 'FRIEND_REQUEST', 'Friend request accepted');

        $requests = $this->fetchRequests();
        return ResponseWrapper::successResponse(
            'Friend request accepted. You are now friends.',
            [
                'friends' => FriendResource::collection(Auth::user()->friends->sortBy('username')),
                'requests' => $requests
            ]
        );
    }

    /**
     * When the user denies the friend request, delete the request.
     * Returns the updated list of request
     */
    public function denyFriendRequest(Request $request, Friend $friend)
    {
        if ($friend->accepted)
            return ResponseWrapper::errorResponse('You have already accepted this request.');
        $friend->delete();
        $requests = $this->fetchRequests();
        ActionTrackingHandler::handleAction($request, 'FRIEND_REQUEST', 'Friend request denied');
        return ResponseWrapper::successResponse('Friend request denied.', ['requests' => $requests]);
    }

    public function removeFriendRequest(Request $request, Friend $friend)
    {
        $friend->delete();
        $requests = $this->fetchRequests();
        ActionTrackingHandler::handleAction($request, 'FRIEND_REQUEST', 'Friend request cancelled');
        return ResponseWrapper::successResponse(
            'Friend request cancelled.',
            ['requests' => $requests]
        );
    }

    /**
     * Returns all open friend requests
     */
    public function getAllRequests()
    {
        $requests = $this->fetchRequests();
        return new JsonResponse($requests);
    }

    /**
     * Returns all friend requests, divided in incoming and outgoing
     */
    private function fetchRequests()
    {
        $incomingRequests = IncomingFriendRequestResource::collection(
            Friend::where('friend_id', Auth::user()->id)->where('accepted', false)->with('friend')->with('user')->get()
        );
        $outgoingRequests = OutgoingFriendRequestResource::collection(
            Friend::where('user_id', Auth::user()->id)->where('accepted', false)->with('friend')->get()
        );
        return ['incoming' => $incomingRequests, 'outgoing' => $outgoingRequests];
    }
}
