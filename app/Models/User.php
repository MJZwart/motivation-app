<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Task;
use Illuminate\Support\Facades\DB;
use App\Models\RepeatableTaskCompleted;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'display_picture',
        'rewards',
        'show_achievements',
        'show_character',
        'show_friends',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $with = ['characters'];

    public function characters(){
        return $this->hasMany('App\Models\Character');
    }

    public function taskLists(){
        return $this->hasMany('App\Models\TaskList');
    }

    public function tasks(){
        return $this->hasMany('App\Models\Task');
    }

    public function achievements(){
        return $this->belongsToMany('App\Models\Achievement', 'achievements_earned')->withPivot('earned');
    }

    public function friends(){
        return $this->belongsToMany('App\Models\User', 'friends', 'user_id', 'friend_id')->where('accepted', true)->withTimestamps()->withPivot('id');
    }

    public function notifications(){
        return $this->hasMany('App\Models\Notification');
    }

    /**
     * Returns the total number of regular and repeatable tasks completed by a user
     */
    public function getTotalTasksCompleted(){
        $regularTasks = Task::where('user_id', $this->id)->where('completed', '!=', null)->count();
        $repeatableTasks = RepeatableTaskCompleted::where('user_id', $this->id)->count();
        return $regularTasks + $repeatableTasks;
    }

    /**
     * Finds the repeatable task that has been completed most often and returns the task
     */
    public function getRepeatableTaskMostCompleted(){
        $repeatable = RepeatableTaskCompleted::where('user_id', $this->id)
            ->select('task_id', DB::raw('count(*) as total'))
            ->groupBy('task_id')
            ->orderByDesc('total')
            ->first();
        if(!!$repeatable) {
            $repeatable->task_name = Task::find($repeatable->task_id)->name;
        }
        return $repeatable;
    }

    /**
     * Returns the amount of tasks the user has made, counting the active tasks + the completed tasks
     */
    public function getTotalTasksMade(){
        $completedTasks = Task::where('user_id', $this->id)->where('completed', '!=', null)->count();
        $activeTasks = Task::where('user_id', $this->id)->where('completed', null)->count();
        return $completedTasks + $activeTasks;
    }

    public function getActiveCharacter(){
        return Character::where('user_id', $this->id)->where('active', true)->first();
    }
}
