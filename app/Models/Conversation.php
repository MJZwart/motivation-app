<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Message;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'recipient_id',
        'conversation_id',
    ];

    protected $with = ['recipient', 'messages'];

    public function messages() {
        return $this->hasMany('App\Models\Message', 'conversation_id', 'conversation_id');
    }

    public function getMessages() {
        return Message::where('conversation_id', $this->conversation_id)->where('user_id', $this->user_id)->get();
    }

    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    public function recipient() {
        return $this->belongsTo('App\Models\User');
    }

    public function getLastMessage() {
        return $this->getMessages()->sortByDesc('created_at')->first();
    }

    public function messagesOut() {
        return $this->messages->where('sender_id', $this->user_id);
    }

    public function messagesIn() {
        return $this->messages->where('recipient_id', $this->user_id);
    }
}
