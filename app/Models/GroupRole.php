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
        'owner',
        'member',
        'group_id',
    ];
    
    public function group()
    {
        return $this->belongsTo('App\Models\Group');
    }
}
