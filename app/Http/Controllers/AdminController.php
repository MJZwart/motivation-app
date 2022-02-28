<?php

namespace App\Http\Controllers;

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
}
