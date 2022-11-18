<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuspendedUser extends Model
{
    protected $table = 'suspended_users';
    use HasFactory;

    protected $fillable = [
        'user_id',
        'admin_id',
        'reason',
        'days',
        'suspended_until',
    ];

    protected $with = ['admin', 'user'];

    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    public function admin() {
        return $this->belongsTo('App\Models\User', 'admin_id');
    }
}
