<?php

namespace App\Helpers;

use App\Models\Achievement;
use App\Models\Notification;
use App\Models\AchievementEarned;
use App\Models\TimelineAction;

class AchievementHandler
{

    /**
     * Collects the information needed to check if the user is eligible for an achievement,
     * given the data in the params, and redirects this data to the appropriate function
     *
     * @param String $type
     * @param User $user
     */
    public static function checkForAchievement($type, $user)
    {
        $amount = null;
        switch ($type) {
            case 'TASKS_MADE':
                $amount = $user->getTotalTasksMade();
                break;
            case 'TASKS_COMPLETED':
                $amount = $user->getTotalTasksCompleted();
                break;
            case 'REPEATABLE_COMPLETED':
                $amount = $user->getRepeatableTaskMostCompleted()->total;
                break;
            case 'FRIENDS':
                $amount = $user->friends()->count();
                break;
        }
        AchievementHandler::checkAchievementEligible($user, $type, $amount);
    }

    /**
     * Checks if there are any achievements to be earned given the parameters
     * If there are any achievements found, check if the user has already earned this in the past
     * If the user is eligible and hasn't earned the achievement yet, award the achievement.
     *
     * @param User $user
     * @param String $type
     * @param Integer $amount
     */
    public static function checkAchievementEligible($user, $type, $amount)
    {
        $achievements = Achievement::where('trigger_type', $type)->where('trigger_amount', '<=', $amount)->get();
        if ($achievements) {
            foreach ($achievements as $achievement) {
                if (!AchievementHandler::checkIfAchievementEarned($user, $achievement)) {
                    AchievementHandler::awardAchievement($user, $achievement);
                }
            }
        }
    }

    /**
     * Checks if the given user has already earned the given achievement
     *
     * @param User $user
     * @param Achievement $achievement
     * @return boolean
     */
    public static function checkIfAchievementEarned($user, $achievement)
    {
        return $user->achievements()->where('achievement_id', $achievement->id)->exists();
    }

    /**
     * Awards the given user the given achievement
     * Sends the given user a notification
     *
     * @param User $user
     * @param Achievement $achievement
     */
    public static function awardAchievement($user, $achievement)
    {
        AchievementEarned::create(['user_id' => $user->id, 'achievement_id' => $achievement->id]);
        TimelineHandler::addAchievementToTimeline($achievement, $user->id);
        AchievementHandler::sendAwardNotification($user, $achievement);
    }

    /**
     * Sends the given user a notification that they have earned the given achievement
     * 
     * @param User $user
     * @param Achievement $achievement
     */
    public static function sendAwardNotification($user, $achievement)
    {
        $title = "New achievement!";
        $text = "You have earned the achievement " . $achievement->name . ": " . $achievement->trigger_description;
        Notification::create(['user_id' => $user->id, 'title' => $title, 'text' => $text]);
    }
}
