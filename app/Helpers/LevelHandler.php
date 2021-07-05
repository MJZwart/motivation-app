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

    public static function addExperience($character, $parsedRewards){
        $character->strength_exp += $parsedRewards[RewardHandler::STRENGTH];
        $character->agility_exp += $parsedRewards[RewardHandler::AGILITY];
        $character->endurance_exp += $parsedRewards[RewardHandler::ENDURANCE];
        $character->intelligence_exp += $parsedRewards[RewardHandler::INTELLIGENCE];
        $character->charisma_exp += $parsedRewards[RewardHandler::CHARISMA];
        $character->experience += $parsedRewards[RewardHandler::EXPERIENCE];
        LevelHandler::checkLevelUp($character);
    }

    public static function checkLevelUp($character){
        $output = new \Symfony\Component\Console\Output\ConsoleOutput();
        $experienceTable =  DB::table('experience_points')->get();
        $experienceNeeded = $experienceTable->firstWhere('level', $character->level)->experience_points;
        LevelHandler::checkLevel($character, $experienceTable);
        $experienceNeeded = $experienceTable->firstWhere('level', $character->strength)->experience_points;
        if($character->strength_exp > $experienceNeeded){
            $character->strength++;
            $character->strength_exp -= $experienceNeeded;
        }
        $experienceNeeded = $experienceTable->firstWhere('level', $character->agility)->experience_points;
        if($character->agility_exp > $experienceNeeded){
            $character->agility++;
            $character->agility_exp -= $experienceNeeded;
        }
        $experienceNeeded = $experienceTable->firstWhere('level', $character->endurance)->experience_points;
        if($character->endurance_exp > $experienceNeeded){
            $character->endurance++;
            $character->endurance_exp -= $experienceNeeded;
        }
        $experienceNeeded = $experienceTable->firstWhere('level', $character->intelligence)->experience_points;
        if($character->intelligence_exp > $experienceNeeded){
            $character->intelligence++;
            $character->intelligence_exp -= $experienceNeeded;
        }
        $experienceNeeded = $experienceTable->firstWhere('level', $character->charisma)->experience_points;
        if($character->charisma_exp > $experienceNeeded){
            $character->charisma++;
            $character->charisma_exp -= $experienceNeeded;
        }
        $character->update();
    }

    //Check level more than just once, in case of massive exp increase.
    private static function checkLevel($character, $experienceTable){
        if($character->experience > $experienceNeeded){
            $output->writeln("<info>Experience needed: ".$experienceNeeded." . Experience having: ".$character->experience.".</info>");
            $character->level++;
            $character->experience -= $experienceNeeded;
            $output->writeln("<info>New level: ".$character->level." . Experience having: ".$character->experience.".</info>");
            $character->update();
        }
        
    }

}