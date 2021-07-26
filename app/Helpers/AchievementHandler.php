<?php

namespace App\Helpers;

use App\Models\Achievement;
use App\Models\Notification;
use App\Models\AchievementEarned;

class AchievementHandler {

    public static function checkForAchievement($type, $user){
        switch($type){
            case 'TASKS_MADE':
                AchievementHandler::checkTasksMadeAchi($user);
                break;
            case 'TASKS_COMPLETED':
                return AchievementHandler::checkTasksCompletedAchi($user);
                break;
            case 'REPEATABLE_COMPLETED':
                return AchievementHandler::checkRepeatableCompletedAchi($user);
                break;
            case 'FRIENDS':
                return AchievementHandler::checkFriendsAchi($user);
                break;
        }
    }

    public static function checkTasksMadeAchi($user){
        $amount = $user->getTotalTasksMade();
        $achievement = Achievement::where('trigger_type', 'TASKS_MADE')->where('trigger_amount', $amount)->first();
        if(!AchievementHandler::checkIfAchievementEarned($user, $achievement)){
            AchievementHandler::awardAchievement($user, $achievement);
        }
    }

    public static function checkTasksCompletedAchi($user){
        
    }

    public static function checkRepeatableCompletedAchi($user){
        
    }

    public static function checkFriendsAchi($user){
        
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