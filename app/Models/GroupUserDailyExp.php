<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupUserDailyExp extends Model
{
    public $table = 'group_user_daily_exp';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'group_id',
        'date',
    ];

    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    public function group() {
        return $this->belongsTo('App\Models\Group');
    }
}
