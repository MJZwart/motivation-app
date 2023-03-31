<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'sender_id',
        'recipient_id',
        'message',
        'conversation_id',
        'created_at',
        'updated_at',
        'read',
    ];

    protected $touches = ['conversation'];

    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    public function sender() {
        return $this->belongsTo('App\Models\User');
    }

    public function recipient() {
        return $this->belongsTo('App\Models\User');
    }

    public function conversation() {
        return $this->belongsTo('App\Models\Conversation', 'conversation_id', 'conversation_id');
    }

    public static function createNewMessage(array $messageContent) {
        $sentMessage = Message::create([
            'user_id' => $messageContent['user_id'],
            'sender_id' => $messageContent['user_id'],
            'recipient_id' => $messageContent['recipient_id'],
            'conversation_id' => $messageContent['conversation_id'],
            'message' => $messageContent['message'],
        ]);
        $receivedMessage = Message::create([
            'user_id' => $messageContent['recipient_id'],
            'sender_id' => $messageContent['user_id'],
            'recipient_id' => $messageContent['recipient_id'],
            'conversation_id' => $messageContent['conversation_id'],
            'message' => $messageContent['message'],
        ]);
        $sentMessage->conversation->touch();
        $receivedMessage->conversation->touch();
    }
}
