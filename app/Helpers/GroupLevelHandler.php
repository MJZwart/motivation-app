<?php

namespace App\Helpers;

use App\Models\ExperiencePoint;
use App\Models\Group;
use App\Models\GroupExperiencePoint;
use App\Models\User;

class GroupLevelHandler
{
    public static function applyExperienceToGroups(User $user, int $taskDifficulty) {
        foreach($user->groups as $group) {
            $experience = rand(50, 150) * $taskDifficulty; //TODO make this into a dynamically adjustable range
            GroupLevelHandler::applyExperienceAndCheckLevel($group, $experience);
            // Mark that this experience has been added by this user, on this day
        }
    }

    public static function applyExperienceAndCheckLevel(Group $group, int $experience) {
        // Check if max experience has been applied that day #751
        // if (expEarned > maxExp) {
        //      return;
        // }
        $group->experience += $experience;
        $experienceForLevel = GroupExperiencePoint::where('level', $group->level)->first()->experience_points;
        while ($group->experience > $experienceForLevel) {
            $group->level++;
            $group->experience -= $experienceForLevel;
            $experienceForLevel = ExperiencePoint::where('level', $group->level)->first()->experience_points;
        }
        $group->save();
    }
}