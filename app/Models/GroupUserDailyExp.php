<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupUserDailyExp extends Model
{
    public $table = 'group_user_daily_exp';
    public $timestamps = false;

    protected $fillable = [
        'group_user_id',
        'group_id',
        'date',
        'exp_gained',
    ];

    public function groupUser() {
        return $this->belongsTo('App\Models\GroupUser');
    }

    public function group() {
        return $this->belongsTo('App\Models\Group');
    }
}
