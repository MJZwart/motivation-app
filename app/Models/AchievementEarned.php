<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AchievementEarned extends Model
{
    protected $table = 'achievements_earned';

    protected $fillable = ['user_id', 'achievement_id'];
    public $timestamps = false;

    public function achievement(){
        return $this->belongsTo('App\Models\Achievement');
    }

    public function user(){
        return $this->belongsTo('App\Models\User');
    }
}
