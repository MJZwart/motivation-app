<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserResource;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class AuthenticationController extends Controller
{
    public function authenticate(LoginRequest $request): JsonResponse{
        $credentials = $request->validated();

        if(Auth::attempt($credentials)){
            $request->session()->regenerate();
            return new JsonResponse(['user' => new UserResource(Auth::user())]);
        }
        $errorMessage = "Username or password is incorrect.";
        return new JsonResponse(['message' => $errorMessage, 'errors' => ['error' => [$errorMessage]]], Response::HTTP_UNPROCESSABLE_ENTITY);

    }

    public function logout(Request $request){
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return;
    }
}
