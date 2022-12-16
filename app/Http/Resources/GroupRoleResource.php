<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GroupRoleResource extends JsonResource
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
            'name' => $this->name,
            'can_edit' => !!$this->can_edit,
            'can_manage_members' => !!$this->can_manage_members,
            'can_moderate_messages' => !!$this->can_moderate_messages,
            'owner' => !!$this->owner,
            'member' => !!$this->member,
        ];
    }
}
