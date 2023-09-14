<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GlobalSetting extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'key',
        'value',
    ];

    // Current keys
    public const MAX_GROUP_EXP = 'group_max_exp_per_day';
}
