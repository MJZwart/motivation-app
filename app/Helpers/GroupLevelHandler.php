<?php

namespace App\Helpers;

use App\Models\ExperiencePoint;
use App\Models\GlobalSetting;
use App\Models\Group;
use App\Models\GroupExpDaily;
use App\Models\GroupExperiencePoint;
use App\Models\GroupUser;
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
        foreach($user->groupMemberships as $groupUser) {
            $group = $groupUser->group;
            // Calculates experience points
            $experience = rand(50, 150) * $taskDifficulty; //TODO make this into a dynamically adjustable range
            // Fetches today's contribution by this user to this group
            $userContributionToday = self::fetchGroupUserDailyContribution($groupUser);
            // Adjusts experience gained if max has been reached
            $experience = self::checkForMaxExp($experience, $userContributionToday->exp_gained);
            if ($experience === 0) continue;
            // Apply experience to group and user exp tracking
            GroupLevelHandler::applyExperienceAndCheckLevel($group, $experience);
            self::registerGroupUserExpEarned($groupUser, $experience);
            $userContributionToday->exp_gained += $experience;
            $userContributionToday->save();
            self::registerDailyGroupExp($groupUser, $experience);
        }
    }

    private static function fetchGroupUserDailyContribution(GroupUser $groupUser) {
        $today = Carbon::now()->toDateString();
        // Fetches today's user contribution to this group
        $userContributionToday = GroupUserDailyExp::where('date', $today)
            ->where('group_id', $groupUser->group_id)
            ->where('group_user_id', $groupUser->id)
            ->first();
        error_log('Searching with group id ' . $groupUser->group_id . ' and group user id ' . $groupUser->id . ' and date ' . $today);
        // error_log($userContributionToday->exp_gained);
        if ($userContributionToday === null) {
            error_log('Making new daily');
            $userContributionToday = new GroupUserDailyExp([
                'group_id' => $groupUser->group_id,
                'group_user_id' => $groupUser->id,
                'exp_gained' => 0,
                'date' => $today,
            ]);
        }
        return $userContributionToday;
    }

    private static function checkForMaxExp(int $experience, int $currentDailyExp)
    {
        // Fetches the current max
        $max = GlobalSetting::where('key', GlobalSetting::MAX_GROUP_EXP)->first()->value;
        // Check if current user's contribution is already at or above max (should never be above)
        if ($currentDailyExp >= $max) {
            return 0;
        }
        // Check if user's contribution would reach max when experience is applies and lowers exp accordingly
        if ($currentDailyExp + $experience >= $max) {
            $experience = $max - $currentDailyExp;
        }
        return $experience;
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
    private static function registerGroupUserExpEarned(GroupUser $groupUser, int $experience) {
        // Fetches total contribution, creates it if not present, then updates
        $totalExpRow = GroupUserExp::where('group_user_id', $groupUser->id)->where('group_id', $groupUser->group_id)->first();
        if($totalExpRow === null) {
            $totalExpRow = new GroupUserExp([
                'group_user_id' => $groupUser->id,
                'group_id' => $groupUser->group_id,
                'exp_gained' => 0,
            ]);
        }
        $totalExpRow->exp_gained += $experience;
        $totalExpRow->save();
    }

    /**
     * Check if this group has already reached max daily exp gained and adjusts experience accordingly
     */
    private static function registerDailyGroupExp(GroupUser $groupUser, int $experience)
    {
        $today = Carbon::now()->toDateString();
        // Fetches today's group total contribution
        $currentDailyGroupExp = GroupExpDaily::where('date', $today)->where('group_id', $groupUser->group_id)->first();
        // If the item doesn't exist, make it
        if ($currentDailyGroupExp === null) {
            $currentDailyGroupExp = new GroupExpDaily([
                'group_id' => $groupUser->group_id,
                'exp_gained' => 0,
                'date' => $today,
            ]);
        }
        // Saves changes to daily contribution
        $currentDailyGroupExp->exp_gained += $experience;
        $currentDailyGroupExp->save();
    }
}