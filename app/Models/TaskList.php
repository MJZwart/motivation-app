<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Task;
use Carbon\Carbon;

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

    public function activeTasks(){
        return $this->tasks->filter(function ($value, $key) {
                return $value->super_task_id == null 
                    && $value->completed == null
                    && $value->repeatable_active <= Carbon::now()
                    ;});
    }

    public function user(){
        return $this->belongsTo('App\Models\User');
    }
}
