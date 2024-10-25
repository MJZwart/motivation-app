<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\RepeatableTaskCompleted;
use Illuminate\Support\Facades\DB;

class Task extends Model
{
    use HasFactory;

    protected $with = ['subTasks'];

    protected $fillable = [
        'user_id',
        'task_list_id',
        'difficulty',
        'type',
        'description',
        'name',
        'super_task_id',
        'repeatable',
        'repeatable_active',
        'repeatable_reset_day',
    ];

    public function taskList()
    {
        return $this->belongsTo('App\Models\TaskList');
    }

    public function superTask()
    {
        return $this->belongsTo('App\Models\Task', 'super_task_id');
    }

    public function subTasks()
    {
        return $this->hasMany('App\Models\Task', 'super_task_id', 'id');
    }

    public function activeSubTasks()
    {
        return $this->subTasks->filter(function ($value, $key) {
            return $value->completed == null
                && $value->repeatable_active <= Carbon::now();
        });
    }

    public function resetDays()
    {
        return DB::table('tasks_reset_days')->where('task_id', $this->id)->orderBy('day')->pluck('day');
    }

    public function isActive()
    {
        return $this->completed == null && $this->repeatable_active <= Carbon::now();
    }
}
