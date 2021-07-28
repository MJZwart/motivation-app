<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Character;
use App\Http\Resources\FriendResource;
use App\Http\Resources\CharacterResource;

class UserResource extends JsonResource
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
            'username' => $this->username,
            'full_display_name' => $this->full_display_name,
            'rewards' => $this->rewards,
            'friends' => FriendResource::collection($this->friends->sortBy('full_display_name')),
            'email' => $this->email,
            'admin' => $this->admin,
            'show_achievements' => !!$this->show_achievements,
            'show_character' => !!$this->show_character,
            'show_friends' => !!$this->show_friends,
        ];
    }
}
