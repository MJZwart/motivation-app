<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\GroupRole;

class GroupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $user = $this->findLoggedGroupUser();
        return [
            'id' => $this->id,
            'time_created' => $this->created_at,
            'time_updated' => $this->updated_at,
            'name' => $this->name,
            'description' => $this->description,
            'is_public' => (boolean) $this->is_public,
            'require_application' => (boolean) $this->require_application,
            'members' => $this->groupUsers->count(),
            'is_member' => !!$user,
            'rank' => $user ? new GroupRoleResource(GroupRole::find($user->rank)) : null,
            'level' => $this->level,
        ];
    }
}
