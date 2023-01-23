<?php

namespace App\Helpers;

use App\Models\Character;
use App\Models\ExperiencePoint;
use App\Models\Village;

class LevelHandler
{
    /**
     * Handles the experience gained by adding the points to the active reward, calculating and applying level ups
     * and creating the messages the user will see upon completion. Usable for both village and character
     *
     * @param string $type
     * @param Character|Village $activeReward
     * @param array $parsedRewards
     * @return object
     */
    public static function handleExperienceGained(string $type, Character|Village $activeReward, array $parsedRewards): object
    {
        $statExpArr = $type === 'CHARACTER' ? RewardEnums::CHAR_STAT_EXP_ARRAY : RewardEnums::VILL_STAT_EXP_ARRAY;
        $statArr = $type === 'CHARACTER' ? RewardEnums::CHAR_STAT_ARRAY : RewardEnums::VILL_STAT_ARRAY;
        $rewardAsArr = $activeReward->toArray();

        $coinsEarned = 0;
        foreach ($statExpArr as $value) {
            if ($value === 'coins') $coinsEarned = $parsedRewards[$value];
            $rewardAsArr[$value] += $parsedRewards[$value];
        }
        $levelupMessages = LevelHandler::checkAndApplyLevelUp(strtolower($type), $statExpArr, $statArr, $activeReward, $rewardAsArr);

        $returnMessages = new \stdClass();
        if (!empty($levelupMessages)) {
            foreach ($levelupMessages as $key => $levelupMessages) {
                $returnMessages->$key = $levelupMessages;
            }
        }
        $returnMessages->coinsEarned =  $coinsEarned;
        $returnMessages->success = __('messages.task.completed');
        $returnValue = new \stdClass();
        $returnValue->activeReward = $activeReward->fresh(); //Add the newly levelled character or village on the return value
        $returnValue->message = $returnMessages; //As well as the messages for the user.
        return $returnValue;
    }


    /**
     * Checks if the given active reward has earned enough experience to level up for each stat type and applies this level up if so.
     * For each level gained, create a level up message to return to the user
     * 
     * @param string $type
     * @param array $statExpArr
     * @param array $statArr
     * @param Character|Village $activeReward
     * @param array $rewardAsArr
     * @return array
     */
    public static function checkAndApplyLevelUp(string $type, array $statExpArr, array $statArr, Character|Village $activeReward, array $rewardAsArr)
    {
        $messages = [];
        $maxLevel = ExperiencePoint::max('level');
        for ($i = 0; $i < count($statArr); $i++) {
            $expNeeded = ExperiencePoint::getCurrentOrMaxExp($rewardAsArr[$statArr[$i]], $maxLevel); //Gets the amount of exp needed to level up with the current level
            while ($rewardAsArr[$statExpArr[$i]] > $expNeeded) { //While the exp owned is higher than the exp needed to level up:
                $rewardAsArr[$statArr[$i]]++; //Increase level
                $rewardAsArr[$statExpArr[$i]] -= $expNeeded; //Subtract the exp needed to level
                $expNeeded = ExperiencePoint::getCurrentOrMaxExp($rewardAsArr[$statArr[$i]], $maxLevel); //Recheck the experience needed after leveling up
                if ($statArr[$i] !== 'level') { //Add messages to an array to give back to the user, letting them know they levelled up.
                    array_push($messages, __('messages.reward.level.'.$type.'.statup', ['stat' => $statArr[$i], 'level' => $rewardAsArr[$statArr[$i]]]));
                } else {
                    if ($rewardAsArr[$statArr[$i]] % 5 == 0) 
                        TimelineHandler::addLevelUpToTimeline($activeReward->name, $activeReward->user_id, $rewardAsArr[$statArr[$i]], $type);
                    array_push($messages, __('messages.reward.level.'.$type.'.levelup', ['level' => $rewardAsArr[$statArr[$i]]]));
                }
            }
        }
        $activeReward->update($rewardAsArr);
        return $messages;
    }
}
