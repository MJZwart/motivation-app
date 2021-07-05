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
        $experienceTable =  DB::table('experience_points')->get();
        for($i = 0 ; $i < count(LevelHandler::STAT_ARRAY) ; $i++){
            $expNeeded = $experienceTable->firstWhere('level', $character[LevelHandler::STAT_ARRAY[$i]])->experience_points;
            while($character[LevelHandler::STAT_EXP_ARRAY[$i]] > $expNeeded){
                $character[LevelHandler::STAT_ARRAY[$i]]++;
                $character[LevelHandler::STAT_EXP_ARRAY[$i]] -= $expNeeded;
                $expNeeded = $experienceTable->firstWhere('level', $character[LevelHandler::STAT_ARRAY[$i]])->experience_points;
            }
        }
        return $character;
    }
}