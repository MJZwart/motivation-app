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

class CharacterController extends Controller
{
    //TODO StoreCharacterRequest #66
    public function store(Request $request){
        // #66
    }

    public function show(){
        //Currently only one character can be active. Will be edited once we allow users to create a new character.
        if(Auth::user()->rewards == 'CHARACTER'){
            return new CharacterResource(Character::where('user_id', Auth::user()->id)->get()->first());
        }
    }

    public function update(UpdateCharacterRequest $request, Character $character): JsonResponse{
        $validated = $request->validated();
        $character->update($validated);

        $character = new CharacterResource($character);
        
        return new JsonResponse(['message' => ['message' => ["Character successfully updated."]], 'data' => $character], Response::HTTP_OK);
    }

    public function destroy(Character $character){
        //
    }

    public function getExperienceTable(){
        return DB::table('experience_points')->get();
    }
}
