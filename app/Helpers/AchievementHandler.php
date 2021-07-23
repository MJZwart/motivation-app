<?php

namespace App\Helpers;

use App\Models\Achievement;

class AchievementHandler {

    public function checkForAchievement($type, $user){
        switch($type){
            case 'TASKS_MADE':
                $this->checkTasksMadeAchi($user);
                break;
            case 'TASKS_COMPLETED':
                $this->checkTasksCompletedAchi($user);
                break;
            case 'REPEATABLE_COMPLETED':
                $this->checkRepeatableCompletedAchi($user);
                break;
            case 'FRIENDS':
                $this->checkFriendsAchi($user);
                break;
        }
    }

    private function checkTasksMadeAchi($user){
        $amount = $user->getTotalTasksMade();
        $achievement = Achievement::where('trigger_type', 'TASKS_MADE')->where('trigger_amount', $amount)->first();
        if(!$this->checkIfAchievementEarned($user, $achievement)){
            $this->awardAchievement($user, $achievement);
        }
    }

    private function checkTasksCompletedAchi($user){
        
    }

    private function checkRepeatableCompletedAchi($user){
        
    }

    private function checkFriendsAchi($user){
        
    }

    private function checkIfAchievementEarned($user, $achievement){
        return $user->achievements->contains('achievement_id', $achievement->id))
    }

    private function awardAchievement($user, $achievement){
        //TODO award achievement using AchievementEarned model?
    }

}