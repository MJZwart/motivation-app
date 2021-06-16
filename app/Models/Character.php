<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Character extends Model
{
    use HasFactory;

    protected $fillable = [
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

    public function checkLevelUp($skill){
        //TODO
    }

    private function levelUp($skill){
        //TODO
    }
}
