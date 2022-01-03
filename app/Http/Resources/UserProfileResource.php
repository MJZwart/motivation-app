<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Character;
use App\Http\Resources\FriendResource;
use App\Http\Resources\CharacterResource;

class UserProfileResource extends JsonResource
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
            'created_at' => $this->created_at->toDateString(),
            'username' => $this->username,
            'display_picture' => $this->display_picture,
            'rewardObj' => $this->getActiveRewardObjectResource(),
            'achievements' => $this->show_achievements ? $this->achievements : false,
            'friends' => $this->show_friends ? FriendResource::collection($this->friends) : false,
        ];
    }
}
