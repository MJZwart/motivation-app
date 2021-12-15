<?php

namespace App\Helpers;

use App\Models\Achievement;
use App\Models\Notification;
use App\Models\AchievementEarned;

class AchievementHandler {

    public static function checkForAchievement($type, $user){
        $amount = null;
        switch($type){
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

    public static function checkAchievementEligible($user, $type, $amount){
        $achievements = Achievement::where('trigger_type', $type)->where('trigger_amount', '<=', $amount)->get();
        if($achievements){
            foreach($achievements as $achievement){
                if(!AchievementHandler::checkIfAchievementEarned($user, $achievement)){
                    AchievementHandler::awardAchievement($user, $achievement);
                }
            }
        }
    }

    public static function checkIfAchievementEarned($user, $achievement){
        return $user->achievements()->where('achievement_id', $achievement->id)->exists();
    }

    public static function awardAchievement($user, $achievement){
        AchievementEarned::create(['user_id' => $user->id, 'achievement_id' => $achievement->id]);
        AchievementHandler::sendAwardNotification($user, $achievement);
    }

    public static function sendAwardNotification($user, $achievement){
        $title = "New achievement!";
        $text = "You have earned the achievement " . $achievement->name . ": ". $achievement->trigger_description;
        Notification::create(['user_id' => $user->id, 'title' => $title, 'text' => $text]);
    }

}