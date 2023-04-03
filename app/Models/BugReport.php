<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BugReport extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'page',
        'type',
        'severity',
        'user_id',
        'image_link',
        'comment',
        'admin_comment',
        'status',
        'diagnostics',
    ];

    protected $with = ['user'];

    public function user() {
        return $this->belongsTo('App\Models\User');
    }
}
