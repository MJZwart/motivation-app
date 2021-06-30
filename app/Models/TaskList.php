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

    //This doesn't work. The repeatable active cannot yet be compared to today's date.
    public function activeTasks(){
        return $this->tasks->filter(function ($value, $key) {
            dd($value);
                return $value->super_task_id == null 
                    && $value->completed == false
                    //&& $value->repeatable_active < Carbon::now()->toDateTimeString()
                    ;});
        // return Task::where('super_task_id', null)
        //     ->where('completed', false)
        //     ->where('task_list_id', $this->id)
        //     ->where('repeatable_active', '<', Carbon::now())
        //     ->get();
    }

    public function user(){
        return $this->belongsTo('App\Models\User');
    }
}
