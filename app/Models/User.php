<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Task;
use App\Models\GroupApplication;
use App\Helpers\RewardObjectHandler;
use App\Http\Resources\BannedUserResource;
use Illuminate\Support\Facades\DB;
use App\Models\RepeatableTaskCompleted;
use App\Models\ReportedUser;
use Carbon\Carbon;

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
        'show_reward',
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

    public function characters(){
        return $this->hasMany('App\Models\Character');
    }

    public function villages(){
        return $this->hasMany('App\Models\Village');
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

    public function bannedUser() {
        return $this->hasMany('App\Models\BannedUser');
    }

    public function groups(){
        return $this->belongsToMany('App\Models\Group')
            ->withPivot(['rank'])
            ->withPivot(['joined']);
    }

    public function applications() {
        return $this->belongsToMany('App\Models\Group', 'group_applications')
            ->using('App\Models\GroupApplication')
            ->withPivot(['applied_at']);
    }
    
    public function blockedUsers() {
        return $this->belongsToMany('App\Models\User', 'blocklist', 'user_id', 'blocked_user_id')->withPivot('id');;
    }

    public function isBlocked($userId) {
        $user = User::find($userId);
        return $user->blockedUsers->contains('id', $this->id);
    }

    public function getActiveRewardObjectResource(){
        return RewardObjectHandler::getActiveRewardObjectResourceByUser($this->rewards, $this->id);
    }
    public function getActiveRewardObject(){
        return RewardObjectHandler::getActiveRewardObjectByUser($this->rewards, $this->id);
    }

    public function getReports() {
        return ReportedUser::where('reported_user_id', $this->id)->get();
    }

    public function isReported():bool{
        return $this->getReports()->isNotEmpty();
    }

    public function getLatestReport(){
        return $this->getReports()->sortByDesc('created_at')->first();
    }
    public function reports() {
        return $this->hasMany('App\Models\ReportedUser', 'reported_user_id');
    }
    public function latestReportDate() {
        return $this->reports->sortByDesc('created_at')->first()->created_at;
    }

    public function bannedFromGroups() {
        return $this->belongsToMany('App\Models\Group', 'group_bans')
            ->withTimestamps();
    }

    public function bannedGroupIds() {
        return $this->bannedFromGroups()->select('group_id')->get()->map(function($element) {
            return $element->group_id;
        })->toArray();
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

    public function getVisibleConversations() {
        $allConversations = Conversation::with(['messages', 'messages.sender', 'messages.recipient'])
            ->where('user_id', $this->id)->orderBy('updated_at', 'desc')->get();
        return $allConversations->filter(function ($value, $key) {
            return $value->getLastMessage();
        });
    }

    /**
     * Checks if the user is suspended by matching the 'banned_until' date-time with the current date-time
     *
     * @return boolean
     */
    public function isBanned() {
        $currentDate = Carbon::now();
        if (!$this->banned_until) return false;
        if ($this->banned_until > $currentDate) return true;
        else return false;
    }
    
    /**
     * Gets the BannedUser instance if it exists and wraps in a resource. Otherwise return null.
     */
    public function getBannedUserResource() {
        $bannedUser = $this->bannedUser;
        if (count($bannedUser) < 1)
            return null;
        return BannedUserResource::collection($bannedUser);
    }
}
