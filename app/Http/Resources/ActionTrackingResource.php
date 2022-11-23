<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ActionTrackingResource extends JsonResource
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
            'timestamp' => $this->created_at,
            'user_agent' => $this->user_agent,
            'user' => $this->user ? [
                'username' => $this->user->username,
                'id' => $this->user->id,
            ] : null,
            'action_type' => $this->action_type,
            'action' => $this->action,
            'error' => $this->error,
        ];
    }
}
