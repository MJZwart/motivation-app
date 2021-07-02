<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\RewardHandler;

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
    ];

    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    public function applyReward(Task $task){
        $difficultyModifier = $task->difficulty;
        $typeModifier = $task->type;
        $parsedReward = RewardHandler::calculateReward($typeModifier, $difficultyModifier);
        //This currently doesn't add the experience, rather overrides it. Change it.
        $this->update($parsedReward);
    }

    public function checkLevelUp($skill){
        //TODO
    }

    private function levelUp($skill){
        //TODO
    }
}
