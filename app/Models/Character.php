<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\RewardHandler;
use App\Helpers\LevelHandler;
use App\Helpers\RewardEnums;
use App\Http\Resources\CharacterResource;
use App\Helpers\VariableHandler;

class Character extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id',
        'strength_exp',
        'agility_exp',
        'endurance_exp',
        'intelligence_exp',
        'charisma_exp',
        'experience',
        'strength',
        'agility',
        'endurance',
        'intelligence',
        'charisma',
        'level',
    ];

    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    /**
     * Applies a reward from a completed task to a character
     *
     * @param Task $task
     * @return Object
     */
    public function applyReward(Task $task){
        $parsedReward = RewardHandler::calculateReward($task->type, $task->difficulty, 'CHARACTER');
        if ($task->activeSubTasks() != null) {
            foreach ($task->activeSubTasks() as $subtask) {
                $parsedReward = $this->addParsedReward(
                    $parsedReward, 
                    RewardHandler::calculateReward($subtask->type, $subtask->difficulty, 'CHARACTER'));
            }
        }
        $returnValue = LevelHandler::addCharacterExperience($this->toArray(), $parsedReward);
        $this->update($returnValue->activeReward);
        $returnValue->activeReward = new CharacterResource($this);
        return $returnValue;
    }

    /**
     * In the case of subtasks, add the rewards for these to the initial parsedRewards
     *
     * @param Array $parsedReward
     * @param Array $newParsedReward
     * @return Array
     */
    private function addParsedReward($parsedReward, $newParsedReward) {
        for ($i=0; $i < count(RewardEnums::CHAR_STAT_EXP_ARRAY); $i++) { 
            $parsedReward[RewardEnums::CHAR_STAT_EXP_ARRAY[$i]] += $newParsedReward[RewardEnums::CHAR_STAT_EXP_ARRAY[$i]];
        }
        return $parsedReward;
    }
}
