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
            'created_at' => $this->created_at->toDateString(),
            'full_display_name' => $this->full_display_name,
            'display_picture' => $this->display_picture,
            'character' => new CharacterResource(Character::where('user_id', $this->id)->get()->first()), //Will need to be fixed once multiple characters are an option
            'achievements' => $this->achievements,
            'friends' => FriendResource::collection($this->friends),
        ];
    }
}