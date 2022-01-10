<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use App\Http\Resources\CharacterResource;
use App\Http\Resources\VillageResource;

class LevelHandler {
    /**
     * Adds experience to a given character, using the given parsed rewards in RewardHandler
     * Sends the data to the checkLevelUp function to further check and return information
     *
     * @param Character $character
     * @param Array $parsedRewards
     * @return Object
     */
    public static function addCharacterExperience($character, $parsedRewards){
        forEach(RewardEnums::CHAR_STAT_EXP_ARRAY as $value){
            $character[$value] += $parsedRewards[$value];
        }
        return LevelHandler::checkCharacterLevelUp($character);
    }

    /**
     * Checks if a given character can level up any of their skills or level with the updated experience points
     * Iterates over all stats a character has, checking each if their current experience is higher than the experience needed for levelup
     * If the experience is higher, it lowers the current experience by experience needed for that level and adds one level
     * In the case of a level up, push a message into an array of messages
     * Repeat until there is no more level up available
     * Parses the messages and returns the value
     *
     * @param Character $character
     * @return void
     */
    public static function checkCharacterLevelUp($character){
        $messages = [];
        $experienceTable =  DB::table('experience_points')->get();
        for($i = 0 ; $i < count(RewardEnums::CHAR_STAT_ARRAY) ; $i++){
            $expNeeded = $experienceTable->firstWhere('level', $character[RewardEnums::CHAR_STAT_ARRAY[$i]])->experience_points;
            while($character[RewardEnums::CHAR_STAT_EXP_ARRAY[$i]] > $expNeeded){ //While the exp owned is higher than the exp needed to level up:
                $character[RewardEnums::CHAR_STAT_ARRAY[$i]]++; //Increase level
                $character[RewardEnums::CHAR_STAT_EXP_ARRAY[$i]] -= $expNeeded; //Subtract the exp needed to level
                $expNeeded = $experienceTable->firstWhere('level', $character[RewardEnums::CHAR_STAT_ARRAY[$i]])->experience_points; //Recheck the experience needed after leveling up
                if(RewardEnums::CHAR_STAT_ARRAY[$i] !== 'level'){ //Add messages to an array to give back to the user, letting them know they levelled up.
                    array_push($messages, 'Your '.RewardEnums::CHAR_STAT_ARRAY[$i].' is now level '.$character[RewardEnums::CHAR_STAT_ARRAY[$i]].'!');
                } else {
                    array_push($messages, 'Your character is now level '.$character[RewardEnums::CHAR_STAT_ARRAY[$i]].'!');
                }
            }
        }
        return LevelHandler::parseReturnValues($character, $messages, 'CHARACTER');
    }

    /**
     * Adds experience to a given village, using the given parsed rewards in RewardHandler
     * Sends the data to the checkLevelUp function to further check and return information
     *
     * @param Village $village
     * @param Array $parsedRewards
     * @return Object
     */
    public static function addVillageExperience($village, $parsedRewards){
        forEach(RewardEnums::VILL_STAT_EXP_ARRAY as $value){
            $village[$value] += $parsedRewards[$value];
        }
        return LevelHandler::checkVillageLevelUp($village);
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
     * @return void
     */
    public static function checkVillageLevelUp($village){
        $messages = [];
        $experienceTable =  DB::table('experience_points')->get();
        for($i = 0 ; $i < count(RewardEnums::VILL_STAT_ARRAY) ; $i++){
            $expNeeded = $experienceTable->firstWhere('level', $village[RewardEnums::VILL_STAT_ARRAY[$i]])->experience_points;
            while($village[RewardEnums::VILL_STAT_EXP_ARRAY[$i]] > $expNeeded){ //While the exp owned is higher than the exp needed to level up:
                $village[RewardEnums::VILL_STAT_ARRAY[$i]]++; //Increase level
                $village[RewardEnums::VILL_STAT_EXP_ARRAY[$i]] -= $expNeeded; //Subtract the exp needed to level
                $expNeeded = $experienceTable->firstWhere('level', $village[RewardEnums::VILL_STAT_ARRAY[$i]])->experience_points; //Recheck the experience needed after leveling up
                if(RewardEnums::VILL_STAT_ARRAY[$i] !== 'level'){ //Add messages to an array to give back to the user, letting them know they levelled up.
                    array_push($messages, 'Your '.RewardEnums::VILL_STAT_ARRAY[$i].' is now level '.$village[RewardEnums::VILL_STAT_ARRAY[$i]].'!');
                } else {
                    array_push($messages, 'Your village is now level '.$village[RewardEnums::VILL_STAT_ARRAY[$i]].'!');
                }
            }
        }
        return LevelHandler::parseReturnValues($village, $messages, 'VILLAGE');
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
    public static function parseReturnValues($activeReward, $messages, $type){
        $returnMessage = new \stdClass();
        if(!empty($messages)){
            foreach($messages as $key=>$message){
                $returnMessage->$key = (array)$message;
            }
        }
        $returnMessage->success = ['Task completed.'];
        $returnValue = new \stdClass();
        if($type == 'CHARACTER') {
            $returnValue->activeReward = $activeReward; //Add the newly levelled character on the return value
        } else if($type = 'VILLAGE') {
            $returnValue->activeReward = $activeReward; //Add the newly levelled village on the return value
        }
        $returnValue->message = $returnMessage; //As well as the messages for the user.
        return $returnValue;
    }
}