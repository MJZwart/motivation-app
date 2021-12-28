<?php

namespace App\Helpers;

use App\Models\Village;
use App\Models\Character;
use App\Helpers\VillageHandler;
use App\Helpers\CharacterHandler;

class RewardObjectHandler {


    public static function getActiveRewardObjectByUser(String $type, $userId) {
        if($type == 'VILLAGE') {
            return VillageHandler::findActiveVillage($userId);
        } else if($type == 'CHARACTER') {
            return CharacterHandler::findActiveCharacter($userId);
        } else {
            return null;
        }
    }
}