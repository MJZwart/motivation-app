<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'coins',
    ];

    public function user(){
        return $this->belongsTo('App\Models\User');
    }
}
