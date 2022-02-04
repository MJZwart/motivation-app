<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MessageInResource extends JsonResource
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
            'sent_by_user' => false,
            'sender' => $this->sender,
            'message' => $this->message,
            'read' => $this->read,
            'visible' => $this->visible_to_recipient,
            'created_at' => $this->created_at,
        ];
    }
}
