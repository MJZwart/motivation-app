<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\AchievementTrigger;
use App\Models\User;
use App\Http\Resources\AchievementResource;
use App\Http\Requests\NewAchievementRequest;
use App\Helpers\AchievementHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;


use Illuminate\Support\Facades\Auth;

class AchievementController extends Controller
{
    public function store(NewAchievementRequest $request){
        $validated = $request->validated();
        $validated['trigger_description'] = $this->parseTrigger($validated['trigger_amount'], $validated['trigger_type']);
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

    public function update(NewAchievementRequest $request, Achievement $achievement){
        $validated = $request->validated();
        $validated['trigger_description'] = $this->parseTrigger($validated['trigger_amount'], $validated['trigger_type']);
        $achievement->update($validated);

        return new JsonResponse(['message' => ['message' => ["Achievement updated."]], 'achievements' => AchievementResource::collection(Achievement::get())], Response::HTTP_OK);
    }

    public function destroy(Achievement $achievement){
        //
    }

    private function parseTrigger($amount, $type){
        $trigger = AchievementTrigger::where('trigger_type', $type)->first();
        $plural = $amount > 1 ? 's': '';
        return sprintf($trigger->trigger_description, $amount, $plural);
    }

    public function testGet(){
        return AchievementHandler::checkForAchievement('FRIENDS', Auth::user());
    }
    public function testPost(Request $request){

    }
}
