<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BugReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'page',
        'type',
        'severity',
        'user_id',
        'image_link',
        'comment',
    ];
}
