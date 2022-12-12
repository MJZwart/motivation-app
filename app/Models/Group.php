<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\GroupUser;
use Illuminate\Support\Facades\Auth;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'is_public',
        'require_application',
    ];

    protected $with = ['users'];

    public function users()
    {
        return $this->belongsToMany('App\Models\User')
            ->withPivot(['rank'])
            ->withPivot(['joined']);
    }

    public function groupUsers() 
    {
        return $this->hasMany('App\Models\GroupUser');
    }

    public function applications()
    {
        return $this->hasMany('App\Models\GroupApplication');
    }

    public function suspendedUsers()
    {
        return $this->belongsToMany('App\Models\User', 'group_suspensions')
            ->withTimestamps();
    }

    public function hasUserApplied()
    {
        return $this->applications()
            ->where('user_id', Auth::user()->id)
            ->where('group_id', $this->id)
            ->exists();
    }

    public function invites()
    {
        return $this->hasMany('App\Models\GroupInvite');
    }

    public function roles()
    {
        return $this->hasMany('App\Models\GroupRole');
    }

    public function invitesAsId()
    {
        return $this->invites->map(function ($item, $key) {
            return $item->user_id;
        });
    }

    public function isAdminById(int $id): bool
    {
        return GroupRole::find($this->groupUsers->where('user_id', $id)->first()->rank)->owner;
    }

    public function rankOfMemberById(int $id): GroupRole
    {
        return GroupRole::find($this->groupUsers->where('user_id', $id)->first()->rank);
    }

    public function findLoggedUser()
    {
        return $this->users->where('id', Auth::user()->id)->first();
    }

    public function loggedUserRank()
    {
        $groupUser = $this->groupUsers->where('user_id', Auth::user()->id)->first();
        return $groupUser ? GroupRole::find($groupUser->rank) : null;
    }

    public function getAdmin()
    {
        $adminRole = $this->roles->where('owner', true)->first();
        if (!GroupUser::where('rank', $adminRole->id)->first()) dd($this);
        return GroupUser::where('rank', $adminRole->id)->first()->user;
    }

    public function hasMember(int $id)
    {
        return $this->users->contains('id', $id);
    }
    public function removeMemberFromGroup(int $id)
    {
        GroupUser::where('user_id', $id)->where('group_id', $this->id)->delete();
    }
}
