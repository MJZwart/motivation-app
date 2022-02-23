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

class AdminController extends Controller
{
    public function getAdminDashboard() {
        $achievements = AchievementResource::collection(Achievement::get());
        $achievementTriggers = AchievementTrigger::get();
        $bugReports = BugReportResource::collection(BugReport::all());

        return new JsonResponse(
            ['achievements' => $achievements, 'achievementTriggers' => $achievementTriggers, 'bugReports' => $bugReports], 
            Response::HTTP_OK);

    }
}
