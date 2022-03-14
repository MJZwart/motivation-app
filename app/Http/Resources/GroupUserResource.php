<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GroupUserResource extends JsonResource
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
            'rank' => $this->whenPivotLoaded('group_user', function() {
                return $this->pivot->rank;
            }),
            'joined' => $this->whenPivotLoaded('group_user', function() {
                return $this->pivot->joined;
            })
        ];
    }
}
