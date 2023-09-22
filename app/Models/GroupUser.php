<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class GroupUser extends Pivot
{
    use HasFactory;

    public $timestamps = false;

    protected $with = ['user'];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function group()
    {
        return $this->belongsTo('App\Models\Group');
    }

    public function role()
    {
        return $this->belongsTo('App\Models\GroupRole', 'rank', 'id');
    }

    public function groupUserDailyExp()
    {
        return $this->hasMany(GroupUserDailyExp::class, 'group_user_id', 'id');
    }

    public function expContributionToday() 
    {
        $recordToday = $this->groupUserDailyExp->where('date', Carbon::now()->toDateString())->first();
        return $recordToday ? $recordToday->exp_gained : 0;
    }

    public function expContributionTotal()
    {
        return $this->groupUserDailyExp->sum('exp_gained');
    }
}
