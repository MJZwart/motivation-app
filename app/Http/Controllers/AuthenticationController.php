<?php

namespace App\Http\Controllers;

use App\Helpers\ActionTrackingHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserResource;
use App\Http\Requests\LoginRequest;
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
            $request->session()->regenerate();
            ActionTrackingHandler::handleAction($request, 'LOGIN', 'User logged in '.$request['username']);
            return new JsonResponse(['user' => new UserResource(Auth::user())]);
        }
        $errorMessage = 'Username or password is incorrect.';
        ActionTrackingHandler::handleAction($request, 'LOGIN', 'User failed to log in '.$request['username'], 'Invalid login');
        return new JsonResponse(['message' => $errorMessage, 'errors' => ['error' => [$errorMessage]]], Response::HTTP_UNPROCESSABLE_ENTITY);

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
