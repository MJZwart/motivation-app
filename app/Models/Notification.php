<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'text',
        'user_id',
        'links',
        'link_active',
        'delete_links_on_action',
    ];

    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    public static function hasUnread(int $userId) {
        return Notification::where('user_id', $userId)->where('read', false)->count() > 0;
    }
}
