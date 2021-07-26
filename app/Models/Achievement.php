<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AchievementTrigger;

class Achievement extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'trigger_type',
        'trigger_amount',
        'trigger_description',
        'image',
    ];

    //TODO Untested public key
    public function users(){
        return $this->belongsToMany('App\Models\User', 'achievements_earned');
    }

    public function parseTrigger($amount, $type){
        $trigger = AchievementTrigger::where('trigger_type', $type)->first();
        $plural = $amount > 1 ? 's': '';
        return sprintf($trigger->trigger_description, $amount, $plural);
    }
}
