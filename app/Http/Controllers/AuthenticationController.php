<?php

namespace App\Http\Controllers;

use App\Helpers\ActionTrackingHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserResource;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\SendResetPasswordEmailRequest;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

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
            /** @var User */
            $user = Auth::user();
            $user->last_login = Carbon::now();
            $user->save();
            return new JsonResponse(['user' => new UserResource($user)]);
        }
        $errorMessage = 'Username or password is incorrect.';
        ActionTrackingHandler::handleAction($request, 'LOGIN', 'User failed to log in ' . $request['username'], 'Invalid login');
        return new JsonResponse(['message' => $errorMessage, 'errors' => ['error' => [$errorMessage]]], Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    private function handleBannedUser(User $user, Request $request)
    {
        $timeRemaining = Carbon::parse($user->banned_until)->diffForHumans(Carbon::now(), ['syntax' => CarbonInterface::DIFF_RELATIVE_TO_NOW]);
        ActionTrackingHandler::handleAction($request, 'LOGIN', 'Banned user attepted logging in ' . $request['username']);
        $bannedUntilDate = $user->banned_until;
        $reason = $user->bannedUser->first()->reason;
        $errorMessage = 'You are banned until %s. Reason: %s If you wish to dispute your ban, contact one of the admins on our Discord. Time remaining: %s.';
        return new JsonResponse([
            'message' => [
                'error' => sprintf($errorMessage, $bannedUntilDate, $reason, $timeRemaining)
            ],
            'invalid' => true
        ]);
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

    public function getResetPasswordLink(SendResetPasswordEmailRequest $request)
    {
        $validated = $request->validated();
        $status = Password::sendResetLink($validated);

        if ($status === Password::RESET_LINK_SENT || $status === Password::INVALID_USER)
            return new JsonResponse(['message' => ['success' => 'Success, if an account with this e-mail exists, we have sent you an e-mail with the link to reset your password. Check your spam folder if you cannot find our email.']], Response::HTTP_OK);
        else
            return new JsonResponse(['message' => 'Something went wrong. Try again later or contact an admin.'], Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $validated = $request->validated();
        $status = Password::reset(
            $validated,
            function ($user, $password) {
                $user->forceFill([
                    'password' => bcrypt($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET)
            return new JsonResponse(['message' => ['success' => 'Password changed. Login with your new password']], Response::HTTP_OK);
        else if ($status === Password::INVALID_TOKEN)
            return new JsonResponse(['message' => 'Invalid token. Please revisit the original link from your email and try again.'], Response::HTTP_UNPROCESSABLE_ENTITY);
        else if ($status === Password::INVALID_USER)
            return new JsonResponse(['message' => 'Invalid user. Check your e-mailaddress and try again.'], Response::HTTP_UNPROCESSABLE_ENTITY);
        else
            return new JsonResponse(['message' => 'Something went wrong. Try again later or contact an admin.'], Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
