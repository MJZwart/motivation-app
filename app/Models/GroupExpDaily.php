<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupExpDaily extends Model
{
    public $timestamps = false;
    public $table = 'group_exp_daily';

    protected $fillable = [
        'group_id',
        'date',
        'exp_gained',
    ];

    public function group() {
        return $this->belongsTo('App\Models\Group');
    }
}
