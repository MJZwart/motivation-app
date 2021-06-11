<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Friend extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id_1',
        'user_id_2',
    ];

    //TODO Untested foreign key
    public function user1(){
        return $this->belongsTo('App\Models\User', 'user_id_1');
    }

    //TODO Untested foreign key
    public function user2(){
        return $this->belongsTo('App\Models\User', 'user_id_2');
    }
}
