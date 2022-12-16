<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GroupMessage extends Model
{
    use HasFactory, SoftDeletes;

    public $fillable = [
        'message',
        'group_id',
        'user_id',
    ];

    public function group()
    {
        return $this->belongsTo('App\Models\Group');
    }
    
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
