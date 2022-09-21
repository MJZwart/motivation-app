<?php

namespace App\Http\Controllers;

use App\Helpers\ActionTrackingHandler;
use App\Helpers\ResponseWrapper;
use Illuminate\Http\Request;
use App\Http\Resources\UserProfileResource;
use App\Http\Resources\StrippedUserResource;
use App\Http\Resources\StatsResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\Message;
use App\Models\Notification;
use App\Models\ReportedUser;
use App\Http\Requests\UpdateUserEmailRequest;
use App\Http\Requests\UpdateUserPasswordRequest;
use App\Http\Requests\UpdateUserSettingsRequest;
use App\Http\Requests\UpdateRewardsTypeRequest;
use App\Http\Requests\StoreReportedUserRequest;
use App\Helpers\RewardObjectHandler;
use App\Http\Resources\BlockedUserResource;
use App\Models\BlockedUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class UserController extends Controller
{
    /**
     * Returns the user from the param wrapped in a profile resource
     */
    public function show(User $user)
    {
        /** @var User */
        $activeUser = Auth::user();
        if ($activeUser->isBlocked($user->id)) {
            return ResponseWrapper::errorResponse('Unable to view this user\'s profile.');
        }
        return new UserProfileResource($user);
    }

    /**
     * Returns the authenticated user wrapped in a stats resource
     */
    public function showStats()
    {
        return new StatsResource(Auth::user());
    }

    /**
     * Checks if authenticated user is admin
     * Returns boolean
     */
    //TODO is this okay?
    public function isAdmin()
    {
        if (!Auth::user()->admin) {
            return ResponseWrapper::forbiddenResponse("You are not admin.");
        }
    }

    /**
     * Updates the authenticated user's email as given in the request
     * Returns updated user
     */
    public function updateEmail(UpdateUserEmailRequest $request)
    {
        $validated = $request->validated();
        /** @var User */
        $user = Auth::user();
        $user->update($validated);
        ActionTrackingHandler::handleAction($request, 'UPDATE_USER', 'Updating email');
        //Invalidate old e-mail
        //Send new e-mail confirmation
        //Update new e-mail, unconfirmed
        return new JsonResponse(['message' => ['success' => 'Your email has been changed.'], 'user' => new UserResource(Auth::user())], Response::HTTP_OK);
    }

    /**
     * Updates the authenticated user's password as given in the request
     * Logs the user out (front-end) if successful
     */
    public function updatePassword(UpdateUserPasswordRequest $request)
    {
        $validated = $request->validated();
        $validated['password'] = bcrypt($validated['password']);
        /** @var User */
        $user = Auth::user();
        $user->update($validated);
        ActionTrackingHandler::handleAction($request, 'UPDATE_USER', 'Updating password');
        return new JsonResponse(['message' => ['success' => 'Your password has been updated. Please log in using your new password.']], Response::HTTP_OK);
    }

    /**
     * Updates general user settings as given in the request
     * Returns the updated user
     */
    public function updateSettings(UpdateUserSettingsRequest $request)
    {
        $validated = $request->validated();
        /** @var User */
        $user = Auth::user();
        $user->update($validated);
        ActionTrackingHandler::handleAction($request, 'UPDATE_USER', 'Updating settings');
        return new JsonResponse(
            [
                'message' => ['success' => 'Your settings have been changed.'],
                'user' => new UserResource(Auth::user())
            ],
            Response::HTTP_OK
        );
    }

    /**
     * Updates an authenticated user's reward type as given in the request
     * When turning on a reward type, the request also holds any additional information needed
     * Such as activating an old reward type or creating a new one, with name 
     * Returns the user and the active reward
     */
    public function updateRewardsType(UpdateRewardsTypeRequest $request)
    {
        $validated = $request->validated();
        /** @var User */
        $user = Auth::user();
        $user->update($validated);
        $activeReward = null;
        $activeReward = RewardObjectHandler::changeRewardSettings(
            $user,
            $request['keepOldInstance'],
            $request['new_object_name'],
            $request['rewards']
        );
        ActionTrackingHandler::handleAction($request, 'UPDATE_USER', 'Updating rewards type');
        return ResponseWrapper::successResponse('Your rewards type has been changed.', ['user' => new UserResource($user), 'activeReward' => $activeReward]);
    }

    /**
     * Searches for a user that is like the search parameters in the request.
     * Returns a list of users that qualify
     */
    public function searchUser(Request $request)
    {
        /** @var User */
        $activeUser = Auth::user();
        $blockedUsers = $activeUser->blockedUsers()->get();
        $blockedByUsers = $activeUser->blockedByUsers()->get();
        $exclude = $blockedUsers->concat($blockedByUsers);
        $exclude = $exclude->map(function ($item) {
            return $item->id;
        });
        return StrippedUserResource::collection(User::where('username', 'like', '%' . $request['userSearch'] . '%')
            ->whereNotIn('id', $exclude)->get());
    }



    /**
     * Check if the user has any unread notifications
     * Returns boolean
     */
    public function hasUnread()
    {
        $hasMessages = Message::where('recipient_id', Auth::user()->id)->where('read', false)->where('visible_to_recipient', true)->count() > 0;
        $hasNotifications = Notification::where('user_id', Auth::user()->id)->where('read', false)->count() > 0;
        return new JsonResponse(['hasNotifications' => $hasNotifications, 'hasMessages' => $hasMessages]);
    }

    public function reportUser(StoreReportedUserRequest $request, User $user)
    {
        $validated = $request->validated();
        $validated['reported_user_id'] = $user->id;
        $validated['reported_by_user_id'] = Auth::user()->id;
        ReportedUser::create($validated);
        ActionTrackingHandler::handleAction($request, 'REPORT_USER', 'User reported: ' . $user->username);
        return new JsonResponse(['message' => ['success' => 'User reported']]);
    }

    public function getBlocklist()
    {
        $blockedUsers = BlockedUser::where('user_id', Auth::user()->id)->get();
        return new JsonResponse(['blockedUsers' => BlockedUserResource::collection($blockedUsers)]);
    }

    public function unblockUser(BlockedUser $blockedUser)
    {
        $blockedUser->delete();
        $blockedUsers = BlockedUser::where('user_id', Auth::user()->id)->get();
        return new JsonResponse(['message' => ['success' => 'User has been unblocked'], 'blockedUsers' => BlockedUserResource::collection($blockedUsers)]);
    }
}
