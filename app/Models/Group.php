<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
            ->withPivot(['is_admin'])
            ->withPivot(['joined']);
    }
}
