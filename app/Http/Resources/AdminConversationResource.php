<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Message;
use App\Http\Resources\AdminMessageResource;

class AdminConversationResource extends JsonResource
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
            'id' => $this->conversation_id,
            'messages' => AdminMessageResource::collection(
                Message::where('conversation_id', $this->conversation_id)
                ->where('user_id', $this->user_id)
                ->get()),
        ];
    }
}
