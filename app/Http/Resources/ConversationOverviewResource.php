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
            'last_message' => MessageResource::make($this->getLastMessage())->setUserId($this->user_id),
            'messages' => MessageResource::collection($this->visibleMessages()->sortByDesc('created_at'))->setUserId($this->user_id),
            'updated_at' => $this->updated_at,
            'is_blocked' => $this->user->hasBlockedUser($this->recipient_id),
        ];
    }
}
