<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\User;

class IncomingFriendRequestResource extends JsonResource
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
            'id' => $this->friend->id,
            'friendship_id' => $this->id,
            'friend' => $this->user->username,
            'sent' => $this->created_at,
        ];
    }
}
