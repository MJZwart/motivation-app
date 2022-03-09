<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'is_public',
    ];

    public function users() {
        return $this->belongsToMany('App\Models\User')
            ->withPivot(['rank'])
            ->withPivot(['joined']);
    }

    public function isAdmin(User $user):bool {
        return $this->users->find($user->id)->pivot->rank == 'admin';
    }

    public function rankOfMember(User $user):string {
        return $this->users->find($user->id)->pivot->rank;
    }
}
