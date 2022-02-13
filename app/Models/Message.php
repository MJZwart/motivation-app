<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'recipient_id',
        'message',
        'conversation_id',
    ];

    protected $touches = ['conversation'];

    public function sender() {
        return $this->belongsTo('App\Models\User');
    }

    public function recipient() {
        return $this->belongsTo('App\Models\User');
    }

    public function conversation() {
        return $this->belongsTo('App\Models\Conversation', 'conversation_id', 'conversation_id');
    }
}
