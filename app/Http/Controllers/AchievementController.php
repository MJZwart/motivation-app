<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\User;
use App\Http\Resources\AchievementResource;

class AchievementController extends Controller
{
    //TODO StoreAchievementRequest #42
    public function store(){
        //
    }

    public function show(User $user){
        return $user->achievements;
    }

    public function showAll(){
        return AchievementResource::collection(Achievement::get());
    }

    //TODO UpdateAchievementRequest #44
    public function update(Request $request, Achievement $achievement){
        //
    }

    public function destroy(Achievement $achievement){
        //
    }
}
