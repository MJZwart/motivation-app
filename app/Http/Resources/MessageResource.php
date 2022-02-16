<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    protected $user_id;

    public function setUserId($user_id) {
        $this->user_id = $user_id;
        return $this;
    }
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $resourceData = [
            'id' => $this->id,
            'message' => $this->message,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
        if($this->user_id == $this->sender->id && $this->visible_to_sender) {
            $resourceData['sent_by_user'] = true;
            $resourceData['recipient'] = new StrippedUserResource($this->recipient);
            $resourceData['visible'] = $this->visible_to_sender;
        } else if($this->user_id == $this->recipient->id && $this->visible_to_recipient) {
            $resourceData['sent_by_user'] = false;
            $resourceData['sender'] = new StrippedUserResource($this->sender);
            $resourceData['read'] = $this->read;
            $resourceData['visible'] = $this->visible_to_recipient;
        }
        return $resourceData;
    }

    public static function collection($resource) {
        return new MessageResourceCollection($resource);
    }
}
