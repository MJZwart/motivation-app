<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupUserExp extends Model
{
    protected $fillable = [
        'user_id',
        'group_id',
    ];

    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    public function group() {
        return $this->belongsTo('App\Models\Group');
    }
}
