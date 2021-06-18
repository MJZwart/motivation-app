<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'trigger_type',
        'trigger_amount',
        'image',
    ];

    //TODO Untested public key
    public function users(){
        return $this->belongsToMany('App\Models\User', 'achievements_earned');
    }
}
