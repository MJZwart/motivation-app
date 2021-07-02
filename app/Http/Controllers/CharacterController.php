<?php

namespace App\Http\Controllers;

use App\Models\Character;
use Illuminate\Http\Request;
use App\Http\Resources\CharacterResource;
use Illuminate\Support\Facades\Auth;

class CharacterController extends Controller
{
    //TODO StoreCharacterRequest #66
    public function store(Request $request){
        // #66
    }

    public function show(){
        //Currently only one character can be active. Will be edited once we allow users to create a new character.
        return new CharacterResource(Character::where('user_id', Auth::user()->id)->get()->first());
    }

    public function update(Request $request, Character $character){
        // #33
    }

    public function destroy(Character $character){
        //
    }
}
