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
            'recipient' => new StrippedUserResource($this->recipient),
            'conversation_id' => $this->conversation_id,
            'last_message' => $this->getLastMessage()->sender == $this->user_id ? 
                new MessageOutResource($this->getLastMessage()) : 
                new MessageInResource($this->getLastMessage()),
        ];
    }
}
