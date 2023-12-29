<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RepeatableTaskCompleted extends Model
{
    public $table = "repeatable_tasks_completed";
    public $timestamps = false;

    protected $fillable = ['user_id', 'task_id'];

    public function task() 
    {
        return $this->belongsTo(Task::class);
    }
}
