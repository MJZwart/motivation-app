<?php

namespace App\Http\Controllers;

use App\Helpers\ActionTrackingHandler;
use App\Helpers\ResponseWrapper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserResource;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class AuthenticationController extends Controller
{
    /**
     * Check login credentials. Logs the user in, regenerates a session.
     */
    public function authenticate(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials)) {
            /** @var User */
            $user = Auth::user();
            if ($user->isBanned()) {
                return $this->handleBannedUser($user, $request);
            }
            $request->session()->regenerate();
            ActionTrackingHandler::handleAction($request, 'LOGIN', 'User logged in ' . $request['username']);
            $user->last_login = Carbon::now();
            $user->save();
            return new JsonResponse(['user' => new UserResource($user)]);
        }
        ActionTrackingHandler::handleAction($request, 'LOGIN', 'User failed to log in ' . $request['username'], 'Invalid login');
        return ResponseWrapper::errorResponse(__('auth.failed'));
    }

    private function handleBannedUser(User $user, Request $request)
    {
        $timeRemaining = Carbon::parse($user->banned_until)->diffForHumans(Carbon::now(), ['syntax' => CarbonInterface::DIFF_RELATIVE_TO_NOW]);
        ActionTrackingHandler::handleAction($request, 'LOGIN', 'Banned user attepted logging in ' . $request['username']);
        $bannedUntilDate = $user->banned_until;
        $reason = $user->bannedUser->first()->reason;
        $errorMessage = 'You are banned until %s. Reason: %s If you wish to dispute your ban, contact one of the admins on our Discord. Time remaining: %s.';
        $parsedMessage = sprintf($errorMessage, $bannedUntilDate, $reason, $timeRemaining);
        return ResponseWrapper::errorResponse($parsedMessage, ['invalid' => true]);
    }

    /**
     * Invalidates the session to log the user out
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return;
    }
}
