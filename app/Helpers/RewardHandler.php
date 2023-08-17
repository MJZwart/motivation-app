<?php

namespace App\Helpers;

use App\Helpers\VariableHandler;
use App\Http\Resources\CharacterResource;
use App\Http\Resources\VillageResource;
use App\Models\Task;
use App\Models\User;

class RewardHandler
{
    public static function handleTaskRewards(Task $task, User $user): object
    {
        $activeReward = $user->getActiveRewardObject();
        $rewardType = $user->rewards;

        GroupLevelHandler::applyExperienceToGroups($user, $task->difficulty);

        $parsedReward = RewardHandler::calculateReward($task->type, $task->difficulty, $rewardType);
        if ($task->activeSubTasks() != null) {
            foreach ($task->activeSubTasks() as $subtask) {
                $parsedReward = RewardHandler::addParsedReward(
                    $rewardType,
                    $parsedReward, 
                    RewardHandler::calculateReward($subtask->type, $subtask->difficulty, $rewardType));
            }
        }

        $rewardAndMessages = LevelHandler::handleExperienceGained($rewardType, $activeReward, $parsedReward);
        $rewardAndMessages->activeReward = $rewardType === 'CHARACTER' ? 
            new CharacterResource($rewardAndMessages->activeReward) : 
            new VillageResource($rewardAndMessages->activeReward);
        return $rewardAndMessages;
    }

    /**
     * Adds the calculated rewards to the existing array of experience and coins earned
     *
     * @param string $type
     * @param array $parsedReward
     * @param array $rewardsToBeAdded
     * @return array
     */
    public static function addParsedReward(string $type, array $parsedReward, array $rewardsToBeAdded): array
    {
        $statExpArray = $type === 'VILLAGE' ? RewardEnums::VILL_STAT_EXP_ARRAY : RewardEnums::CHAR_STAT_EXP_ARRAY;
        for ($i=0; $i < count($statExpArray); $i++) { 
            $parsedReward[$statExpArray[$i]] += $rewardsToBeAdded[$statExpArray[$i]];
        }
        return $parsedReward;
    }

    /**
     * Calculates and returns the reward that is ready for update
     *
     * @param String $type
     * @param int $difficulty
     * @param String $rewardType
     * @return array
     */
    public static function calculateReward($type, $difficulty, $rewardType): array
    {
        if ($rewardType == 'CHARACTER') {
            $balance = VariableHandler::getCharacterExpGain($type);
            return [
                RewardEnums::STRENGTH_EXP => ($balance->strength * $difficulty) * rand(5, 20),
                RewardEnums::AGILITY_EXP => ($balance->agility * $difficulty) * rand(5, 20),
                RewardEnums::ENDURANCE_EXP => ($balance->endurance * $difficulty) * rand(5, 20),
                RewardEnums::INTELLIGENCE_EXP => ($balance->intelligence * $difficulty) * rand(5, 20),
                RewardEnums::CHARISMA_EXP => ($balance->charisma * $difficulty) * rand(5, 20),
                RewardEnums::EXPERIENCE => ($balance->level * $difficulty) * rand(5, 20),
                RewardEnums::COINS => ($balance->coins * $difficulty) * rand(5, 20)
            ];
        } else if ($rewardType == 'VILLAGE') {
            $balance = VariableHandler::getVillageExpGain($type);
            return [
                RewardEnums::ECONOMY_EXP => ($balance->economy * $difficulty) * rand(5, 20),
                RewardEnums::LABOUR_EXP => ($balance->labour * $difficulty) * rand(5, 20),
                RewardEnums::CRAFT_EXP => ($balance->craft * $difficulty) * rand(5, 20),
                RewardEnums::ART_EXP => ($balance->art * $difficulty) * rand(5, 20),
                RewardEnums::COMMUNITY_EXP => ($balance->community * $difficulty) * rand(5, 20),
                RewardEnums::EXPERIENCE => ($balance->level * $difficulty) * rand(5, 20),
                RewardEnums::COINS => ($balance->coins * $difficulty) * rand(5, 20)
            ];
        }
    }
}
