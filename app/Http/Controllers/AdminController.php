<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateExperiencePointsRequest;
use App\Http\Requests\UpdateCharacterExpGainRequest;
use Illuminate\Http\Request;
use App\Models\Achievement;
use App\Models\AchievementTrigger;
use App\Http\Resources\AchievementResource;
use App\Models\BugReport;
use App\Http\Resources\BugReportResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function getAdminDashboard() {
        $achievements = AchievementResource::collection(Achievement::get());
        $achievementTriggers = AchievementTrigger::get();
        $bugReports = BugReportResource::collection(BugReport::all());
        $experiencePoints = DB::table('experience_points')->get();
        $characterExpGain = DB::table('character_exp_gain')->get();
        $villageExpGain = DB::table('village_exp_gain')->get();
        $balancing = ['experience_points' => $experiencePoints, 'character_exp_gain' => $characterExpGain, 'village_exp_gain' => $villageExpGain];

        return new JsonResponse(
            ['achievements' => $achievements, 'achievementTriggers' => $achievementTriggers, 'bugReports' => $bugReports, 'balancing' => $balancing], 
            Response::HTTP_OK);
    }

    public function updateExeriencePoints(UpdateExperiencePointsRequest $request) {
        $validated = $request->validated();
        DB::table('experience_points')->upsert($validated, ['id'], ['experience_points']);
        $experiencePoints = DB::table('experience_points')->get();
        return new JsonResponse(
            ['message' => ['success' => ['Experience points updated']], 'data' => $experiencePoints], 
            Response::HTTP_OK);
    }

    public function updateCharacterExpGain(UpdateCharacterExpGainRequest $request) {
        $validated = $request->validated();
        DB::table('character_exp_gain')->upsert($validated, ['id'], ['strength', 'agility', 'endurance', 'intelligence', 'charisma', 'level']);
        $characterExpGain = DB::table('character_exp_gain')->get();
        return new JsonResponse(
            ['message' => ['success' => ['Character experience balancing updated']], 'data' => $characterExpGain], 
            Response::HTTP_OK);
    }
}
