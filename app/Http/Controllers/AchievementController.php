<?php

namespace App\Http\Controllers;

use App\Helpers\ActionTrackingHandler;
use App\Models\Achievement;
use App\Models\AchievementTrigger;
use App\Http\Resources\AchievementResource;
use App\Http\Requests\NewAchievementRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class AchievementController extends Controller
{
    /** 
     * Create a new achievement. Only available to admins.
     * Returns all available achievements
     */
    public function store(NewAchievementRequest $request){
        $validated = $request->validated();
        $validated['trigger_description'] = $this->parseTrigger($validated['trigger_amount'], $validated['trigger_type']);
        Achievement::create($validated);
        ActionTrackingHandler::handleAction($request, 'STORE_ACHIEVEMENT', 'Created new achievement: '.$validated['name']);

        return new JsonResponse(['message' => ['success' => ["Achievement added."]], 'achievements' => AchievementResource::collection(Achievement::get())], Response::HTTP_OK);
    }

    /**
     * Returns all available achievements
     */
    public function showAll(){
        return AchievementResource::collection(Achievement::get());
    }

    /**
     * Returns all available achievement triggers
     */
    public function showTriggers(){
        return AchievementTrigger::get();
    }

    /**
     * Update an existing achievement. Only available to admins.
     * Returns all available achievements
     */
    public function update(NewAchievementRequest $request, Achievement $achievement){
        $validated = $request->validated();
        $validated['trigger_description'] = $this->parseTrigger($validated['trigger_amount'], $validated['trigger_type']);
        $achievement->update($validated);
        ActionTrackingHandler::handleAction($request, 'UPDATE_ACHIEVEMENT', 'Updated achievement: '.$validated['name']);

        return new JsonResponse(['message' => ['success' => ["Achievement updated."]], 'achievements' => AchievementResource::collection(Achievement::get())], Response::HTTP_OK);
    }

    public function destroy(Achievement $achievement){
        //
    }

    /**
     * @param Number $amount - The amount of the trigger type needed to get the achievement
     * @param String $type - The trigger type of the achievement
     * Uses the trigger description and applies the type and amount needed to earn the achievement, and rewrites it to a logical sentence
     * Returns the parsed trigger
     */
    private function parseTrigger($amount, $type){
        $trigger = AchievementTrigger::where('trigger_type', $type)->first();
        $plural = $amount > 1 ? 's': '';
        return sprintf($trigger->trigger_description, $amount, $plural);
    }
}
