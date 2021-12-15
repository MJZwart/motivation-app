<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class LevelHandler {
    public const STRENGTH = "strength_exp";
    public const AGILITY = "agility_exp";
    public const ENDURANCE = "endurance_exp";
    public const INTELLIGENCE = "intelligence_exp";
    public const CHARISMA = "charisma_exp";
    public const EXPERIENCE = "experience";

    public const STAT_EXP_ARRAY = [RewardHandler::STRENGTH_EXP, RewardHandler::AGILITY_EXP, RewardHandler::ENDURANCE_EXP, RewardHandler::INTELLIGENCE_EXP, RewardHandler::CHARISMA_EXP, RewardHandler::EXPERIENCE];
    public const STAT_ARRAY = [RewardHandler::STRENGTH, RewardHandler::AGILITY, RewardHandler::ENDURANCE, RewardHandler::INTELLIGENCE, RewardHandler::CHARISMA, RewardHandler::LEVEL];

    public static function addExperience($character, $parsedRewards){
        forEach(LevelHandler::STAT_EXP_ARRAY as $value){
            $character[$value] += $parsedRewards[$value];
        }
        return LevelHandler::checkLevelUp($character);
    }

    public static function checkLevelUp($character){
        $messages = [];
        $experienceTable =  DB::table('experience_points')->get();
        for($i = 0 ; $i < count(LevelHandler::STAT_ARRAY) ; $i++){
            $expNeeded = $experienceTable->firstWhere('level', $character[LevelHandler::STAT_ARRAY[$i]])->experience_points;
            while($character[LevelHandler::STAT_EXP_ARRAY[$i]] > $expNeeded){ //While the exp owned is higher than the exp needed to level up:
                $character[LevelHandler::STAT_ARRAY[$i]]++; //Increase level
                $character[LevelHandler::STAT_EXP_ARRAY[$i]] -= $expNeeded; //Subtract the exp needed to level
                $expNeeded = $experienceTable->firstWhere('level', $character[LevelHandler::STAT_ARRAY[$i]])->experience_points; //Recheck the experience needed after leveling up
                if(LevelHandler::STAT_ARRAY[$i] !== 'level'){ //Add messages to an array to give back to the user, letting them know they levelled up.
                    array_push($messages, 'Your '.LevelHandler::STAT_ARRAY[$i].' is now level '.$character[LevelHandler::STAT_ARRAY[$i]].'!');
                } else {
                    array_push($messages, 'Your character is now level '.$character[LevelHandler::STAT_ARRAY[$i]].'!');
                }
            }
        }
        return LevelHandler::parseReturnValues($character, $messages);
    }

    public static function parseReturnValues($character, $messages){
        $returnMessage = new \stdClass();
        if(!empty($messages)){
            foreach($messages as $key=>$message){
                $returnMessage->$key = (array)$message;
            }
        }
        $returnMessage->success = ['Task completed.'];
        $returnValue = new \stdClass();
        $returnValue->character = $character; //Add the newly levelled character on the return value
        $returnValue->message = $returnMessage; //As well as the messages for the user.
        return $returnValue;
    }
}