<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\UserProfileResource;
use App\Http\Resources\StatsResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function show(User $user){
        return new UserProfileResource($user);
    }

    public function showStats(){
        return new StatsResource(Auth::user());
    }

    public function isAdmin() {
        return Auth::user()->admin;
    }
}
