<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BlockedUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'blocked_user_id' => $this->blocked_user_id,
            'blocked_user' => $this->blockedUser()->pluck('username')->first(),
            'created_at' => $this->created_at,
        ];
    }
}
