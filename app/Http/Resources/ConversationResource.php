<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\StrippedUserResource;

class ConversationResource extends JsonResource
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
            'messages_in' => MessageInResource::collection($this->messagesIn()),
            'messages_out' => MessageOutResource::collection($this->messagesOut()),
        ];
    }
}
