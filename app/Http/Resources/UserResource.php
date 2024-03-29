<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\FriendResource;

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
            'rewards' => $this->rewards,
            'friends' => FriendResource::collection($this->friends->sortBy('username')),
            'email' => $this->email,
            'admin' => !!$this->admin,
            'show_achievements' => !!$this->show_achievements,
            'show_reward' => !!$this->show_reward,
            'show_friends' => !!$this->show_friends,
            'show_timeline' => !!$this->show_timeline,
            'show_tutorial' => !!$this->show_tutorial,
            'first' => !!$this->first_login,
            'language' => $this->language,
            'guest' => !!$this->guest,
        ];
    }
}
