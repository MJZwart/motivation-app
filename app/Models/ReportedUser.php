<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

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

    protected $with = ['user', 'reporter'];

    public function getReportedUser() {
        return User::find($this->reported_user_id);
    }

    public function user() {
        return $this->belongsTo('App\Models\User', 'reported_user_id');
    }

    public function reporter() {
        return $this->belongsTo('App\Models\User', 'reported_by_user_id');
    }
}
