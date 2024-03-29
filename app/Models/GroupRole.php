<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupRole extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'can_edit',
        'can_manage_members',
        'can_moderate_messages',
        'owner',
        'member',
        'group_id',
        'position',
    ];
    
    public function group()
    {
        return $this->belongsTo('App\Models\Group');
    }
}
