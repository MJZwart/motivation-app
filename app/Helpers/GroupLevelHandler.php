<?php

namespace App\Helpers;

use App\Models\ExperiencePoint;
use App\Models\GlobalSetting;
use App\Models\Group;
use App\Models\GroupExpDaily;
use App\Models\GroupExperiencePoint;
use App\Models\GroupUserDailyExp;
use App\Models\GroupUserExp;
use App\Models\User;
use Carbon\Carbon;

class GroupLevelHandler
{
    public static function applyExperienceToGroups(User $user, int $taskDifficulty) {
        foreach($user->groups as $group) {
            $experience = rand(50, 150) * $taskDifficulty; //TODO make this into a dynamically adjustable range
            GroupLevelHandler::applyExperienceAndCheckLevel($group, $experience);
            self::registerGroupUserExpEarned($group, $user, $experience);
        }
    }

    public static function applyExperienceAndCheckLevel(Group $group, int $experience) {
        $currentDailyExp = GroupExpDaily::where('date', Carbon::now()->toDateString())->where('group_id', $group->id)->first();
        if ($currentDailyExp === null) {
            $currentDailyExp = new GroupExpDaily(['group_id' => $group->id]);
        }
        $max = GlobalSetting::where('key', GlobalSetting::MAX_GROUP_EXP)->first()->value;
        if ($currentDailyExp->exp_gained >= $max) {
            return;
        }
        if ($currentDailyExp->exp_gained + $experience >= $max) {
            $experience = $max - $currentDailyExp->exp_gained;
        }
        $currentDailyExp->exp_gained += $experience;
        $currentDailyExp->save();
        $group->experience += $experience;
        $experienceForLevel = GroupExperiencePoint::where('level', $group->level)->first()->experience_points;
        while ($group->experience > $experienceForLevel) {
            $group->level++;
            $group->experience -= $experienceForLevel;
            $experienceForLevel = ExperiencePoint::where('level', $group->level)->first()->experience_points;
        }
        $group->save();
    }

    private static function registerGroupUserExpEarned(Group $group, User $user, int $experience) {
        // ! STOP WHEN MAX HAS BEEN REACHED
        $groupUserId = $group->users->where('id', $user->id)->first()->id;
        $dailyExpRow = GroupUserDailyExp::
            where('date', Carbon::now()->toDateString())
            ->where('user_id', $groupUserId)
            ->where('group_id', $group->id)
            ->first();
        if ($dailyExpRow === null) {
            $dailyExpRow = new GroupUserDailyExp([
                'user_id' => $groupUserId,
                'group_id' => $group->id,
            ]);
        }
        $dailyExpRow->daily_exp += $experience;
        $dailyExpRow->save();
        $totalExpRow = GroupUserExp::where('user_id', $groupUserId)->where('group_id', $group->id)->first();
        if($totalExpRow === null) {
            $totalExpRow = new GroupUserExp([
                'user_id' => $groupUserId,
                'group_id' => $group->id,
            ]);
        }
        $totalExpRow->total_exp += $experience;
        $totalExpRow->save();
    }
}