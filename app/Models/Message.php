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

    public static function hasUnread(int $userId) {
        return Message::where('recipient_id', $userId)->where('user_id', $userId)->where('read', false)->count() > 0;
    }

    /**
     * @return Message Message that the receiver gets so it can be broadcasted.
     */
    public static function createNewMessage(array $messageContent) {
        $sentMessage = Message::create([...$messageContent, 'read' => true, 'user_id' => $messageContent['sender_id']]);
        $receivedMessage = Message::create([...$messageContent, 'user_id' => $messageContent['recipient_id']]);
        $sentMessage->conversation->touch();
        $receivedMessage->conversation->touch();
        return $receivedMessage;
    }
}
