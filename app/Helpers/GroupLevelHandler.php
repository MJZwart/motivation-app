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
    /**
     * Applies experience to groups and tracks daily and total exp contribution
     */
    public static function applyExperienceToGroups(User $user, int $taskDifficulty) {
        foreach($user->groups as $group) {
            // Calculates experience points
            $experience = rand(50, 150) * $taskDifficulty; //TODO make this into a dynamically adjustable range
            // Adjusts experience gained if max has been reached
            $experience = self::checkForMaxExpAppliedToday($experience, $group, $user);
            if ($experience === 0) return;
            // Apply experience to group and user exp tracking
            GroupLevelHandler::applyExperienceAndCheckLevel($group, $experience);
            self::registerGroupUserExpEarned($group, $user, $experience);
        }
    }

    /**
     * Applies the experience earned to a group, checks for a level up and applies this where possible
     */
    public static function applyExperienceAndCheckLevel(Group $group, int $experience) {
        $group->experience += $experience;
        // Checks for level up and applies if possible
        $experienceForLevel = GroupExperiencePoint::where('level', $group->level)->first()->experience_points;
        while ($group->experience > $experienceForLevel) {
            $group->level++;
            $group->experience -= $experienceForLevel;
            $experienceForLevel = ExperiencePoint::where('level', $group->level)->first()->experience_points;
        }
        $group->save();
    }

    /**
     * Tracks the user contribution to this group, both in daily exp and total
     */
    private static function registerGroupUserExpEarned(Group $group, User $user, int $experience) {
        $groupUserId = $group->users->where('id', $user->id)->first()->id;
        // Fetches today's contribution, creates it if not present, then updates
        // $dailyExpRow = GroupUserDailyExp::
        //     where('date', Carbon::now()->toDateString())
        //     ->where('user_id', $groupUserId)
        //     ->where('group_id', $group->id)
        //     ->first();
        // if ($dailyExpRow === null) {
        //     $dailyExpRow = new GroupUserDailyExp([
        //         'user_id' => $groupUserId,
        //         'group_id' => $group->id,
        //     ]);
        // }
        // $dailyExpRow->exp_gained += $experience;
        // $dailyExpRow->save();
        // Fetches total contribution, creates it if not present, then updates
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

    /**
     * Check if this group has already reached max daily exp gained and adjusts experience accordingly
     */
    private static function checkForMaxExpAppliedToday(int $experience, Group $group, User $user): int 
    {
        $groupUserId = $group->users->where('id', $user->id)->first()->id;
        $currentDailyExp = GroupUserDailyExp::where('date', Carbon::now()->toDateString())
            ->where('group_id', $group->id)
            ->where('user_id', $user->id)
            ->first();
        $currentDailyGroupExp = GroupExpDaily::where('date', Carbon::now()->toDateString())->where('group_id', $group->id)->first();
        if ($currentDailyGroupExp === null) {
            $currentDailyGroupExp = new GroupExpDaily([
                'group_id' => $group->id,
        ]);}
        if ($currentDailyExp === null) {
            $currentDailyExp = new GroupUserDailyExp([
                'group_id' => $group->id,
                'user_id' => $groupUserId,
        ]);
        }
        $max = GlobalSetting::where('key', GlobalSetting::MAX_GROUP_EXP)->first()->value;
        if ($currentDailyExp->exp_gained >= $max) {
            return 0;
        }
        if ($currentDailyExp->exp_gained + $experience >= $max) {
            $experience = $max - $currentDailyExp->exp_gained;
        }
        $currentDailyGroupExp->exp_gained += $experience;
        $currentDailyGroupExp->save();
        $currentDailyExp->exp_gained += $experience;
        $currentDailyExp->save();
        return $experience;
    }
}