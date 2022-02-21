<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Messages;

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

    public function visibleMessages() {
        return $this->messages->filter(function ($value, $key) {
            return ($this->user_id == $value->sender_id && $value->visible_to_sender) || ($this->user_id == $value->recipient_id && $value->visible_to_recipient);
        });
    }

    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    public function recipient() {
        return $this->belongsTo('App\Models\User');
    }

    public function getLastMessage() {
        //Message::where('conversation_id', $this->conversation_id)->order_by('created_at', 'desc')->first();
        error_log($this->visibleMessages()->sortByDesc('created_at')->first());
        return $this->visibleMessages()->sortByDesc('created_at')->first();
    }

    public function getLastMessageAsResource() {
        $message = $this->messages->order_by('created_at', 'desc')->first();
        if($message->sender_id == $this->user_id) {
            return new MessageOutResource($message);
        } else {
            return new MessageInResource($message);
        }
    }

    public function messagesOut() {
        return $this->messages->where('sender_id', $this->user_id);
    }

    public function messagesIn() {
        return $this->messages->where('recipient_id', $this->user_id);
    }
}
