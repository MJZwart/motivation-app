<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'task_list_id',
        'difficulty',
        'type',
        'name',
        'super_task_id',
    ];

    public function taskList(){
        return $this->belongsTo('App\Models\TaskList');
    }

    //TODO Untested: Is the foreign key set properly?
    public function superTask(){
        return $this->belongsTo('App\Models\Task', 'super_task_id');
    }

    //TODO Untested: First key should be the foreign key, second one the local. Test if this is correct with seeder data.
    public function subTasks(){
        return $this->hasMany('App\Models\Task', 'super_task_id', 'id');
    }
}
