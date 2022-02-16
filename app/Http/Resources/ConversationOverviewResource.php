<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ConversationOverviewResource extends JsonResource
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
            'recipient' => new StrippedUserResource($this->recipient),
            'conversation_id' => $this->conversation_id,
            'last_message' => $this->getLastMessage()->sender_id == $this->user_id ? 
                new MessageOutResource($this->getLastMessage()) : 
                new MessageInResource($this->getLastMessage()),
            'messages' => MessageResource::collection($this->visibleMessages()->sortByDesc('created_at'))->setUserId($this->user_id),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
