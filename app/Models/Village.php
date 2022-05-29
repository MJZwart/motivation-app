<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\RewardHandler;
use App\Helpers\LevelHandler;
use App\Helpers\RewardEnums;
use App\Http\Resources\VillageResource;
use App\Helpers\VariableHandler;
use App\Models\ExperiencePoint;

class Village extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'user_id',
        'economy_exp',
        'labour_exp',
        'craft_exp',
        'art_exp',
        'community_exp',
        'experience',
        'economy',
        'labour',
        'craft',
        'art',
        'community',
        'level',
    ];

    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    /**
     * Applies a reward from a completed task to a village
     *
     * @param Task $task
     * @return Object
     */
    public function applyReward(Task $task){
        $parsedReward = RewardHandler::calculateReward($task->type, $task->difficulty, 'VILLAGE');
        if ($task->activeSubTasks() != null) {
            foreach ($task->activeSubTasks() as $subtask) {
                $parsedReward = $this->addParsedReward(
                    $parsedReward, 
                    RewardHandler::calculateReward($subtask->type, $subtask->difficulty, 'VILLAGE'));
            }
        }
        $returnValue = LevelHandler::addVillageExperience($this->toArray(), $parsedReward);
        $this->update($returnValue->activeReward);
        $returnValue->activeReward = new VillageResource($this);
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
        for ($i=0; $i < count(RewardEnums::VILL_STAT_EXP_ARRAY); $i++) { 
            $parsedReward[RewardEnums::VILL_STAT_EXP_ARRAY[$i]] += $newParsedReward[RewardEnums::VILL_STAT_EXP_ARRAY[$i]];
        }
        return $parsedReward;
    }
}
