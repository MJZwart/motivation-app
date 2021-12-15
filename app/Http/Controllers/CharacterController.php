<?php

namespace App\Http\Controllers;

use App\Models\Character;
use Illuminate\Http\Request;
use App\Http\Resources\CharacterResource;
use App\Http\Requests\UpdateCharacterRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use App\Helpers\CharacterHelper;

class CharacterController extends Controller
{
    public function fetchAllCharactersByUser(){ //tested only when exists TODO
        $characters = Character::where('user_id', Auth::user()->id)->get();
        return $characters ? CharacterResource::collection($characters) : null;
    }

    public function fetchActiveCharacter(){ //tested TODO
        if(Auth::user()->rewards == 'CHARACTER'){
            $character = Character::where('user_id', Auth::user()->id)->where('active', true)->first();
            return $character ? new CharacterResource($character) : null;
        }
    }

    public function update(UpdateCharacterRequest $request, Character $character): JsonResponse{
        $validated = $request->validated();
        $character->update($validated);

        $character = new CharacterResource($character);
        
        return new JsonResponse(['message' => ['success' => ["Character successfully updated."]], 'data' => $character], Response::HTTP_OK);
    }

    public function getExperienceTable(){
        return DB::table('experience_points')->get();
    }

    /*
    Inactive
    */
    
    public function destroy(Character $character){
        //
    }

    public function activateCharacter(){

    }

    private function deactivateOtherCharacters(){

    }

    public function fetchCharacterIfExists(){
        
    }

    // public function show(){
    //     //Currently only one character can be active. Will be edited once we allow users to create a new character.
    //     if(Auth::user()->rewards == 'CHARACTER'){
    //         return new CharacterResource(Character::where('user_id', Auth::user()->id)->get()->first());
    //     }
    // }

    //TODO StoreCharacterRequest #66
    public function store(Request $request){
        // #66
    }
}