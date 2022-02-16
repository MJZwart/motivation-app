<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MessageOutResource extends JsonResource
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
            'sent_by_user' => true,
            'recipient' => new StrippedUserResource($this->recipient),
            'message' => $this->message,
            'visible' => $this->visible_to_sender,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
