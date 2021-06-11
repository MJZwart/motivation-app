<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskList extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'color',
    ];

    public function tasks(){
        return $this->hasMany('App\Models\Task');
    }

    public function user(){
        return $this->belongsTo('App\Models\User');
    }
}
