<?php

namespace App\Helpers;

use App\Helpers\VariableHandler;

class RewardHandler {

    /**
     * Calculates and returns the reward that is ready for update
     *
     * @param String $type
     * @param Integer $difficulty
     * @param String $rewardType
     * @return Array
     */
    public static function calculateReward($type, $difficulty, $rewardType) {
        if($rewardType == 'CHARACTER') {
            $balance = VariableHandler::getCharacterExpGain($type);
            return RewardHandler::parseCharacterReward($balance, $difficulty);
        } else if($rewardType == 'VILLAGE') {
            $balance = VariableHandler::getVillageExpGain($type);
            return RewardHandler::parseVillageReward($balance, $difficulty);
        }
    }

    /**
     * Parses the reward for a character to be injected into a character for update
     *
     * @param Object $balance
     * @param Integer $difficulty
     * @return Array
     */
    public static function parseCharacterReward($balance, $difficulty) {
        return [RewardEnums::STRENGTH_EXP => ($balance->strength * $difficulty) * rand(5, 20), 
                RewardEnums::AGILITY_EXP => ($balance->agility * $difficulty) * rand(5, 20),
                RewardEnums::ENDURANCE_EXP => ($balance->endurance * $difficulty) * rand(5, 20),
                RewardEnums::INTELLIGENCE_EXP => ($balance->intelligence * $difficulty) * rand(5, 20),
                RewardEnums::CHARISMA_EXP => ($balance->charisma * $difficulty) * rand(5, 20),
                RewardEnums::EXPERIENCE => ($balance->level * $difficulty) * rand(5, 20)];
    }

    /**
     * Parses the reward for a village to be injected into a village for update
     *
     * @param Object $balance
     * @param Integer $difficulty
     * @return Array
     */
    public static function parseVillageReward($balance, $difficulty) {
        return [RewardEnums::ECONOMY_EXP => ($balance->strength * $difficulty) * rand(5, 20), 
                RewardEnums::LABOUR_EXP => ($balance->agility * $difficulty) * rand(5, 20),
                RewardEnums::CRAFT_EXP => ($balance->endurance * $difficulty) * rand(5, 20),
                RewardEnums::ART_EXP => ($balance->intelligence * $difficulty) * rand(5, 20),
                RewardEnums::COMMUNITY_EXP => ($balance->charisma * $difficulty) * rand(5, 20),
                RewardEnums::EXPERIENCE => ($balance->level * $difficulty) * rand(5, 20)];
    }

}