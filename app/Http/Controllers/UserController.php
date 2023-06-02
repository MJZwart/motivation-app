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
use App\Http\Requests\BlockUserRequest;
use App\Http\Requests\ToggleTutorialRequest;
use App\Http\Requests\UnblockUserRequest;
use App\Http\Requests\UpdateLanguage;
use App\Http\Resources\BlockedUserResource;
use App\Models\BlockedUser;
use App\Models\Friend;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    /**
     * Returns the user from the param wrapped in a profile resource
     */
    public function show(User $user)
    {
        /** @var User */
        $activeUser = Auth::user();
        if ($activeUser->isBlockedByUser($user->id)) {
            return ResponseWrapper::errorResponse(__('messages.user.profile_unavailable'));
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
        return ResponseWrapper::successResponse(__('messages.user.email_updated'), ['user' => new UserResource(Auth::user())]);
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
        return ResponseWrapper::successResponse(__('messages.user.password_updated'));
    }

    public function toggleTutorial(ToggleTutorialRequest $request): JsonResponse
    {
        $validated = $request->validated();
        /** @var User */
        $user = Auth::user();
        $user->show_tutorial = $validated['show'];
        $user->save();
        ActionTrackingHandler::handleAction($request, 'UPDATE_USER', 'Toggling tutorial show');
        return ResponseWrapper::successResponse('Your tutorial settings have been updated', ['user' => $user]);
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
        return ResponseWrapper::successResponse(__('messages.user.settings_updated'), ['user' => new UserResource($user->fresh())]);
    }

    /**
     * Updates the user's language
     * Returns the updated user
     */
    public function updateLanguage(UpdateLanguage $request) 
    {
        $validated = $request->validated();
        /** @var User */
        $user = Auth::user();
        $user->update($validated);
        ActionTrackingHandler::handleAction($request, 'UPDATE_LANGUAGE', 'Language changed');
        return ResponseWrapper::successResponse(__('messages.user.language_updated'), ['user' => new UserResource($user->fresh())]);
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
        return ResponseWrapper::successResponse(__('messages.user.reward_updated'), ['user' => new UserResource($user), 'activeReward' => $activeReward]);
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

    public function reportUser(StoreReportedUserRequest $request, User $user)
    {
        $validated = $request->validated();
        $validated['reported_user_id'] = $user->id;
        $validated['reported_by_user_id'] = Auth::user()->id;
        ReportedUser::create($validated);
        ActionTrackingHandler::handleAction($request, 'REPORT_USER', 'User reported: ' . $user->username);
        return ResponseWrapper::successResponse(__('messages.user.reported'));
    }

    public function getBlocklist()
    {
        $blockedUsers = BlockedUser::where('user_id', Auth::user()->id)->get();
        return new JsonResponse(['blockedUsers' => BlockedUserResource::collection($blockedUsers)]);
    }
    
    public function blockUser(BlockUserRequest $request, User $blockedUser)
    {
        $validated = $request->validated();

        /** @var User */
        $user = Auth::user();
        $blocked = BlockedUser::create([
            'user_id' => $user->id,
            'blocked_user_id' => $blockedUser->id,
        ]);
        if ($validated['hideMessages']) {
            MessageController::makeConversationInvisible($user, $blocked);
        }
        //delete friendship and inverse friendship
        $toDelete = Friend::where('user_id', $user->id)->where('friend_id', $blockedUser->id)->first();
        if ($toDelete) $toDelete->delete();
        $toDelete = Friend::where('user_id', $blockedUser->id)->where('friend_id', $user->id)->first();
        if ($toDelete) $toDelete->delete();

        ActionTrackingHandler::handleAction($request, 'BLOCK_USER', 'Blocked user ' . $blockedUser->username);
        return ResponseWrapper::successResponse(__('messages.user.blocked'));
    }

    public function unblockUser(UnblockUserRequest $request, BlockedUser $blockedUser)
    {
        /** @var User */
        $user = Auth::user();
        if ($request['restoreMessages']) {
            MessageController::restoreHiddenConversation($user, $blockedUser);
        }

        $blockedUser->delete();

        $blockedUsers = BlockedUser::where('user_id', Auth::user()->id)->get();
        return ResponseWrapper::successResponse(__('messages.user.unblocked'), ['blockedUsers' => BlockedUserResource::collection($blockedUsers)]);
    }

    public function hasUnread(): JsonResponse
    {
        $userId = Auth::user()->id;
        $hasMessages = Message::hasUnread($userId);
        $hasNotifications = Notification::hasUnread($userId);
        return new JsonResponse(['hasNotifications' => $hasNotifications, 'hasMessages' => $hasMessages]);
    }
}
