<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
    ];

    public function user(){
        return $this->belongsTo('App\Models\User');
    }
    public function experienceTable(){
        return DB::table('experience_points')->get();
    }
}
