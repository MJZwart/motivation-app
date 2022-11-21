<?php

namespace App\Helpers;

use App\Models\ExperiencePoint;

class LevelHandler
{
    /**
     * Adds experience to a given character, using the given parsed rewards in RewardHandler
     * Sends the data to the checkLevelUp function to further check and return information
     *
     * @param Character $character
     * @param Array $parsedRewards
     * @return Object
     */
    public static function addCharacterExperience($character, $parsedRewards)
    {
        $coinsEarned = 0;
        foreach (RewardEnums::CHAR_STAT_EXP_ARRAY as $value) {
            if ($value === 'coins') $coinsEarned = $parsedRewards[$value];
            $character[$value] += $parsedRewards[$value];
        }
        return LevelHandler::checkCharacterLevelUp($character, $coinsEarned);
    }

    /**
     * Checks if a given character can level up any of their skills or level with the updated experience points
     * Iterates over all stats a character has, checking each if their current experience is higher than the experience needed for levelup
     * If the experience is higher, it lowers the current experience by experience needed for that level and adds one level
     * In the case of a level up, push a message into an array of messages
     * Repeat until there is no more level up available
     * If a player has reached 'max level' as they exist in the database, instead pick the experience needed for levelup from
     * the highest existing level, and uses that value instead, allowing for infinite levels (up to max int)
     * Parses the messages and returns the value
     *
     * @param Character $character
     * @return Object
     */
    public static function checkCharacterLevelUp($character, $coinsEarned)
    {
        $messages = [];
        $maxLevel = ExperiencePoint::max('level');
        for ($i = 0; $i < count(RewardEnums::CHAR_STAT_ARRAY); $i++) {
            $expNeeded = ExperiencePoint::getCurrentOrMaxExp($character[RewardEnums::CHAR_STAT_ARRAY[$i]], $maxLevel); //Gets the amount of exp needed to level up with the current level
            while ($character[RewardEnums::CHAR_STAT_EXP_ARRAY[$i]] > $expNeeded) { //While the exp owned is higher than the exp needed to level up:
                $character[RewardEnums::CHAR_STAT_ARRAY[$i]]++; //Increase level
                $character[RewardEnums::CHAR_STAT_EXP_ARRAY[$i]] -= $expNeeded; //Subtract the exp needed to level
                $expNeeded = ExperiencePoint::getCurrentOrMaxExp($character[RewardEnums::CHAR_STAT_ARRAY[$i]], $maxLevel); //Recheck the experience needed after leveling up
                if (RewardEnums::CHAR_STAT_ARRAY[$i] !== 'level') { //Add messages to an array to give back to the user, letting them know they levelled up.
                    array_push($messages, __('messages.reward.level.character.statup', ['stat' => RewardEnums::CHAR_STAT_ARRAY[$i], 'level' => $character[RewardEnums::CHAR_STAT_ARRAY[$i]]]));
                } else {
                    array_push($messages, __('messages.reward.level.character.levelup', ['level' => $character[RewardEnums::CHAR_STAT_ARRAY[$i]]]));
                }
            }
        }
        return LevelHandler::parseReturnValues($character, $messages, $coinsEarned);
    }

    /**
     * Adds experience to a given village, using the given parsed rewards in RewardHandler
     * Sends the data to the checkLevelUp function to further check and return information
     *
     * @param Village $village
     * @param Array $parsedRewards
     * @return Object
     */
    public static function addVillageExperience($village, $parsedRewards)
    {
        $coinsEarned = 0;
        foreach (RewardEnums::VILL_STAT_EXP_ARRAY as $value) {
            if ($value === 'coins') $coinsEarned = $parsedRewards[$value];
            $village[$value] += $parsedRewards[$value];
        }
        return LevelHandler::checkVillageLevelUp($village, $coinsEarned);
    }

    /**
     * Checks if a given village can level up any of their skills or level with the updated experience points
     * Iterates over all stats a village has, checking each if their current experience is higher than the experience needed for levelup
     * If the experience is higher, it lowers the current experience by experience needed for that level and adds one level
     * In the case of a level up, push a message into an array of messages
     * Repeat until there is no more level up available
     * Parses the messages and returns the value
     *
     * @param Village $village
     * @return Object
     */
    public static function checkVillageLevelUp($village, $coinsEarned)
    {
        $messages = [];
        $experienceTable =  ExperiencePoint::get();
        for ($i = 0; $i < count(RewardEnums::VILL_STAT_ARRAY); $i++) {
            $expNeeded = $experienceTable->firstWhere('level', $village[RewardEnums::VILL_STAT_ARRAY[$i]])->experience_points;
            while ($village[RewardEnums::VILL_STAT_EXP_ARRAY[$i]] > $expNeeded) { //While the exp owned is higher than the exp needed to level up:
                $village[RewardEnums::VILL_STAT_ARRAY[$i]]++; //Increase level
                $village[RewardEnums::VILL_STAT_EXP_ARRAY[$i]] -= $expNeeded; //Subtract the exp needed to level
                $expNeeded = $experienceTable->firstWhere('level', $village[RewardEnums::VILL_STAT_ARRAY[$i]])->experience_points; //Recheck the experience needed after leveling up
                if (RewardEnums::VILL_STAT_ARRAY[$i] !== 'level') { //Add messages to an array to give back to the user, letting them know they levelled up.
                    array_push($messages, __('messages.reward.level.village.statup', ['stat' => RewardEnums::VILL_STAT_ARRAY[$i], 'level' => $village[RewardEnums::VILL_STAT_ARRAY[$i]]]));
                } else {
                    array_push($messages, __('messages.reward.level.village.levelup', ['level' => $village[RewardEnums::VILL_STAT_ARRAY[$i]]]));
                }
            }
        }
        return LevelHandler::parseReturnValues($village, $messages, $coinsEarned);
    }

    /**
     * Parses the messages and values to return to the user after the rewards have been applied.
     * Adds a message that the task has been completed, as well as all messages in case of level up
     * Adds the handled object and returns all in an object
     *
     * @param [Character | Village] $activeReward
     * @param Array $messages
     * @return Object
     */
    public static function parseReturnValues($activeReward, $messages, $coinsEarned)
    {
        $returnMessage = new \stdClass();
        if (!empty($messages)) {
            foreach ($messages as $key => $message) {
                $returnMessage->$key = $message;
            }
        }
        $returnMessage->coinsEarned = __('messages.reward.coinsEarned', ['coins'=> $coinsEarned]);
        $returnMessage->success = __('messages.task.completed');
        $returnValue = new \stdClass();
        $returnValue->activeReward = $activeReward; //Add the newly levelled character or village on the return value
        $returnValue->message = $returnMessage; //As well as the messages for the user.
        return $returnValue;
    }
}
