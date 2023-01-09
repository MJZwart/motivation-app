<?php

namespace App\Http\Controllers;

use App\Helpers\ActionTrackingHandler;
use App\Helpers\ResponseWrapper;
use App\Models\Achievement;
use App\Http\Resources\AchievementResource;
use App\Http\Requests\StoreAchievementRequest;

class AchievementController extends Controller
{
    /**
     * Fetches all achievements
     *
     * @return ResourceCollection
     */
    public function getAllAchievements()
    {
        return AchievementResource::collection(Achievement::get());
    }

    /** 
     * Create a new achievement. Only available to admins.
     * Returns all available achievements
     */
    public function store(StoreAchievementRequest $request)
    {
        $validated = $request->validated();
        Achievement::create($validated);
        ActionTrackingHandler::handleAction($request, 'STORE_ACHIEVEMENT', 'Created new achievement: ' . $validated['name']);

        return ResponseWrapper::successResponse(__('messages.achievement.created'), ['achievements' => AchievementResource::collection(Achievement::get())]);
    }

    /**
     * Returns all available achievements
     */
    public function showAll()
    {
        return AchievementResource::collection(Achievement::get());
    }

    /**
     * Update an existing achievement. Only available to admins.
     * Returns all available achievements
     */
    public function update(StoreAchievementRequest $request, Achievement $achievement)
    {
        $validated = $request->validated();
        $achievement->update($validated);
        ActionTrackingHandler::handleAction($request, 'UPDATE_ACHIEVEMENT', 'Updated achievement: ' . $validated['name']);

        return ResponseWrapper::successResponse(__('messages.achievement.updated'), ['achievements' => AchievementResource::collection(Achievement::get())]);
    }

    public function destroy(Achievement $achievement)
    {
        //
    }
}
