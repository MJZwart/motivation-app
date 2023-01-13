<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimelineAction extends Model
{
    use HasFactory;
    
    public $timestamps = false;
    protected $table = 'timeline';

    protected $fillable = [
        'timestamp',
        'user_id',
        'type',
        'action',
        'params',
    ];
}
