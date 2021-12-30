<?php

namespace App\Helpers;

class RewardHandler {
    public const TYPEGENERIC = 1;
    public const TYPEPHYSICAL = 2;
    public const TYPEMENTAL = 3;
    public const TYPESOCIAL = 4;

    public const STRENGTH_EXP = "strength_exp";
    public const AGILITY_EXP = "agility_exp";
    public const ENDURANCE_EXP = "endurance_exp";
    public const INTELLIGENCE_EXP = "intelligence_exp";
    public const CHARISMA_EXP = "charisma_exp";
    public const EXPERIENCE = "experience";

    public const STRENGTH = "strength";
    public const AGILITY = "agility";
    public const ENDURANCE = "endurance";
    public const INTELLIGENCE = "intelligence";
    public const CHARISMA = "charisma";
    public const LEVEL = "level";

    //Input is the type and difficulty of the task. After determining the balance, it calculates the experience points earned per stat by difficulty.
    public static function calculateReward($type, $difficulty){
        $balance = RewardHandler::calculateBalance($type);
        $experiencePoints = [];
        for($i = 0 ; $i < sizeof($balance) ; $i++){
            $experiencePoints[$i]= ($balance[$i] * $difficulty) * rand(10,50);
        }
        return RewardHandler::parseTypeRewards($experiencePoints);
    }

    //First determines the balance of the reward based on the type.
    public static function calculateBalance($type){
        $balance = [3, 3, 3, 3, 3, 20]; //Strength, agility, endurance, intelligence, charisma. 18 points total.
        switch($type){
            case RewardHandler::TYPEGENERIC:
                break;
            case RewardHandler::TYPEPHYSICAL:
                $balance = [6, 6, 4, 1, 1, 20];
                break;
            case RewardHandler::TYPEMENTAL:
                $balance = [2, 1, 3, 7, 5, 20];
                break;
            case RewardHandler::TYPESOCIAL:
                $balance = [2, 3, 2, 3, 8, 20];
                break;
        }
        return $balance;
    }
    //First determines the balance of the reward based on the type.
    public static function calculateVillageBalance($type){
        $balance = [3, 3, 3, 3, 3, 20]; //Economy, Labour, Craft, Art, Community. 18 points total.
        switch($type){
            case RewardHandler::TYPEGENERIC:
                break;
            case RewardHandler::TYPEPHYSICAL:
                $balance = [6, 6, 4, 1, 1, 20];
                break;
            case RewardHandler::TYPEMENTAL:
                $balance = [2, 1, 3, 7, 5, 20];
                break;
            case RewardHandler::TYPESOCIAL:
                $balance = [2, 3, 2, 3, 8, 20];
                break;
        }
        return $balance;
    }

    //Parses the earned experience points into an associative array that can be handled by the controller.
    private static function parseTypeRewards($experiencePoints){
        return [RewardHandler::STRENGTH_EXP => $experiencePoints[0], 
            RewardHandler::AGILITY_EXP => $experiencePoints[1],
            RewardHandler::ENDURANCE_EXP => $experiencePoints[2],
            RewardHandler::INTELLIGENCE_EXP => $experiencePoints[3],
            RewardHandler::CHARISMA_EXP => $experiencePoints[4],
            RewardHandler::EXPERIENCE => $experiencePoints[5]];
    }

}