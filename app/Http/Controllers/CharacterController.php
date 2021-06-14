<?php

namespace App\Http\Controllers;

use App\Models\Character;
use Illuminate\Http\Request;

class CharacterController extends Controller
{
    //TODO StoreCharacterRequest #66
    public function store(Request $request){
        // #66
    }

    public function show(Character $character){
        // #60
    }

    public function update(Request $request, Character $character){
        // #33
    }

    public function destroy(Character $character){
        //
    }
}
