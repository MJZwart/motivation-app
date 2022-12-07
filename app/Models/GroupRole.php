<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupRole extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'can_delete',
        'can_edit',
        'can_manage_members',
        'owner',
        'member',
    ];
    
    public function group()
    {
        return $this->belongsTo('App\Models\Group');
    }
}
