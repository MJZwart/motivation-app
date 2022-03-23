<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\RewardHandler;
use App\Helpers\LevelHandler;
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
        $returnValue = LevelHandler::addVillageExperience($this->toArray(), $parsedReward);
        $this->update($returnValue->activeReward);
        $returnValue->activeReward = new VillageResource($this);
        return $returnValue;
    }

    public function expToLevel() {
        return ExperiencePoint::where('level', $this->economy)
            ->orWhere('level', $this->labour)
            ->orWhere('level', $this->craft)
            ->orWhere('level', $this->art)
            ->orWhere('level', $this->community)
            ->orWhere('level', $this->level)->get();
    }
}
