<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportedUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'reported_user_id',
        'reported_by_user_id',
        'comment',
        'reason',
        'conversation_id',
    ];
}
