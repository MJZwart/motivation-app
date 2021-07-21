<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\AchievementTrigger;
use App\Models\User;
use App\Http\Resources\AchievementResource;
use App\Http\Requests\NewAchievementRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class AchievementController extends Controller
{
    public function store(NewAchievementRequest $request){
        $validated = $request->validated();
        Achievement::create($validated);

        return new JsonResponse(['message' => ['message' => ["Achievement added."]], 'achievements' => AchievementResource::collection(Achievement::get())], Response::HTTP_OK);
    }

    public function show(User $user){
        return $user->achievements;
    }

    public function showAll(){
        return AchievementResource::collection(Achievement::get());
    }

    public function showTriggers(){
        return AchievementTrigger::get();
    }

    //TODO UpdateAchievementRequest #44
    public function update(Request $request, Achievement $achievement){
        //
    }

    public function destroy(Achievement $achievement){
        //
    }
}
