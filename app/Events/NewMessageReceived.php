<?php

namespace App\Events;

use App\Http\Resources\MessageResource;
use App\Models\Message;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NewMessageReceived implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $userId;
    protected $message;

    /**
     * Create a new event instance.
     */
    public function __construct(int $userId, Message $message)
    {
        Log::info('Constructing');
        $this->userId = $userId;
        $this->message = $message;
        Log::info('Constructed');
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        Log::info('Broadcast on');
        return [
            new PrivateChannel('messages.'.$this->userId),
        ];
    }

    public function broadcastWith()
    {
        Log::info('Broadcast with');
        return [
            'message' => new MessageResource($this->message),
        ];
    }
}
