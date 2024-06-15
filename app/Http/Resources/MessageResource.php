<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'message' => $this->message,
            'created_at' => $this->created_at,
            'read' => $this->read,
            'sent_by_user' => $this->user_id === $this->sender_id,
            'sender' => new StrippedUserResource($this->sender),
            'conversation_id' => $this->conversation->conversation_id,
        ];
    }
}
