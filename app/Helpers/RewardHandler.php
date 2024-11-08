<?php

namespace App\Helpers;

use App\Helpers\VariableHandler;
use App\Http\Resources\VillageResource;
use App\Models\Task;
use App\Models\User;

class RewardHandler
{
    public static function handleTaskRewards(Task $task, User $user): object
    {
        $activeVillage = $user->getActiveVillageObject();

        GroupLevelHandler::applyExperienceToGroups($user, $task->difficulty);

        $parsedReward = RewardHandler::calculateReward($task->type, $task->difficulty);
        if ($task->activeSubTasks() != null) {
            foreach ($task->activeSubTasks() as $subtask) {
                $parsedReward = RewardHandler::addParsedReward(
                    $parsedReward,
                    RewardHandler::calculateReward($subtask->type, $subtask->difficulty)
                );
            }
        }

        $rewardAndMessages = LevelHandler::handleExperienceGained($activeVillage, $parsedReward);
        $rewardAndMessages->activeVillage = new VillageResource($rewardAndMessages->activeVillage);
        return $rewardAndMessages;
    }

    /**
     * Adds the calculated rewards to the existing array of experience and coins earned
     */
    public static function addParsedReward(array $parsedReward, array $rewardsToBeAdded): array
    {
        $statExpArray = RewardEnums::STAT_EXP_ARRAY;
        for ($i = 0; $i < count($statExpArray); $i++) {
            $parsedReward[$statExpArray[$i]] += $rewardsToBeAdded[$statExpArray[$i]];
        }
        return $parsedReward;
    }

    /**
     * Calculates and returns the reward that is ready for update
     */
    public static function calculateReward(string $type, int $difficulty): array
    {
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
