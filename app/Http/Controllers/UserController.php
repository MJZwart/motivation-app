<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\CharacterResource;
use App\Http\Resources\VillageResource;
use App\Http\Resources\UserProfileResource;
use App\Http\Resources\StatsResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Http\Requests\UpdateUserEmailRequest;
use App\Http\Requests\UpdateUserPasswordRequest;
use App\Http\Requests\UpdateUserSettingsRequest;
use App\Http\Requests\UpdateRewardsTypeRequest;
use App\Helpers\CharacterHandler;
use App\Helpers\VillageHandler;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class UserController extends Controller
{
    /**
     * Returns the user from the param wrapped in a profile resource
     */
    public function show(User $user){
        return new UserProfileResource($user);
    }

    /**
     * Returns the authenticated user wrapped in a stats resource
     */
    public function showStats(){
        return new StatsResource(Auth::user());
    }

    /**
     * Checks if authenticated user is admin
     * Returns boolean
     */
    public function isAdmin() {
        if(!Auth::user()->admin){
            return new JsonResponse(['errors' => ['error' => ["You are not admin."]]], Response::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * Updates the authenticated user's email as given in the request
     * Returns updated user
     */
    public function updateEmail(UpdateUserEmailRequest $request){
        $validated = $request->validated();
        Auth::user()->update($validated);
        //Invalidate old e-mail
        //Send new e-mail confirmation
        //Update new e-mail, unconfirmed
        return new JsonResponse(['message' => ['success' => ['Your email has been changed.']], 'user' => new UserResource(Auth::user())], Response::HTTP_OK);
    }

    /**
     * Updates the authenticated user's password as given in the request
     * Logs the user out (front-end) if successful
     */
    public function updatePassword(UpdateUserPasswordRequest $request){
        $validated = $request->validated();
        $validated['password'] = bcrypt($validated['password']);
        Auth::user()->update($validated);
        return new JsonResponse(['message' => ['success' => ['Your password has been updated. Please log in using your new password.']]], Response::HTTP_OK);
    }

    /**
     * Updates general user settings as given in the request
     * Returns the updated user
     */
    public function updateSettings(UpdateUserSettingsRequest $request){
        $validated = $request->validated();
        Auth::user()->update($validated);
        return new JsonResponse([
            'message' => ['success' => ['Your settings have been changed.']], 
            'user' => new UserResource(Auth::user())],
            Response::HTTP_OK);
    }

    /**
     * Updates an authenticated user's reward type as given in the request
     * When turning on a reward type, the request also holds any additional information needed
     * Such as activating an old reward type or creating a new one, with name 
     * Returns the user and the active reward
     */
    public function updateRewardsType(UpdateRewardsTypeRequest $request){
        $validated = $request->validated();
        $user = Auth::user();
        $user->update($validated);
        $activeReward = null;
        if($request['rewards'] == 'CHARACTER'){
            $activeReward = $this->handleCharacterSettings($user, $request['keepOldInstance'], $request['character_name']);
        } else if($request['rewards'] == 'VILLAGE'){
            $activeReward = $this->handleVillageSettings($user, $request['keepOldInstance'], $request['village_name']);
        }
        return new JsonResponse([
            'message' => ['success' => ['Your rewards type has been changed.']], 
            'user' => new UserResource($user),
            'activeReward' => $activeReward],
            Response::HTTP_OK);
    }

    private function handleCharacterSettings(User $user, $keepCharacter, $characterName){
        $character = null;
        if($keepCharacter == 'NEW') {
            $character = CharacterHandler::createNewCharacterAndActivate($user->id, $characterName);
        } else if(is_numeric($keepCharacter)){
            $character = CharacterHandler::toggleCharacterActive($user->id, $keepCharacter);
        }
        return new CharacterResource($character);
    }

    private function handleVillageSettings(User $user, $keepVillage, $villageName) { 
        $village = null;
        if($keepVillage == 'NEW') {
            $village = VillageHandler::createNewVillageAndActivate($user->id, $villageName);
        } else if(is_numeric($keepVillage)){
            $village = VillageHandler::toggleVillageActive($user->id, $keepVillage);
        }
        return new VillageResource($village);
    }

    /**
     * Searches for a user that is like the search parameters in the request.
     * Returns a list of users that qualify
     */
    public function searchUser(Request $request){
        return UserProfileResource::collection(User::where('username', 'like', '%'.$request['userSearch'].'%')->get());
    }
}
