<?php

namespace App\Helpers;

use App\Http\Resources\CharacterResource;
use App\Http\Resources\VillageResource;
use App\Helpers\VillageHandler;
use App\Helpers\CharacterHandler;

class RewardObjectHandler {

    public static function createNewObjectAndActivate(String $type, int $userId, String $objectName){
        if($type == 'VILLAGE') {
            return new VillageResource(VillageHandler::createNewVillageAndActivate($userId, $objectName));
        } else if($type == 'CHARACTER') {
            return new CharacterResource(CharacterHandler::createNewCharacterAndActivate($userId, $objectName));
        } else {
            return null;
        }
    }

    public static function getActiveRewardObjectResourceByUser(String $type, int $userId) {
        if($type == 'VILLAGE') {
            return new VillageResource(VillageHandler::findActiveVillage($userId));
        } else if($type == 'CHARACTER') {
            return new CharacterResource(CharacterHandler::findActiveCharacter($userId));
        } else {
            return null;
        }
    }

    public static function getActiveRewardObjectByUser(String $type, int $userId) {
        if($type == 'VILLAGE') {
            return VillageHandler::findActiveVillage($userId);
        } else if($type == 'CHARACTER') {
            return CharacterHandler::findActiveCharacter($userId);
        } else {
            return null;
        }
    }

    public static function toggleActiveRewardObject(String $type, int $userId, int $oldInstance) {
        if($type == 'VILLAGE') {
            return new VillageResource(VillageHandler::toggleVillageActive($userId, $oldInstance));
        } else if ($type == 'CHARACTER') {
            return new CharacterResource(CharacterHandler::toggleCharacterActive($userId, $oldInstance));
        } else {
            return null;
        }
    }

    public static function updateActiveReward(String $type, $activeRewardId, $newName) {
        if($type == 'VILLAGE') {
            return new VillageResource(VillageHandler::updateActiveVillage($activeRewardId, $newName));
        } else if($type == 'CHARACTER') {
            return new CharacterResource(CharacterHandler::updateActiveCharacter($activeRewardId, $newName));
        }
    }
}