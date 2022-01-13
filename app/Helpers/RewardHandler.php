<?php

namespace App\Helpers;

class RewardHandler {

    //Input is the type and difficulty of the task. After determining the balance, it calculates the experience points earned per stat by difficulty.
    public static function calculateReward($type, $difficulty, $rewardType){
        $balance = RewardHandler::calculateBalance($type, $rewardType);
        $experiencePoints = [];
        for($i = 0 ; $i < sizeof($balance) ; $i++){
            $experiencePoints[$i]= ($balance[$i] * $difficulty) * rand(10,50);
        }
        return RewardHandler::parseTypeRewards($experiencePoints, $rewardType);
    }

    //First determines the balance of the reward based on the type.
    public static function calculateBalance($type, $rewardType){
        $balance = [3, 3, 3, 3, 3, 20];     
        if($rewardType == 'CHARACTER') {    //Strength, agility, endurance, intelligence, charisma. 18 points total.
            switch($type){
                case RewardEnums::TYPEGENERIC:
                    break;
                case RewardEnums::TYPEPHYSICAL:
                    $balance = [6, 6, 4, 1, 1, 20];
                    break;
                case RewardEnums::TYPEMENTAL:
                    $balance = [2, 1, 3, 7, 5, 20];
                    break;
                case RewardEnums::TYPESOCIAL:
                    $balance = [2, 3, 2, 3, 8, 20];
                    break;
            } 
        } else if($rewardType == 'VILLAGE') {//Economy, Labour, Craft, Art, Community. 18 points total.
            switch($type){
                case RewardEnums::TYPEGENERIC:
                    break;
                case RewardEnums::TYPEPHYSICAL:
                    $balance = [1, 9, 4, 1, 3, 20];
                    break;
                case RewardEnums::TYPEMENTAL:
                    $balance = [6, 2, 6, 3, 1, 20];
                    break;
                case RewardEnums::TYPESOCIAL:
                    $balance = [3, 1, 1, 6, 7, 20];
                    break;
            }
        }
        return $balance;
    }

    //Parses the earned experience points into an associative array that can be handled by the controller.
    private static function parseTypeRewards($experiencePoints, $rewardType){
        if($rewardType == 'CHARACTER') {
            return [RewardEnums::STRENGTH_EXP => $experiencePoints[0], 
                RewardEnums::AGILITY_EXP => $experiencePoints[1],
                RewardEnums::ENDURANCE_EXP => $experiencePoints[2],
                RewardEnums::INTELLIGENCE_EXP => $experiencePoints[3],
                RewardEnums::CHARISMA_EXP => $experiencePoints[4],
                RewardEnums::EXPERIENCE => $experiencePoints[5]];
        } else if($rewardType == 'VILLAGE') {
            return [RewardEnums::ECONOMY_EXP => $experiencePoints[0], 
                RewardEnums::LABOUR_EXP => $experiencePoints[1],
                RewardEnums::CRAFT_EXP => $experiencePoints[2],
                RewardEnums::ART_EXP => $experiencePoints[3],
                RewardEnums::COMMUNITY_EXP => $experiencePoints[4],
                RewardEnums::EXPERIENCE => $experiencePoints[5]];
        }
    }

}