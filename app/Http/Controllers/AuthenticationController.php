<?php

namespace App\Http\Controllers;

use App\Helpers\ActionTrackingHandler;
use App\Helpers\ResponseWrapper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserResource;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\SendResetPasswordEmailRequest;
use App\Mail\ResetPassword;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
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
            if ($user->isSuspended()) {
                return $this->handleSuspendedUser($user, $request);
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

    /**
     * Handles a suspended user by logging its attempt and parsing the suspension message to the user to show on the login screen.
     *
     * @param User $user
     * @param Request $request
     */
    private function handleSuspendedUser(User $user, Request $request): JsonResponse
    {
        $timeRemaining = Carbon::parse($user->suspended_until)->diffForHumans(Carbon::now(), ['syntax' => CarbonInterface::DIFF_RELATIVE_TO_NOW]);
        ActionTrackingHandler::handleAction($request, 'LOGIN', 'Suspended user attepted logging in ' . $request['username']);
        $suspendedUntilDate = $user->suspended_until;
        $reason = $user->suspendedUser->first()->reason;
        $errorMessage = __('messages.user.suspension.login_notification', [
            'time' => $suspendedUntilDate,
            'reason' => $reason,
            'remaining' => $timeRemaining,
        ]);
        return ResponseWrapper::errorResponse($errorMessage, ['invalid' => true]);
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

    /**
     * Creates a password reset link. To not show to the user whether that user/email exists, always confirm
     * unless there was another technical error. This way a hacker cannot guess an e-mail or try multiple until
     * an email is confirmed.
     *
     * @param SendResetPasswordEmailRequest $request
     */
    public function getResetPasswordLink(SendResetPasswordEmailRequest $request)
    {
        $validated = $request->validated();
        $user = User::where('email', $validated['email'])->first();
        /** @var User */
        if($user == null) return 'No user';
        $token = Password::createToken($user);
        // $url = '';
        
        Mail::to($user->email)->send(new ResetPassword($token, $user->email));
        // $status = Password::sendResetLink($validated);

        // if ($status === Password::RESET_LINK_SENT || $status === Password::INVALID_USER)
        //     return ResponseWrapper::successResponse(__('messages.user.password_reset.link_sent'));
        // else
        //     return ResponseWrapper::errorResponse(__('messages.user.password_reset.link_error'));
    }

    /** 
     * Resets the password using the previously provided link 
     * 
     * @param ResetPasswordRequest $request
     */
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
            return ResponseWrapper::successResponse(__('messages.user.password_reset.reset_success'));
        else if ($status === Password::INVALID_TOKEN)
            return ResponseWrapper::errorResponse(__('messages.user.password_reset.invalid_token'));
        else if ($status === Password::INVALID_USER)
            return ResponseWrapper::errorResponse(__('messages.user.password_reset.invalid_user'));
        else
            return ResponseWrapper::errorResponse(__('messages.user.password_reset.reset_error'));
    }

    /**
     * Checks if the user is logged in, and if so, returns the user. If not, returns a null
     */
    public function me(): JsonResponse
    {
        if (Auth::check()) {
            return new JsonResponse(['user' => new UserResource(Auth::user())]);
        }
        return new JsonResponse(['user' => null]);
    }
}
