<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\FriendResource;
use App\Http\Resources\AchievementEarnedResource;

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
            'created_at' => $this->created_at,
            'username' => $this->username,
            'display_picture' => $this->display_picture,
            'rewardObj' => $this->show_reward ? $this->getActiveRewardObjectResource() : false,
            'achievements' => $this->show_achievements ? AchievementEarnedResource::collection($this->achievements) : false,
            'friends' => $this->show_friends ? FriendResource::collection($this->friends) : false,
            'suspended' => $this->isBanned() ? new UserSuspensionSummaryResource($this->getLatestSuspension()) : null,
        ];
    }
}
