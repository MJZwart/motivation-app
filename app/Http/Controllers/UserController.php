<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\UserProfileResource;
use App\Http\Resources\StatsResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Http\Requests\UpdateUserEmailRequest;
use App\Http\Requests\UpdateUserPasswordRequest;
use App\Http\Requests\UpdateUserSettingsRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function show(User $user){
        return new UserProfileResource($user);
    }

    public function showStats(){
        return new StatsResource(Auth::user());
    }

    public function isAdmin() {
        if(!Auth::user()->admin){
            return new JsonResponse(['errors' => ['error' => ["You are not admin."]]], Response::HTTP_UNAUTHORIZED);
        }
    }

    public function updateEmail(UpdateUserEmailRequest $request){
        $validated = $request->validated();
        Auth::user()->update($validated);
        //Invalidate old e-mail
        //Send new e-mail confirmation
        //Update new e-mail, unconfirmed
        return new JsonResponse(['message' => ['message' => ['Your email has been changed.']], 'user' => new UserResource(Auth::user())], Response::HTTP_OK);
    }

    public function updatePassword(UpdateUserPasswordRequest $request){
        $validated = $request->validated();
        $validated['password'] = bcrypt($validated['password']);
        Auth::user()->update($validated);
        return new JsonResponse(['message' => ['message' => ['Your password has been updated. Please log in using your new password.']]], Response::HTTP_OK);
    }

    public function updateSettings(UpdateUserSettingsRequest $request){
        $validated = $request->validated();
        Auth::user()->update($validated);
        return new JsonResponse(['message' => ['message' => ['Your settings have been changed.']], 'user' => new UserResource(Auth::user())], Response::HTTP_OK);
    }

    public function searchUser(Request $request){
        return User::where('full_display_name', 'like', '%'.$request['userSearch'].'%')->get();
    }
}
