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
}
