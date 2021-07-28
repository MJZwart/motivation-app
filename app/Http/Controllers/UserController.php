<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\UserProfileResource;
use App\Http\Resources\StatsResource;
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
        //validate
        //If e-mail is changed, invalidate old e-mail
        //Send new e-mail confirmation
        //Update new e-mail, unconfirmed
    }

    public function updatePassword(UpdateUserPasswordRequest $request){
        $user = Auth::user();
        $validated = $request->validated();
        $validated['password'] = bcrypt($validated['password']);
        $user->update($validated);
        return new JsonResponse(['messages' => ['message' => ['Your password has been updated. Please log in using your new password.']]], Response::HTTP_OK);
    }

    public function updateSettings(UpdateUserSettingsRequest $request){
        //validate
        //Update
    }
}
