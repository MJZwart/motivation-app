<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Village extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'user_id',
        'economy_exp',
        'labour_exp',
        'craft_exp',
        'art_exp',
        'community_exp',
        'experience',
        'economy',
        'labour',
        'craft',
        'art',
        'community',
        'level',
        'coins',
    ];

    public function user(){
        return $this->belongsTo('App\Models\User');
    }
}
