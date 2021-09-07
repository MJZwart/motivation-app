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
            'id' => $this->pivot->id,
            'friend_id' => $this->pivot->friend_id,
            'friend' => $this->username,
            'friends_since' => $this->pivot->created_at->toDateString(),
        ];
    }
}
