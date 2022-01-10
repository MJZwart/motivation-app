<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\RewardHandler;
use App\Helpers\LevelHandler;
use App\Http\Resources\VillageResource;
use App\Helpers\VariableHandler;

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
    public function experienceTable(){
        return VariableHandler::getExperienceTable();
    }

    /**
     * Applies a reward from a completed task to a village
     *
     * @param Task $task
     * @return Object
     */
    public function applyReward(Task $task){
        $parsedReward = RewardHandler::calculateReward($task->type, $task->difficulty, 'VILLAGE');
        $returnValue = LevelHandler::addVillageExperience($this->toArray(), $parsedReward);
        $this->update($returnValue->activeReward);
        $returnValue->activeReward = new VillageResource($this);
        return $returnValue;
    }
}
