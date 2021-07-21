<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'trigger_type',
        'trigger_amount',
        'image',
    ];

    //TODO Untested public key
    public function users(){
        return $this->belongsToMany('App\Models\User', 'achievements_earned');
    }

    public function parseTrigger($amount, $type){
        $typeParser = [
            'TASKS_MADE' => 'Create a task ',
            'TASKS_COMPLETED' => 'Complete a task ',
            'REPEATABLE_COMPLETED' => 'Completing a repeatable task ',
            'FRIENDS' => 'Adding a friend ',
        ];
        $amountParser = $amount == 1 ? 'once.' : $amount.' times.';
        return $typeParser[$type].$amountParser;
    }
}
