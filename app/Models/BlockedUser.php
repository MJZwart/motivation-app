<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlockedUser extends Model
{
    protected $table = 'blocklist';
    use HasFactory;

    protected $fillable = [
        'user_id',
        'blocked_user_id',
    ];

    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    public function blockedUser() {
        return $this->belongsTo('App\Models\User', 'blocked_user_id');
    }
}
