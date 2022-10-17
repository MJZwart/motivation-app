<?php

namespace App\Http\Controllers;

use App\Exceptions\FriendNotFoundException;
use App\Models\Friend;
use App\Models\User;
use App\Http\Resources\IncomingFriendRequestResource;
use App\Http\Resources\OutgoingFriendRequestResource;
use App\Helpers\AchievementHandler;
use App\Helpers\ActionTrackingHandler;
use App\Helpers\NotificationHandler;
use App\Helpers\ResponseWrapper;
use App\Http\Resources\FriendResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

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
            __('messages.friend.deleted'),
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
            return ResponseWrapper::errorResponse(__('messages.friend.request.already_sent'));
        }
        if ($activeUser->isBlocked($user->id)) {
            return ResponseWrapper::errorResponse(__('messages.friend.request.unable_to_send'));
        }
        if ($user->isBlocked($activeUser->id)) {
            return ResponseWrapper::errorResponse(__('messages.friend.request.blocked'));
        }
        $friendRequest = Friend::create(['user_id' => $activeUser->id, 'friend_id' => $user->id]);
        NotificationHandler::createFromFriendRequest(
            $user->id,
            __('messages.friend.request.notification_title'),
            __('messages.friend.request.notification_body', ['name' => $activeUser->username]),
            $friendRequest,
            true
        );
        ActionTrackingHandler::handleAction($request, 'FRIEND_REQUEST', 'Friend request sent to ' . $user->username);
        return ResponseWrapper::successResponse(__('messages.friend.request.sent'));
    }

    /**
     * When a user accepts the friend request, changes the request to a friendship and creates a friendship on the opposite site
     * The friendship uses two database entries for both sides of the friendship
     * Returns all open requests after updating
     */
    public function acceptFriendRequest(Request $request, int $friend)
    {
        $friendship = $this->findFriendOrFail($friend);
        if ($friendship->accepted)
            return ResponseWrapper::errorResponse(__('messages.friend.request.already_accepted'));
        $friendship->accepted = true;
        $friendship->update();
        Friend::create(['user_id' => $friendship->friend_id, 'friend_id' => $friendship->user_id, 'accepted' => true]);

        AchievementHandler::checkForAchievement('FRIENDS', Auth::user());
        AchievementHandler::checkForAchievement('FRIENDS', $friendship->friend);
        ActionTrackingHandler::handleAction($request, 'FRIEND_REQUEST', 'Friend request accepted');

        $requests = $this->fetchRequests();
        return ResponseWrapper::successResponse(
            __('messages.friend.request.accepted'),
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
    public function denyFriendRequest(Request $request, int $friend)
    {
        $friendship = $this->findFriendOrFail($friend);
        if ($friendship->accepted)
            return ResponseWrapper::errorResponse(__('messages.friend.request.already_accepted'));
        $friendship->delete();
        $requests = $this->fetchRequests();
        ActionTrackingHandler::handleAction($request, 'FRIEND_REQUEST', 'Friend request denied');
        return ResponseWrapper::successResponse(__('messages.friend.request.denied'), ['requests' => $requests]);
    }

    private function findFriendOrFail(int $friendshipId)
    {
        try {
            return Friend::findOrFail($friendshipId);
        } catch (Throwable $e) {
            if ($e instanceof ModelNotFoundException) {
                throw new FriendNotFoundException();
            }
        }
    }

    public function removeFriendRequest(Request $request, int $friend)
    {
        $friendship = $this->findFriendOrFail($friend);
        if ($friendship->accepted) {
            $requests = $this->fetchRequests();
            return ResponseWrapper::errorResponse(__('messages.friend.request.already_accepted_other'), ['requests' => $requests]);
        }
        $friendship->delete();
        $requests = $this->fetchRequests();
        ActionTrackingHandler::handleAction($request, 'FRIEND_REQUEST', 'Friend request cancelled');
        return ResponseWrapper::successResponse(
            __('messages.friend.request.cancelled'),
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
