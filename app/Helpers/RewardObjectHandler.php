<?php

namespace App\Helpers;

use App\Http\Resources\CharacterResource;
use App\Http\Resources\VillageResource;
use App\Helpers\VillageHandler;
use App\Helpers\CharacterHandler;
use App\Models\User;

class RewardObjectHandler
{

    /**
     * Separates by type and further handles the reward settings
     *
     * @param User $user
     * @param [String | int] $keepOldInstance
     * @param [String | null] $rewardObjectName
     * @param String $rewardType
     * @return [Village | Character]
     */
    public static function changeRewardSettings(User $user, $keepOldInstance, $rewardObjectName, String $rewardType)
    {
        if ($rewardType == 'NONE') {
            return RewardObjectHandler::deactivateAllRewardObjects($user);
        } else {
            if ($keepOldInstance == 'NEW') {
                return RewardObjectHandler::createNewObjectAndActivate($rewardType, $user, $rewardObjectName);
            } else if (is_numeric($keepOldInstance)) {
                return RewardObjectHandler::toggleActiveRewardObject($rewardType, $user, $keepOldInstance);
            }
        }
    }

    public static function deactivateAllRewardObjects(User $user)
    {
        CharacterHandler::deactivateAllCharacters($user);
        VillageHandler::deactivateAllVillages($user);
    }

    public static function createNewObjectAndActivate(String $type, User $user, String $objectName)
    {
        if ($type == 'VILLAGE') {
            CharacterHandler::deactivateAllCharacters($user);
            return new VillageResource(VillageHandler::createNewVillageAndActivate($user->id, $objectName));
        } else if ($type == 'CHARACTER') {
            VillageHandler::deactivateAllVillages($user);
            return new CharacterResource(CharacterHandler::createNewCharacterAndActivate($user->id, $objectName));
        } else {
            return null;
        }
    }

    public static function getActiveRewardObjectResourceByUser(String $type, int $userId)
    {
        if ($type == 'VILLAGE') {
            return new VillageResource(VillageHandler::findActiveVillage($userId));
        } else if ($type == 'CHARACTER') {
            return new CharacterResource(CharacterHandler::findActiveCharacter($userId));
        } else {
            return null;
        }
    }

    public static function getActiveRewardObjectByUser(String $type, int $userId)
    {
        if ($type == 'VILLAGE') {
            return VillageHandler::findActiveVillage($userId);
        } else if ($type == 'CHARACTER') {
            return CharacterHandler::findActiveCharacter($userId);
        } else {
            return null;
        }
    }

    public static function toggleActiveRewardObject(String $type, User $user, int $oldInstance)
    {
        if ($type == 'VILLAGE') {
            CharacterHandler::deactivateAllCharacters($user);
            return new VillageResource(VillageHandler::toggleVillageActive($user->id, $oldInstance));
        } else if ($type == 'CHARACTER') {
            VillageHandler::deactivateAllVillages($user);
            return new CharacterResource(CharacterHandler::toggleCharacterActive($user->id, $oldInstance));
        } else {
            return null;
        }
    }

    public static function updateActiveReward(String $type, $activeRewardId, $newName)
    {
        if ($type == 'VILLAGE') {
            return new VillageResource(VillageHandler::updateActiveVillage($activeRewardId, $newName));
        } else if ($type == 'CHARACTER') {
            return new CharacterResource(CharacterHandler::updateActiveCharacter($activeRewardId, $newName));
        }
    }

    public static function deleteRewardObject(String $type, $rewardId)
    {
        if ($type == 'VILLAGE') {
            VillageHandler::deleteVillage($rewardId);
        } else if ($type == 'CHARACTER') {
            CharacterHandler::deleteCharacter($rewardId);
        }
    }
}
