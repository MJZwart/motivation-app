<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'text',
        'user_id',
        'email',
    ];

    public function user() { //Inverse does not exist, currently no need for it
        return $this->belongsTo('App\Models\User');
    }
}
