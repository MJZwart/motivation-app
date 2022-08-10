<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
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

    protected $with = ['users', 'invites', 'applications'];

    public function users()
    {
        return $this->belongsToMany('App\Models\User')
            ->withPivot(['rank'])
            ->withPivot(['joined']);
    }

    public function applications()
    {
        return $this->belongsToMany('App\Models\User', 'group_applications')
            ->using('App\Models\GroupApplication')
            ->withPivot(['applied_at']);
    }

    public function bannedUsers()
    {
        return $this->belongsToMany('App\Models\User', 'group_bans')
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

    public function invitesAsId()
    {
        return $this->invites->map(function ($item, $key) {
            return $item->user_id;
        });
    }

    public function isAdminById(int $id): bool
    {
        return $this->users()->find($id)->pivot->rank == 'admin';
    }

    public function rankOfMemberById(int $id): string
    {
        return $this->users->find($id)->pivot->rank;
    }

    public function findLoggedUser()
    {
        return $this->users->where('id', Auth::user()->id)->first();
    }

    public function getAdmin()
    {
        return $this->users->where('pivot.rank', 'admin')->first();
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
