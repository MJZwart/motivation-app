<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupUserExp extends Model
{
    public $table = 'group_user_exp';
    public $timestamps = false;

    protected $fillable = [
        'group_user_id',
        'group_id',
        'exp_gained',
    ];

    public function groupUser() {
        return $this->belongsTo('App\Models\GroupUser');
    }

    public function group() {
        return $this->belongsTo('App\Models\Group');
    }

    public function user() {
        return $this->groupUser->user;
    }
}
