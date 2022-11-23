<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActionTracking extends Model
{
    use HasFactory;

    protected $table = 'action_tracking';

    public $timestamps = false;

    protected $fillable = [
        'user_agent',
        'ip_address',
        'user_id',
        'action_type',
        'action',
    ];

    protected $with = ['user'];

    public function user() {
        return $this->belongsTo('App\Models\User');
    }
}
