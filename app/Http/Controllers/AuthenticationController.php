<?php

namespace App\Http\Controllers;

use App\Helpers\ActionTrackingHandler;
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
    public function authenticate(LoginRequest $request): JsonResponse{
        $credentials = $request->validated();

        if(Auth::attempt($credentials)){
            /** @var User */
            $user = Auth::user();
            if (!$user->isActive()){
                return $this->handleBannedUser($user, $request);
            }
            $request->session()->regenerate();
            ActionTrackingHandler::handleAction($request, 'LOGIN', 'User logged in '.$request['username']);
            return new JsonResponse(['user' => new UserResource(Auth::user())]);
        }
        $errorMessage = 'Username or password is incorrect.';
        ActionTrackingHandler::handleAction($request, 'LOGIN', 'User failed to log in '.$request['username'], 'Invalid login');
        return new JsonResponse(['message' => $errorMessage, 'errors' => ['error' => [$errorMessage]]], Response::HTTP_UNPROCESSABLE_ENTITY);

    }

    private function handleBannedUser(User $user, Request $request) {
        $timeRemaining = Carbon::parse($user->active)->diffForHumans(Carbon::now(), ['syntax' => CarbonInterface::DIFF_RELATIVE_TO_NOW]);
        ActionTrackingHandler::handleAction($request, 'LOGIN', 'Banned user attepted logging in '.$request['username']);
        $activeDate = $user->active;
        $reason = $user->bannedUser->first()->reason;
        $errorMessage = 'You are banned until %s. Reason: %s If you wish to dispute your ban, contact one of the admins on our Discord. Time remaining: %s.';
        return new JsonResponse([
            'message' => [
                'error' => sprintf($errorMessage, $activeDate, $reason, $timeRemaining)], 
            'invalid' => true]);
    }

    /**
     * Invalidates the session to log the user out
     */
    public function logout(Request $request){
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return;
    }
}
