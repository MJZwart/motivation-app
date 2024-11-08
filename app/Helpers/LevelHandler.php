<?php

namespace App\Helpers;

use App\Models\ExperiencePoint;
use App\Models\Village;

class LevelHandler
{
    /**
     * Handles the experience gained by adding the points to the active village, calculating and applying level ups
     * and creating the messages the user will see upon completion.
     */
    public static function handleExperienceGained(Village $activeVillage, array $parsedRewards): object
    {
        $villageAsArray = $activeVillage->toArray();

        $coinsEarned = 0;
        foreach (RewardEnums::STAT_EXP_ARRAY as $value) {
            if ($value === 'coins') $coinsEarned = $parsedRewards[$value];
            $villageAsArray[$value] += $parsedRewards[$value];
        }
        $levelupMessages = LevelHandler::checkAndApplyLevelUp(RewardEnums::STAT_EXP_ARRAY, RewardEnums::STAT_ARRAY, $activeVillage, $villageAsArray);

        $returnMessages = new \stdClass();
        if (!empty($levelupMessages)) {
            foreach ($levelupMessages as $key => $levelupMessages) {
                $returnMessages->$key = $levelupMessages;
            }
        }
        $returnMessages->coinsEarned =  $coinsEarned;
        $returnMessages->success = __('messages.task.completed');
        $returnValue = new \stdClass();
        $returnValue->activeVillage = $activeVillage->fresh(); //Add the newly levelled village on the return value
        $returnValue->message = $returnMessages; //As well as the messages for the user.
        return $returnValue;
    }


    /**
     * Checks if the given active village has earned enough experience to level up for each stat type and applies this level up if so.
     * For each level gained, create a level up message to return to the user
     */
    public static function checkAndApplyLevelUp(array $statExpArr, array $statArr, Village $activeVillage, array $villageAsArray)
    {
        $messages = [];
        $maxLevel = ExperiencePoint::max('level');
        for ($i = 0; $i < count($statArr); $i++) {
            $expNeeded = ExperiencePoint::getCurrentOrMaxExp($villageAsArray[$statArr[$i]], $maxLevel); //Gets the amount of exp needed to level up with the current level
            while ($villageAsArray[$statExpArr[$i]] > $expNeeded) { //While the exp owned is higher than the exp needed to level up:
                $villageAsArray[$statArr[$i]]++; //Increase level
                $villageAsArray[$statExpArr[$i]] -= $expNeeded; //Subtract the exp needed to level
                $expNeeded = ExperiencePoint::getCurrentOrMaxExp($villageAsArray[$statArr[$i]], $maxLevel); //Recheck the experience needed after leveling up
                if ($statArr[$i] !== 'level') { //Add messages to an array to give back to the user, letting them know they levelled up.
                    array_push($messages, __('messages.reward.level.village.statup', ['stat' => $statArr[$i], 'level' => $villageAsArray[$statArr[$i]]]));
                } else {
                    if ($villageAsArray[$statArr[$i]] % 5 == 0)
                        TimelineHandler::addLevelUpToTimeline($activeVillage->name, $activeVillage->user_id, $villageAsArray[$statArr[$i]], 'village');
                    array_push($messages, __('messages.reward.level.village.levelup', ['level' => $villageAsArray[$statArr[$i]]]));
                }
            }
        }
        $activeVillage->update($villageAsArray);
        return $messages;
    }
}
