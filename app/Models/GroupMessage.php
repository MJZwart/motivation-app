<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupMessage extends Model
{
    use HasFactory;

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
