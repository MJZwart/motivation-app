<?php

namespace App\Helpers;

class RewardHandler {
    public const TYPEGENERIC = 1;
    public const TYPEPHYSICAL = 2;
    public const TYPEMENTAL = 3;
    public const TYPESOCIAL = 4;

    public const STRENGTH = "strength_exp";
    public const AGILITY = "agility_exp";
    public const ENDURANCE = "endurance_exp";
    public const INTELLIGENCE = "intelligence_exp";
    public const CHARISMA = "charisma_exp";
    public const EXPERIENCE = "experience";

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

    //Parses the earned experience points into an associative array that can be handled by the controller.
    private static function parseTypeRewards($experiencePoints){
        return [RewardHandler::STRENGTH => $experiencePoints[0], 
            RewardHandler::AGILITY => $experiencePoints[1],
            RewardHandler::ENDURANCE => $experiencePoints[2],
            RewardHandler::INTELLIGENCE => $experiencePoints[3],
            RewardHandler::CHARISMA => $experiencePoints[4],
            RewardHandler::EXPERIENCE => $experiencePoints[5]];
    }

}