<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FeedbackResource extends JsonResource
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
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'type' => $this->type,
            'text' => $this->text,
            'email' => $this->email,
            'user_id' => $this->user?->id,
            'username' => $this->user?->username,
            'archived' => !!$this->archived,
            'diagnostics' => $this->diagnostics,
        ];
    }
}
