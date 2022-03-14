<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

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

    public function isAdminById(int $id):bool {
        return $this->users()->find($id)->pivot->rank == 'admin';
    }

    public function rankOfMemberById(int $id):string {
        return $this->users->find($id)->pivot->rank;
    }

    public function findLoggedUser() {
        return $this->users->where('id', Auth::user()->id)->first();
    }

    public function getAdmin() {
        return $this->users->where('pivot.rank', 'admin')->first();
    }
}
