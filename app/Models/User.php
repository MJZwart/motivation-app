<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

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
        'full_display_name',
        'display_picture',
        'rewards',
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

    public function taskLists(){
        return $this->hasMany('App\Models\TaskList');
    }

    public function tasks(){
        return $this->hasMany('App\Models\Task');
    }

    public function achievements(){
        return $this->belongsToMany('App\Models\Achievement', 'achievements_earned');
    }

    public function friends(){
        return $this->belongsToMany('App\Models\Friend', 'friends');
    }
}
