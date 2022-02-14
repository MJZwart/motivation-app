<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FriendResource extends JsonResource
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
            'friendship_id' => $this->pivot->id,
            'id' => $this->pivot->friend_id,
            'username' => $this->username,
            'friends_since' => $this->pivot->created_at->toDateString(),
        ];
    }
}
